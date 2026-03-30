<?php

namespace App\Services\Common\Hotelbeds;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║           HotelbedsService  — Advanced Edition              ║
 * ╠══════════════════════════════════════════════════════════════╣
 * ║  Features added over base version:                          ║
 * ║  ✅ Retry logic (3 attempts, exponential back-off)          ║
 * ║  ✅ Intelligent response caching (per-method TTL)           ║
 * ║  ✅ Structured DTO responses                                ║
 * ║  ✅ Request/Response logging (structured JSON)              ║
 * ║  ✅ Rate-limit header tracking                              ║
 * ║  ✅ Multi-room / multi-occupancy search                     ║
 * ║  ✅ Children age support                                    ║
 * ║  ✅ Promo/offer codes                                       ║
 * ║  ✅ Content API: hotel list, facilities, room types         ║
 * ║  ✅ Board types lookup                                      ║
 * ║  ✅ Pagination helper                                       ║
 * ║  ✅ Currency + language config per call                     ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * Docs: https://developer.hotelbeds.com/documentation/hotels/booking-api/
 */
class HotelbedsService
{
    // ─── Cache TTLs (seconds) ──────────────────────────────────────────────
    private const TTL_DESTINATIONS = 86400;   // 24 hours  – rarely changes
    private const TTL_HOTEL_DETAILS = 3600;   // 1 hour    – static content
    private const TTL_FACILITIES   = 86400;   // 24 hours
    private const TTL_ROOM_TYPES   = 86400;   // 24 hours
    private const TTL_BOARDS       = 86400;   // 24 hours
    private const TTL_AVAILABILITY = 60;      // 1 minute  – prices change fast
    private const TTL_CHECKRATE    = 30;      // 30 secs   – very volatile

    // ─── Retry config ──────────────────────────────────────────────────────
    private const MAX_RETRIES    = 3;
    private const RETRY_DELAY_MS = 500;        // base delay (doubles each attempt)

    // ─── Credentials & base URL ────────────────────────────────────────────
    protected string $apiKey;
    protected string $secret;
    protected string $baseUrl;
    protected string $contentUrl;
    protected int    $timeout;

    public function __construct()
    {
        $this->apiKey     = config('services.hotelbeds.key');
        $this->secret     = config('services.hotelbeds.secret');
        $this->baseUrl    = rtrim(config('services.hotelbeds.base_url', 'https://api.test.hotelbeds.com'), '/');
        $this->contentUrl = $this->baseUrl . '/hotel-content-api/1.0';
        $this->timeout    = (int) config('services.hotelbeds.timeout', 30);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  AUTHENTICATION
    //  SHA256( apiKey + secret + UTC_unix_timestamp )
    // ══════════════════════════════════════════════════════════════════════

    private function signature(): string
    {
        return hash('sha256', $this->apiKey . $this->secret . time());
    }

    private function headers(array $extra = []): array
    {
        return array_merge([
            'Api-key'         => $this->apiKey,
            'X-Signature'     => $this->signature(),
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/json',
            'Accept-Encoding' => 'gzip',
        ], $extra);
    }

    // ══════════════════════════════════════════════════════════════════════
    //  HTTP CLIENT — with retry + structured logging
    // ══════════════════════════════════════════════════════════════════════

    /**
     * GET with automatic retry + optional caching.
     *
     * @param string   $url        Full URL
     * @param array    $params     Query parameters
     * @param int|null $cacheTtl   Seconds to cache (null = no cache)
     * @param string   $cacheKey   Unique cache key
     */
    private function get(
        string $url,
        array $params = [],
        ?int $cacheTtl = null,
        string $cacheKey = ''
    ): array {
        if ($cacheTtl && $cacheKey) {
            return Cache::remember($cacheKey, $cacheTtl, fn () => $this->doGet($url, $params));
        }

        return $this->doGet($url, $params);
    }

    private function doGet(string $url, array $params): array
    {
        return $this->withRetry(function () use ($url, $params) {
            $start    = microtime(true);
            $response = Http::withHeaders($this->headers())
                ->timeout($this->timeout)
                ->get($url, $params);

            $this->logRequest('GET', $url, $params, $response, $start);
            $this->trackRateLimit($response);

            return $this->parseResponse($response, "GET {$url}");
        });
    }

    /**
     * POST with automatic retry (no caching — POSTs change state).
     *
     * @param string   $url
     * @param array    $body
     * @param int|null $cacheTtl  Set only for idempotent POSTs like checkRate
     * @param string   $cacheKey
     */
    private function post(
        string $url,
        array $body,
        ?int $cacheTtl = null,
        string $cacheKey = ''
    ): array {
        if ($cacheTtl && $cacheKey) {
            return Cache::remember($cacheKey, $cacheTtl, fn () => $this->doPost($url, $body));
        }

        return $this->doPost($url, $body);
    }

    private function doPost(string $url, array $body): array
    {
        return $this->withRetry(function () use ($url, $body) {
            $start    = microtime(true);
            $response = Http::withHeaders($this->headers())
                ->timeout($this->timeout)
                ->post($url, $body);

            $this->logRequest('POST', $url, $body, $response, $start);
            $this->trackRateLimit($response);

            return $this->parseResponse($response, "POST {$url}");
        });
    }

    private function delete(string $url, array $params = []): array
    {
        return $this->withRetry(function () use ($url, $params) {
            $start    = microtime(true);
            $response = Http::withHeaders($this->headers())
                ->timeout($this->timeout)
                ->delete($url, $params);

            $this->logRequest('DELETE', $url, $params, $response, $start);

            return $this->parseResponse($response, "DELETE {$url}");
        });
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RETRY LOGIC — exponential back-off, skips 4xx (client errors)
    // ══════════════════════════════════════════════════════════════════════

    private function withRetry(callable $fn, int $maxAttempts = self::MAX_RETRIES): array
    {
        $attempt = 0;

        while (true) {
            $attempt++;

            try {
                return $fn();
            } catch (HotelbedsApiException $e) {
                // 4xx = client error — retrying won't help
                if ($e->statusCode >= 400 && $e->statusCode < 500) {
                    throw $e;
                }

                if ($attempt >= $maxAttempts) {
                    throw $e;
                }

                $delayMs = self::RETRY_DELAY_MS * (2 ** ($attempt - 1)); // 500 → 1000 → 2000
                Log::warning("[Hotelbeds] Retry {$attempt}/{$maxAttempts} after {$delayMs}ms", [
                    'error' => $e->getMessage(),
                ]);
                usleep($delayMs * 1000);
            } catch (\Exception $e) {
                if ($attempt >= $maxAttempts) {
                    throw $e;
                }
                usleep(self::RETRY_DELAY_MS * 1000);
            }
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RESPONSE PARSING
    // ══════════════════════════════════════════════════════════════════════

    private function parseResponse(Response $response, string $context): array
    {
        if ($response->failed()) {
            $errorMsg = $response->json('error.message')
                ?? $response->json('message')
                ?? "API call failed: {$context}";

        }

        return $response->json() ?? [];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  LOGGING — structured JSON logs for easy parsing
    // ══════════════════════════════════════════════════════════════════════

    private function logRequest(
        string $method,
        string $url,
        array $payload,
        Response $response,
        float $start
    ): void {
        $duration = round((microtime(true) - $start) * 1000, 2); // ms

        $level = $response->successful() ? 'info' : 'error';

        Log::{$level}('[Hotelbeds]', [
            'method'   => $method,
            'url'      => $url,
            'status'   => $response->status(),
            'duration' => "{$duration}ms",
            'payload'  => $this->sanitizePayload($payload),
        ]);
    }

    /** Remove sensitive card data before logging */
    private function sanitizePayload(array $payload): array
    {
        $sensitive = ['cardNumber', 'cardCVC', 'expirationDate'];

        array_walk_recursive($payload, function (&$value, $key) use ($sensitive) {
            if (in_array($key, $sensitive, true)) {
                $value = '***REDACTED***';
            }
        });

        return $payload;
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RATE-LIMIT TRACKING
    //  Stores remaining quota in cache so the app can react proactively
    // ══════════════════════════════════════════════════════════════════════

    private function trackRateLimit(Response $response): void
    {
        $remaining = $response->header('X-RateLimit-Remaining');
        $reset     = $response->header('X-RateLimit-Reset');

        if ($remaining !== null) {
            Cache::put('hotelbeds.rate_limit.remaining', (int) $remaining, 60);
        }
        if ($reset !== null) {
            Cache::put('hotelbeds.rate_limit.reset_at', $reset, 60);
        }

        // Log a warning if quota drops below 10%
        $max = (int) config('services.hotelbeds.quota_max', 50);
        if ($remaining !== null && (int) $remaining <= max(1, $max * 0.10)) {
            Log::warning('[Hotelbeds] Rate limit low', [
                'remaining' => $remaining,
                'reset_at'  => $reset,
            ]);
        }
    }

    /** Check remaining quota from cache */
    public function getRateLimitStatus(): array
    {
        return [
            'remaining' => Cache::get('hotelbeds.rate_limit.remaining', 'unknown'),
            'reset_at'  => Cache::get('hotelbeds.rate_limit.reset_at', 'unknown'),
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  RESPONSE HELPERS
    // ══════════════════════════════════════════════════════════════════════

    private function ok(string $message, mixed $data = []): array
    {
        return ['success' => true, 'message' => $message, 'data' => $data];
    }

    private function fail(string $message, int $code = 500, array $extra = []): array
    {
        return array_merge(
            ['success' => false, 'message' => $message, 'data' => [], 'code' => $code],
            $extra
        );
    }

    // ══════════════════════════════════════════════════════════════════════
    //  DEBUG — test credentials without making a real booking
    //
    //  php artisan tinker
    //  >>> app(\App\Services\Common\Hotelbeds\HotelbedsService::class)->debug();
    // ══════════════════════════════════════════════════════════════════════
    public function debug(): array
    {
        $ts  = (string) time();
        $sig = hash('sha256', $this->apiKey . $this->secret . $ts);

        $response = Http::withHeaders([
            'Api-key'      => $this->apiKey,
            'X-Signature'  => $sig,
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ])->get($this->contentUrl . '/hotels', [
            'fields'   => 'code,name',
            'language' => 'ENG',
            'from'     => 1,
            'to'       => 1,
        ]);

        return [
            'timestamp'   => $ts,
            'signature'   => $sig,
            'http_status' => $response->status(),
            'env'         => config('services.hotelbeds.env', 'test'),
            'base_url'    => $this->baseUrl,
            'body_sample' => $response->json(),
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  1. SEARCH DESTINATIONS  (cached 24 h)
    //
    //  $service->searchDestinations('dubai');
    //  $service->searchDestinations('paris', language: 'ENG', countryCode: 'FR');
    // ══════════════════════════════════════════════════════════════════════
    public function searchDestinations(
        string $keyword,
        string $language    = 'ENG',
        ?string $countryCode = null
    ): array {
        try {

            $data = $this->get(
                $this->contentUrl . '/locations/destinations',
                array_filter([
                    'fields'      => 'code,name,countryCode,isoCode,zones,groupZones,type',
                    'language'    => $language,
                    'countryCode' => $countryCode,
                    'name'        => $keyword,
                    'from'        => 1,
                    'to'          => 400,
                ]),
            );

            dd($data);

            $destinations = collect($data['destinations'] ?? [])
                ->filter(fn ($d) => str_contains(
                    strtolower($d['name']['content'] ?? ''),
                    strtolower($keyword)
                ))
                ->map(fn ($d) => [
                    'code'         => $d['code'],
                    'name'         => $d['name']['content'] ?? '',
                    'country_code' => $d['countryCode'] ?? '',
                ])
                ->values()
                ->toArray();

            return $this->ok('Destinations fetched', $destinations);

        }catch (\Exception $e) {
            Log::error('[Hotelbeds] searchDestinations: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  2. SEARCH HOTELS  (cache 60s — prices volatile)
    //
    //  Basic:
    //  $service->searchHotels([
    //      'destination_code' => 'DXB',
    //      'check_in'         => '2025-09-01',
    //      'check_out'        => '2025-09-05',
    //      'adults'           => 2,
    //      'children'         => 0,
    //      'rooms'            => 1,
    //  ]);
    //
    //  Advanced — multi-room, children ages, filters, promo:
    //  $service->searchHotels([
    //      'destination_code' => 'DXB',
    //      'check_in'         => '2025-09-01',
    //      'check_out'        => '2025-09-05',
    //      'occupancies'      => [               // overrides adults/children/rooms
    //          ['rooms' => 1, 'adults' => 2, 'children' => 1, 'children_ages' => [5]],
    //          ['rooms' => 1, 'adults' => 2, 'children' => 0],
    //      ],
    //      'min_category'     => 3,
    //      'max_category'     => 5,
    //      'max_hotels'       => 30,
    //      'currency'         => 'USD',
    //      'language'         => 'ENG',
    //      'board_codes'      => ['BB', 'HB'],   // filter by board type
    //      'accommodation'    => ['HOTEL'],       // HOTEL, APART, VILLA ...
    //      'keywords'         => [123, 456],      // keyword filter codes
    //      'promo_code'       => 'SUMMER25',      // promotional code
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function searchHotels(array $params): array
    {
        try {
            // Build occupancies — supports both simple and advanced format
            $occupancies = $this->buildOccupancies($params);

            $body = [
                'stay' => [
                    'checkIn'  => $params['check_in'],
                    'checkOut' => $params['check_out'],
                ],
                'occupancies' => $occupancies,
                'destination' => ['code' => $params['destination_code']],
                'filter'      => array_filter([
                    'maxHotels'       => (int) ($params['max_hotels']    ?? 20),
                    'minCategory'     => (int) ($params['min_category']  ?? 1),
                    'maxCategory'     => (int) ($params['max_category']  ?? 5),
                    'minRate'         => $params['min_rate']             ?? null,
                    'maxRate'         => $params['max_rate']             ?? null,
                    'paymentType'     => $params['payment_type']         ?? 'AT_WEB',
                    'hotelPackage'    => $params['hotel_package']        ?? 'BOTH',
                ]),
            ];

            // Optional: board codes filter
            if (!empty($params['board_codes'])) {
                $body['boards'] = [
                    'included'  => true,
                    'board'     => (array) $params['board_codes'],
                ];
            }

            // Optional: accommodation type filter
            if (!empty($params['accommodation'])) {
                $body['accommodations'] = (array) $params['accommodation'];
            }

            // Optional: keyword filter
            if (!empty($params['keywords'])) {
                $body['keywords'] = [
                    'allIncluded' => false,
                    'keyword'     => (array) $params['keywords'],
                ];
            }

            // Optional: promo/offer code
            if (!empty($params['promo_code'])) {
                $body['promotionCode'] = $params['promo_code'];
            }

            // Optional: currency & language
            if (!empty($params['currency'])) {
                $body['currency'] = $params['currency'];
            }
            if (!empty($params['language'])) {
                $body['language'] = $params['language'];
            }

            $cacheKey = 'hotelbeds.search.' . md5(json_encode($body));

            $data   = $this->post(
                $this->baseUrl . '/hotel-api/1.0/hotels',
                $body,
                self::TTL_AVAILABILITY,
                $cacheKey
            );

            $hotels = collect($data['hotels']['hotels'] ?? [])
                ->map(fn ($h) => $this->mapHotel($h))
                ->toArray();

            return $this->ok('Hotels fetched successfully', [
                'total'         => count($hotels),
                'check_in'      => $params['check_in'],
                'check_out'     => $params['check_out'],
                'nights'        => $this->countNights($params['check_in'], $params['check_out']),
                'occupancies'   => $occupancies,
                'hotels'        => $hotels,
            ]);

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode, ['api_error' => $e->apiResponse]);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] searchHotels: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  3. GET HOTEL DETAILS  (cached 1 hour)
    //
    //  $service->getHotelDetails(12345);
    //  $service->getHotelDetails(12345, language: 'CAS');
    // ══════════════════════════════════════════════════════════════════════
    public function getHotelDetails(int|string $hotelCode, string $language = 'ENG'): array
    {
        try {
            $cacheKey = "hotelbeds.hotel_details.{$hotelCode}.{$language}";

            $data = $this->get(
                $this->contentUrl . "/hotels/{$hotelCode}/details",
                ['language' => $language, 'useSecondaryLanguage' => false],
                self::TTL_HOTEL_DETAILS,
                $cacheKey
            );

            $hotel = $data['hotel'] ?? [];

            return $this->ok('Hotel details fetched', [
                'code'        => $hotel['code']                                    ?? null,
                'name'        => $hotel['name']['content']                         ?? null,
                'category'    => $hotel['categoryCode']                            ?? null,
                'category_name' => $hotel['categoryGroupCode']                     ?? null,
                'chain'       => $hotel['chainCode']                               ?? null,
                'description' => $hotel['description']['content']                  ?? null,
                'address'     => $hotel['address']['content']                      ?? null,
                'city'        => $hotel['city']['content']                         ?? null,
                'postal_code' => $hotel['postalCode']                              ?? null,
                'country'     => $hotel['countryCode']                             ?? null,
                'latitude'    => $hotel['coordinates']['latitude']                 ?? null,
                'longitude'   => $hotel['coordinates']['longitude']                ?? null,
                'phones'      => $hotel['phones']                                  ?? [],
                'email'       => $hotel['email']                                   ?? null,
                'web'         => $hotel['web']                                     ?? null,
                'check_in'    => $hotel['checkIn']                                 ?? null,
                'check_out'   => $hotel['checkOut']                                ?? null,
                'facilities'  => collect($hotel['facilities'] ?? [])
                    ->map(fn ($f) => [
                        'code'     => $f['facilityCode']                           ?? null,
                        'name'     => $f['facilityName']['content']                ?? null,
                        'group'    => $f['facilityGroupCode']                      ?? null,
                        'voucher'  => $f['voucher']                                ?? false,
                    ])
                    ->values()->toArray(),
                'rooms'       => collect($hotel['rooms'] ?? [])
                    ->map(fn ($r) => [
                        'code' => $r['roomCode']                                   ?? null,
                        'name' => $r['roomType']['description']['content']         ?? null,
                    ])
                    ->values()->toArray(),
                'images'      => collect($hotel['images'] ?? [])
                    ->map(fn ($i) => [
                        'url'     => 'https://photos.hotelbeds.com/giata/' . ($i['path'] ?? ''),
                        'type'    => $i['imageTypeCode']                           ?? null,
                        'order'   => $i['visualOrder']                             ?? 0,
                    ])
                    ->sortBy('order')->values()->toArray(),
            ]);

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getHotelDetails: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  4. GET AVAILABLE ROOMS  (cache 60s)
    //
    //  $service->getAvailableRooms([
    //      'hotel_code'    => 12345,
    //      'check_in'      => '2025-09-01',
    //      'check_out'     => '2025-09-05',
    //      'adults'        => 2,
    //      'children'      => 1,
    //      'children_ages' => [7],
    //      'rooms'         => 1,
    //      'currency'      => 'USD',  // optional
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function getAvailableRooms(array $params): array
    {
        try {
            $occupancies = $this->buildOccupancies($params);

            $body = [
                'stay'        => [
                    'checkIn'  => $params['check_in'],
                    'checkOut' => $params['check_out'],
                ],
                'occupancies' => $occupancies,
                'hotels'      => ['hotel' => [(int) $params['hotel_code']]],
            ];

            if (!empty($params['currency'])) {
                $body['currency'] = $params['currency'];
            }

            $cacheKey = 'hotelbeds.rooms.' . md5(json_encode($body));

            $data  = $this->post(
                $this->baseUrl . '/hotel-api/1.0/hotels',
                $body,
                self::TTL_AVAILABILITY,
                $cacheKey
            );

            $hotel = collect($data['hotels']['hotels'] ?? [])->first();

            if (!$hotel) {
                return $this->fail('No availability found for this hotel', 404);
            }

            $rooms = collect($hotel['rooms'] ?? [])
                ->map(fn ($room) => [
                    'room_code' => $room['code'],
                    'room_name' => $room['name'],
                    'rates'     => collect($room['rates'] ?? [])
                        ->map(fn ($rate) => $this->mapRate($rate, $hotel['currency'] ?? 'EUR'))
                        ->sortBy('net_price')
                        ->values()->toArray(),
                ])
                ->values()->toArray();

            return $this->ok('Rooms fetched successfully', [
                'hotel_code'  => $hotel['code'],
                'hotel_name'  => $hotel['name'],
                'currency'    => $hotel['currency'] ?? 'EUR',
                'min_rate'    => (float) ($hotel['minRate'] ?? 0),
                'max_rate'    => (float) ($hotel['maxRate'] ?? 0),
                'nights'      => $this->countNights($params['check_in'], $params['check_out']),
                'check_in'    => $params['check_in'],
                'check_out'   => $params['check_out'],
                'occupancies' => $occupancies,
                'rooms'       => $rooms,
            ]);

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getAvailableRooms: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  5. CHECK RATE  (cache 30s — price volatile)
    //
    //  $service->checkRate('RATE_KEY_HERE');
    //  $service->checkRate('RATE_KEY_HERE', currency: 'USD');
    // ══════════════════════════════════════════════════════════════════════
    public function checkRate(string $rateKey, ?string $currency = null): array
    {
        try {
            $body = ['rooms' => [['rateKey' => $rateKey]]];
            if ($currency) {
                $body['currency'] = $currency;
            }

            $cacheKey = 'hotelbeds.checkrate.' . md5($rateKey . $currency);

            $data  = $this->post(
                $this->baseUrl . '/hotel-api/1.0/checkrates',
                $body,
                self::TTL_CHECKRATE,
                $cacheKey
            );

            $hotel = $data['hotel'] ?? [];
            $rate  = $hotel['rooms'][0]['rates'][0] ?? [];

            return $this->ok('Rate verified successfully', [
                'hotel_code'           => $hotel['code']                  ?? null,
                'hotel_name'           => $hotel['name']                  ?? null,
                'currency'             => $hotel['currency']              ?? null,
                'net_price'            => (float) ($rate['net']           ?? 0),
                'selling_rate'         => (float) ($rate['sellingRate']   ?? $rate['net'] ?? 0),
                'board_code'           => $rate['boardCode']              ?? null,
                'board_name'           => $rate['boardName']              ?? null,
                'rate_comments'        => $rate['rateComments']           ?? null,
                'rate_key'             => $rateKey,
                'is_non_refundable'    => ($rate['rateType'] ?? '') === 'NRFN',
                'cancellation_policies'=> $this->mapCancellationPolicies($rate['cancellationPolicies'] ?? []),
                'packaging'            => $rate['packaging']              ?? false,
                'offers'               => collect($rate['offers'] ?? [])
                    ->map(fn ($o) => ['code' => $o['code'], 'name' => $o['name']])
                    ->toArray(),
            ]);

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] checkRate: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  6. CREATE BOOKING
    //
    //  Simple (single room, no payment data for direct billing):
    //  $service->createBooking([
    //      'rate_key'         => 'RATE_KEY_FROM_CHECKRATE',
    //      'check_in'         => '2025-09-01',
    //      'check_out'        => '2025-09-05',
    //      'holder_name'      => 'Rahul',
    //      'holder_surname'   => 'Sharma',
    //      'guest_name'       => 'Rahul',
    //      'guest_surname'    => 'Sharma',
    //      'client_reference' => 'ORDER-001',
    //      'remark'           => 'Late check-in',
    //  ]);
    //
    //  Advanced (multi-room, payment card):
    //  $service->createBooking([
    //      ...
    //      'rooms' => [
    //          [
    //              'rate_key' => 'KEY1',
    //              'paxes' => [
    //                  ['roomId' => 1, 'type' => 'AD', 'name' => 'Ali', 'surname' => 'Khan'],
    //                  ['roomId' => 1, 'type' => 'CH', 'name' => 'Sara', 'surname' => 'Khan', 'age' => 7],
    //              ],
    //          ],
    //          ['rate_key' => 'KEY2', 'paxes' => [...]],
    //      ],
    //      'card_type'     => 'VI',
    //      'card_number'   => '4111111111111111',
    //      'card_holder'   => 'Ali Khan',
    //      'expiry_month'  => '12',
    //      'expiry_year'   => '2026',
    //      'card_cvc'      => '123',
    //      'contact_email' => 'ali@example.com',
    //      'contact_phone' => '+971501234567',
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function createBooking(array $params): array
    {
        try {
            // Build rooms array — support both simple & multi-room format
            $rooms = $this->buildBookingRooms($params);

            $body = [
                'holder'          => [
                    'name'    => $params['holder_name'],
                    'surname' => $params['holder_surname'],
                ],
                'rooms'           => $rooms,
                'clientReference' => $params['client_reference'],
                'remark'          => $params['remark'] ?? '',
                'tolerance'       => $params['price_tolerance'] ?? 2, // % price increase tolerance
            ];

            // Optional: check-in / check-out at booking level
            if (!empty($params['check_in'])) {
                $body['checkIn']  = $params['check_in'];
                $body['checkOut'] = $params['check_out'];
            }

            // Optional: payment card data
            if (!empty($params['card_number'])) {
                $body['paymentData'] = [
                    'paymentCard' => [
                        'cardType'       => $params['card_type']    ?? 'VI',
                        'cardNumber'     => $params['card_number'],
                        'cardHolderName' => $params['card_holder'],
                        'expirationDate' => $params['expiry_month'] . '/' . $params['expiry_year'],
                        'cardCVC'        => $params['card_cvc'],
                    ],
                    'contactData' => [
                        'email'       => $params['contact_email'],
                        'phoneNumber' => $params['contact_phone'] ?? '',
                    ],
                ];
            }

            $data    = $this->post($this->baseUrl . '/hotel-api/1.0/bookings', $body);
            $booking = $data['booking'];

            Log::info('[Hotelbeds] Booking created', [
                'reference'        => $booking['reference']        ?? null,
                'client_reference' => $booking['clientReference']  ?? null,
                'total_net'        => $booking['totalNet']         ?? null,
            ]);

            return $this->ok('Booking created successfully', $this->mapBooking($booking));

        } catch (HotelbedsApiException $e) {
            Log::error('[Hotelbeds] createBooking failed', [
                'status'   => $e->statusCode,
                'response' => $e->apiResponse,
            ]);
            return $this->fail($e->getMessage(), $e->statusCode, ['api_error' => $e->apiResponse]);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] createBooking: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  7. GET BOOKING DETAILS
    //
    //  $service->getBooking('HB-12345678');
    // ══════════════════════════════════════════════════════════════════════
    public function getBooking(string $reference): array
    {
        try {
            $data    = $this->get($this->baseUrl . "/hotel-api/1.0/bookings/{$reference}");
            $booking = $data['booking'] ?? [];

            return $this->ok('Booking details fetched', $this->mapBooking($booking));

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getBooking: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  8. CANCEL BOOKING
    //
    //  $service->cancelBooking('HB-12345678');
    //  $service->cancelBooking('HB-12345678', simulation: true); // dry-run
    // ══════════════════════════════════════════════════════════════════════
    public function cancelBooking(string $reference, bool $simulation = false): array
    {
        try {
            $url = $this->baseUrl . "/hotel-api/1.0/bookings/{$reference}";
            if ($simulation) {
                $url .= '?flag=SIMULATION';
            }

            $data    = $this->delete($url);
            $booking = $data['booking'] ?? [];

            Log::info('[Hotelbeds] Booking ' . ($simulation ? 'simulation' : 'cancelled'), [
                'reference'  => $reference,
                'simulation' => $simulation,
                'amount'     => $booking['cancellationAmount'] ?? 0,
            ]);

            return $this->ok(
                $simulation ? 'Simulation only — booking NOT cancelled' : 'Booking cancelled successfully',
                [
                    'reference'           => $booking['reference']          ?? null,
                    'status'              => $booking['status']             ?? null,
                    'cancellation_amount' => (float) ($booking['cancellationAmount'] ?? 0),
                    'currency'            => $booking['currency']           ?? null,
                    'simulation'          => $simulation,
                ]
            );

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] cancelBooking: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  9. LIST BOOKINGS  (with filters)
    //
    //  $service->listBookings([
    //      'from'             => '2025-01-01',
    //      'to'               => '2025-12-31',
    //      'status'           => 'CONFIRMED',
    //      'client_reference' => 'ORDER-001',
    //      'page'             => 1,
    //      'per_page'         => 20,
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function listBookings(array $filters = []): array
    {
        try {
            $perPage = (int) ($filters['per_page'] ?? 20);
            $page    = max(1, (int) ($filters['page'] ?? 1));
            $from    = ($page - 1) * $perPage;
            $to      = $from + $perPage - 1;

            $query = array_filter([
                'from'            => $filters['from']             ?? null,
                'to'              => $filters['to']               ?? null,
                'status'          => $filters['status']           ?? null,
                'clientReference' => $filters['client_reference'] ?? null,
                'start'           => $from,
                'end'             => $to,
                'filterType'      => $filters['filter_type']      ?? 'CREATION',
            ]);

            $data = $this->get($this->baseUrl . '/hotel-api/1.0/bookings', $query);

            $bookings = collect($data['bookings'] ?? [])
                ->map(fn ($b) => $this->mapBooking($b))
                ->toArray();

            return $this->ok('Bookings listed', [
                'total'    => count($bookings),
                'page'     => $page,
                'per_page' => $perPage,
                'bookings' => $bookings,
            ]);

        } catch (HotelbedsApiException $e) {
            return $this->fail($e->getMessage(), $e->statusCode);
        } catch (\Exception $e) {
            Log::error('[Hotelbeds] listBookings: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CONTENT API — ADDITIONAL LOOKUPS
    // ══════════════════════════════════════════════════════════════════════

    /**
     * 10. Get list of hotels in a destination  (paginated, cached 24h)
     *
     * $service->getHotelList(['destination_code' => 'PMI', 'page' => 1]);
     */
    public function getHotelList(array $params = []): array
    {
        try {
            $perPage  = (int) ($params['per_page'] ?? 100);
            $page     = max(1, (int) ($params['page'] ?? 1));
            $from     = ($page - 1) * $perPage + 1;
            $to       = $from + $perPage - 1;
            $language = $params['language'] ?? 'ENG';

            $query = array_filter([
                'fields'           => 'code,name,categoryCode,destinationCode,countryCode,coordinates',
                'language'         => $language,
                'from'             => $from,
                'to'               => $to,
                'destinationCode'  => $params['destination_code'] ?? null,
                'countryCode'      => $params['country_code']     ?? null,
                'categoryCode'     => $params['category_code']    ?? null,
            ]);

            $cacheKey = 'hotelbeds.hotel_list.' . md5(json_encode($query));

            $data   = $this->get($this->contentUrl . '/hotels', $query, self::TTL_DESTINATIONS, $cacheKey);

            $hotels = collect($data['hotels'] ?? [])
                ->map(fn ($h) => [
                    'code'             => $h['code'],
                    'name'             => $h['name']['content'] ?? '',
                    'category'         => $h['categoryCode']    ?? null,
                    'destination_code' => $h['destinationCode'] ?? null,
                    'country_code'     => $h['countryCode']     ?? null,
                    'latitude'         => $h['coordinates']['latitude']  ?? null,
                    'longitude'        => $h['coordinates']['longitude'] ?? null,
                ])->toArray();

            return $this->ok('Hotel list fetched', [
                'total'    => count($hotels),
                'page'     => $page,
                'per_page' => $perPage,
                'hotels'   => $hotels,
            ]);

        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getHotelList: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 11. Get all board types (BB, HB, AI etc.) — cached 24h
     *
     * $service->getBoardTypes();
     */
    public function getBoardTypes(string $language = 'ENG'): array
    {
        try {
            $data  = $this->get(
                $this->contentUrl . '/types/boards',
                ['fields' => 'code,description', 'language' => $language, 'from' => 1, 'to' => 50],
                self::TTL_BOARDS,
                "hotelbeds.boards.{$language}"
            );

            $boards = collect($data['boards'] ?? [])
                ->map(fn ($b) => [
                    'code'        => $b['code'],
                    'description' => $b['description']['content'] ?? '',
                ])->toArray();

            return $this->ok('Board types fetched', $boards);

        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getBoardTypes: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 12. Get room types — cached 24h
     *
     * $service->getRoomTypes();
     */
    public function getRoomTypes(string $language = 'ENG'): array
    {
        try {
            $data  = $this->get(
                $this->contentUrl . '/types/rooms',
                ['fields' => 'code,description', 'language' => $language, 'from' => 1, 'to' => 200],
                self::TTL_ROOM_TYPES,
                "hotelbeds.room_types.{$language}"
            );

            $rooms = collect($data['rooms'] ?? [])
                ->map(fn ($r) => [
                    'code'        => $r['code'],
                    'description' => $r['description']['content'] ?? '',
                ])->toArray();

            return $this->ok('Room types fetched', $rooms);

        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getRoomTypes: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    /**
     * 13. Get facilities list — cached 24h
     *
     * $service->getFacilities();
     */
    public function getFacilities(string $language = 'ENG'): array
    {
        try {
            $data = $this->get(
                $this->contentUrl . '/types/facilities',
                ['fields' => 'code,description,facilityGroupCode', 'language' => $language, 'from' => 1, 'to' => 500],
                self::TTL_FACILITIES,
                "hotelbeds.facilities.{$language}"
            );

            $facilities = collect($data['facilities'] ?? [])
                ->map(fn ($f) => [
                    'code'       => $f['code'],
                    'group_code' => $f['facilityGroupCode'] ?? null,
                    'name'       => $f['description']['content'] ?? '',
                ])->toArray();

            return $this->ok('Facilities fetched', $facilities);

        } catch (\Exception $e) {
            Log::error('[Hotelbeds] getFacilities: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  CACHE MANAGEMENT
    // ══════════════════════════════════════════════════════════════════════

    /** Flush all Hotelbeds caches */
    public function flushCache(): void
    {
        // Note: works best with Redis/tagged cache driver
        // For array/file driver, clear individual keys
        $keys = [
            'hotelbeds.rate_limit.remaining',
            'hotelbeds.rate_limit.reset_at',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        Log::info('[Hotelbeds] Cache flushed');
    }

    // ══════════════════════════════════════════════════════════════════════
    //  PRIVATE MAPPERS & HELPERS
    // ══════════════════════════════════════════════════════════════════════

    /** Build occupancies array — supports simple & advanced format */
    private function buildOccupancies(array $params): array
    {
        // Advanced format already provided
        if (!empty($params['occupancies'])) {
            return collect($params['occupancies'])->map(function ($occ) {
                $paxes = [];

                // Adults
                for ($i = 0; $i < (int) ($occ['adults'] ?? 2); $i++) {
                    $paxes[] = ['type' => 'AD', 'age' => 30];
                }

                // Children with ages
                $childrenAges = $occ['children_ages'] ?? [];
                for ($i = 0; $i < (int) ($occ['children'] ?? 0); $i++) {
                    $paxes[] = ['type' => 'CH', 'age' => $childrenAges[$i] ?? 8];
                }

                $built = [
                    'rooms'    => (int) ($occ['rooms']    ?? 1),
                    'adults'   => (int) ($occ['adults']   ?? 2),
                    'children' => (int) ($occ['children'] ?? 0),
                ];

                if (!empty($paxes)) {
                    $built['paxes'] = $paxes;
                }

                return $built;
            })->toArray();
        }

        // Simple format
        $occ = [
            'rooms'    => (int) ($params['rooms']    ?? 1),
            'adults'   => (int) ($params['adults']   ?? 2),
            'children' => (int) ($params['children'] ?? 0),
        ];

        // Add paxes if children ages given
        if (!empty($params['children_ages'])) {
            $paxes = [];
            for ($i = 0; $i < $occ['adults']; $i++) {
                $paxes[] = ['type' => 'AD', 'age' => 30];
            }
            foreach ($params['children_ages'] as $age) {
                $paxes[] = ['type' => 'CH', 'age' => $age];
            }
            $occ['paxes'] = $paxes;
        }

        return [$occ];
    }

    /** Build rooms array for booking — simple & multi-room */
    private function buildBookingRooms(array $params): array
    {
        // Multi-room advanced format
        if (!empty($params['rooms'])) {
            return collect($params['rooms'])->map(fn ($room) => [
                'rateKey' => $room['rate_key'],
                'paxes'   => $room['paxes'] ?? [[
                    'roomId'  => 1,
                    'type'    => 'AD',
                    'name'    => $params['guest_name']    ?? $params['holder_name'],
                    'surname' => $params['guest_surname'] ?? $params['holder_surname'],
                ]],
            ])->toArray();
        }

        // Simple single-room
        return [[
            'rateKey' => $params['rate_key'],
            'paxes'   => [[
                'roomId'  => 1,
                'type'    => 'AD',
                'name'    => $params['guest_name']    ?? $params['holder_name'],
                'surname' => $params['guest_surname'] ?? $params['holder_surname'],
            ]],
        ]];
    }

    /** Map raw hotel data to clean array */
    private function mapHotel(array $h): array
    {
        return [
            'code'             => $h['code'],
            'name'             => $h['name'],
            'category_code'    => $h['categoryCode']    ?? null,
            'destination_code' => $h['destinationCode'] ?? null,
            'destination_name' => $h['destinationName'] ?? null,
            'zone_name'        => $h['zoneName']        ?? null,
            'latitude'         => $h['latitude']        ?? null,
            'longitude'        => $h['longitude']       ?? null,
            'min_rate'         => (float) ($h['minRate'] ?? 0),
            'max_rate'         => (float) ($h['maxRate'] ?? 0),
            'currency'         => $h['currency']        ?? 'EUR',
            'rooms'            => collect($h['rooms'] ?? [])->map(fn ($r) => [
                'code'  => $r['code'],
                'name'  => $r['name'],
                'rates' => collect($r['rates'] ?? [])
                    ->map(fn ($rate) => $this->mapRate($rate, $h['currency'] ?? 'EUR'))
                    ->sortBy('net_price')
                    ->values()->toArray(),
            ])->toArray(),
        ];
    }

    /** Map raw rate to clean array */
    private function mapRate(array $rate, string $currency): array
    {
        return [
            'rate_key'             => $rate['rateKey'],
            'rate_type'            => $rate['rateType']      ?? null,
            'rate_class'           => $rate['rateClass']     ?? null,
            'net_price'            => (float) ($rate['net']         ?? 0),
            'selling_rate'         => (float) ($rate['sellingRate'] ?? $rate['net'] ?? 0),
            'discount'             => (float) ($rate['discount']    ?? 0),
            'discount_pct'         => (float) ($rate['discountPCT'] ?? 0),
            'currency'             => $currency,
            'board_code'           => $rate['boardCode']     ?? null,
            'board_name'           => $rate['boardName']     ?? null,
            'rooms_available'      => $rate['rooms']         ?? 0,
            'adults'               => $rate['adults']        ?? 0,
            'children'             => $rate['children']      ?? 0,
            'is_non_refundable'    => ($rate['rateType'] ?? '') === 'NRFN',
            'packaging'            => $rate['packaging']     ?? false,
            'cancellation_policies'=> $this->mapCancellationPolicies($rate['cancellationPolicies'] ?? []),
            'offers'               => collect($rate['offers'] ?? [])
                ->map(fn ($o) => ['code' => $o['code'], 'name' => $o['name'] ?? ''])
                ->toArray(),
            'taxes'                => collect($rate['taxes']['taxes'] ?? [])
                ->map(fn ($t) => [
                    'amount'    => (float) ($t['amount'] ?? 0),
                    'currency'  => $t['currency'] ?? $currency,
                    'type'      => $t['type'] ?? null,
                    'included'  => $t['included'] ?? false,
                ])->toArray(),
        ];
    }

    /** Map cancellation policies to human-readable format */
    private function mapCancellationPolicies(array $policies): array
    {
        return collect($policies)->map(fn ($p) => [
            'amount'    => (float) ($p['amount'] ?? 0),
            'from'      => $p['from'] ?? null,
            'type'      => $p['type'] ?? 'AMOUNT',
            'penalty'   => isset($p['amount']) ? "€{$p['amount']} after {$p['from']}" : null,
        ])->toArray();
    }

    /** Map booking to clean array */
    private function mapBooking(array $booking): array
    {
        return [
            'reference'        => $booking['reference']        ?? null,
            'client_reference' => $booking['clientReference']  ?? null,
            'status'           => $booking['status']           ?? null,
            'total_net'        => (float) ($booking['totalNet']         ?? 0),
            'total_selling'    => (float) ($booking['totalSellingRate'] ?? 0),
            'currency'         => $booking['currency']         ?? null,
            'creation_date'    => $booking['creationDate']     ?? null,
            'remark'           => $booking['remark']           ?? null,
            'holder'           => $booking['holder']           ?? [],
            'hotel'            => [
                'code'      => $booking['hotel']['code']     ?? null,
                'name'      => $booking['hotel']['name']     ?? null,
                'check_in'  => $booking['hotel']['checkIn']  ?? null,
                'check_out' => $booking['hotel']['checkOut'] ?? null,
                'rooms'     => $booking['hotel']['rooms']    ?? [],
            ],
            'invoice_company'  => $booking['invoiceCompany']  ?? null,
            'cancellation_amount' => (float) ($booking['cancellationAmount'] ?? 0),
        ];
    }

    /** Count nights between two dates */
    private function countNights(string $checkIn, string $checkOut): int
    {
        return (int) \Carbon\Carbon::parse($checkIn)->diffInDays(\Carbon\Carbon::parse($checkOut));
    }
}