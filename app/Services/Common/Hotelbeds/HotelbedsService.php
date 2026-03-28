<?php

namespace App\Services\Common\Hotelbeds;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelbedsService
{
    protected string $apiKey;
    protected string $secret;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey  = config('services.hotelbeds.key');
        $this->secret  = config('services.hotelbeds.secret');
        $this->baseUrl = rtrim(config('services.hotelbeds.base_url', 'https://api.test.hotelbeds.com'), '/');


        // dd($this->apiKey,$this->secret,$this->baseUrl);

    }

    // ──────────────────────────────────────────────────────────────────────
    //  SIGNATURE  — SHA256(apiKey + secret + UTC_timestamp_seconds)
    //  ⚠️  time() is always UTC epoch in PHP — no timezone conversion needed
    // ──────────────────────────────────────────────────────────────────────

    private function makeHeaders(): array
    {
        $timestamp = (string) time();

        return [
            'Api-key'     => $this->apiKey,
            'X-Signature' => hash('sha256', $this->apiKey . $this->secret . $timestamp),
            'Accept'      => 'application/json',
            'Content-Type'=> 'application/json',
        ];
    }

    private function client()
    {
        return Http::withHeaders($this->makeHeaders())
            ->baseUrl($this->baseUrl)
            ->timeout(30);
    }

    private function ok(string $message, mixed $data = []): array
    {
        return ['success' => true, 'message' => $message, 'data' => $data];
    }

    private function fail(string $message, int $code = 500): array
    {
        return ['success' => false, 'message' => $message, 'data' => [], 'code' => $code];
    }

    private function apiError($response, string $fallback): array
    {
        $msg = $response->json('error.message')
            ?? $response->json('message')
            ?? $fallback;

        Log::error("[HotelbedsService] {$fallback}", [
            'status' => $response->status(),
            'body'   => $response->body(),
        ]);

        return $this->fail($msg, $response->status());
    }

    // ══════════════════════════════════════════════════════════════════════
    //  DEBUG — run in tinker first to confirm credentials are correct
    //
    //  php artisan tinker
    //  >>> app(\App\Services\Common\Hotelbeds\HotelbedsService::class)->debug();
    //
    //  Should return http_status: 200 if keys are correct
    // ══════════════════════════════════════════════════════════════════════
    public function debug(): array
    {
        $timestamp = (string) time();

        $signature = hash('sha256', $this->apiKey . $this->secret . $timestamp);

        $response = Http::withHeaders([
            'Api-key'     => $this->apiKey,
            'X-Signature' => $signature,
            'Accept'      => 'application/json',
            'Content-Type'=> 'application/json',
        ])->get($this->baseUrl . '/hotel-content-api/1.0/hotels', [
            'fields'   => 'code,name',
            'language' => 'ENG',
            'from'     => 1,
            'to'       => 1,
        ]);

        return [
            'timestamp'   => $timestamp,
            'signature'   => $signature,
            'http_status' => $response->status(),
            'body'        => $response->body(),
        ];
    }

    // ══════════════════════════════════════════════════════════════════════
    //  1. SEARCH DESTINATIONS
    //
    //  $service->searchDestinations('dubai');
    // ══════════════════════════════════════════════════════════════════════
    public function searchDestinations(string $keyword, string $language = 'ENG'): array
    {
        try {
            $response = $this->client()->get('/hotel-content-api/1.0/locations/destinations', [
                'fields' => 'code,name,countryCode',
                'language' => $language,
                'from'     => 1,
                'to'       => 100,
            ]);

            dd($response);

            if ($response->failed()) {
                return $this->apiError($response, 'Failed to fetch destinations');
            }

            $destinations = collect($response->json('destinations') ?? [])
                ->filter(fn($d) => str_contains(
                    strtolower($d['name']['content'] ?? ''),
                    strtolower($keyword)
                ))
                ->map(fn($d) => [
                    'code'         => $d['code'],
                    'name'         => $d['name']['content'] ?? '',
                    'country_code' => $d['countryCode'] ?? '',
                ])
                ->values()
                ->toArray();

            return $this->ok('Destinations fetched', $destinations);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] searchDestinations: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  2. SEARCH HOTELS
    //
    //  $service->searchHotels([
    //      'destination_code' => 'DXB',
    //      'check_in'         => '2025-09-01',
    //      'check_out'        => '2025-09-05',
    //      'adults'           => 2,
    //      'children'         => 0,
    //      'rooms'            => 1,
    //      'max_hotels'       => 20,
    //      'min_category'     => 3,   // optional
    //      'max_category'     => 5,   // optional
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function searchHotels(array $params): array
    {
        try {
            $body = [
                'stay' => [
                    'checkIn'  => $params['check_in'],
                    'checkOut' => $params['check_out'],
                ],
                'occupancies' => [[
                    'rooms'    => (int) ($params['rooms']    ?? 1),
                    'adults'   => (int) ($params['adults']   ?? 2),
                    'children' => (int) ($params['children'] ?? 0),
                ]],
                'destination' => [
                    'code' => $params['destination_code'],
                ],
                'filter' => [
                    'maxHotels'   => (int) ($params['max_hotels']   ?? 20),
                    'minCategory' => (int) ($params['min_category'] ?? 1),
                    'maxCategory' => (int) ($params['max_category'] ?? 5),
                ],
            ];

            $response = $this->client()->post('/hotel-api/1.0/hotels', $body);

            if ($response->failed()) {
                return $this->apiError($response, 'Hotel search failed');
            }

            $hotels = collect($response->json('hotels.hotels') ?? [])
                ->map(fn($h) => [
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
                    'currency'         => $h['currency'] ?? 'EUR',
                    'rooms'            => $h['rooms'] ?? [],
                ])
                ->toArray();

            return $this->ok('Hotels fetched successfully', [
                'total'     => count($hotels),
                'check_in'  => $params['check_in'],
                'check_out' => $params['check_out'],
                'hotels'    => $hotels,
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] searchHotels: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  3. GET HOTEL DETAILS
    //
    //  $service->getHotelDetails(12345);
    // ══════════════════════════════════════════════════════════════════════
    public function getHotelDetails(int|string $hotelCode, string $language = 'ENG'): array
    {
        try {
            $response = $this->client()->get("/hotel-content-api/1.0/hotels/{$hotelCode}/details", [
                'language'             => $language,
                'useSecondaryLanguage' => false,
            ]);

            if ($response->failed()) {
                return $this->apiError($response, 'Hotel details not found');
            }

            $hotel = $response->json('hotel') ?? [];

            return $this->ok('Hotel details fetched', [
                'code'        => $hotel['code']                      ?? null,
                'name'        => $hotel['name']['content']           ?? null,
                'category'    => $hotel['categoryCode']              ?? null,
                'description' => $hotel['description']['content']    ?? null,
                'address'     => $hotel['address']['content']        ?? null,
                'city'        => $hotel['city']['content']           ?? null,
                'latitude'    => $hotel['coordinates']['latitude']   ?? null,
                'longitude'   => $hotel['coordinates']['longitude']  ?? null,
                'phones'      => $hotel['phones'] ?? [],
                'facilities'  => collect($hotel['facilities'] ?? [])
                    ->pluck('facilityName.content')
                    ->filter()->values()->toArray(),
                'images'      => collect($hotel['images'] ?? [])
                    ->map(fn($i) => [
                        'url'   => 'http://photos.hotelbeds.com/giata/' . ($i['path'] ?? ''),
                        'type'  => $i['type']['code'] ?? null,
                        'order' => $i['visualOrder'] ?? 0,
                    ])
                    ->sortBy('order')->values()->toArray(),
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] getHotelDetails: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  4. GET AVAILABLE ROOMS
    //
    //  $service->getAvailableRooms([
    //      'hotel_code' => 12345,
    //      'check_in'   => '2025-09-01',
    //      'check_out'  => '2025-09-05',
    //      'adults'     => 2,
    //      'children'   => 0,
    //      'rooms'      => 1,
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function getAvailableRooms(array $params): array
    {
        try {
            $body = [
                'stay' => [
                    'checkIn'  => $params['check_in'],
                    'checkOut' => $params['check_out'],
                ],
                'occupancies' => [[
                    'rooms'    => (int) ($params['rooms']    ?? 1),
                    'adults'   => (int) ($params['adults']   ?? 2),
                    'children' => (int) ($params['children'] ?? 0),
                ]],
                'hotels' => [
                    'hotel' => [(int) $params['hotel_code']],
                ],
            ];

            $response = $this->client()->post('/hotel-api/1.0/hotels', $body);

            if ($response->failed()) {
                return $this->apiError($response, 'Room availability check failed');
            }

            $hotel = collect($response->json('hotels.hotels') ?? [])->first();

            if (! $hotel) {
                return $this->fail('No availability found for this hotel');
            }

            $rooms = collect($hotel['rooms'] ?? [])
                ->map(fn($room) => [
                    'room_code' => $room['code'],
                    'room_name' => $room['name'],
                    'rates'     => collect($room['rates'] ?? [])
                        ->map(fn($rate) => [
                            'rate_key'             => $rate['rateKey'],
                            'rate_type'            => $rate['rateType'],
                            'net_price'            => (float) ($rate['net'] ?? 0),
                            'selling_rate'         => (float) ($rate['sellingRate'] ?? $rate['net'] ?? 0),
                            'currency'             => $hotel['currency'],
                            'board_code'           => $rate['boardCode'] ?? null,
                            'board_name'           => $rate['boardName'] ?? null,
                            'rooms_available'      => $rate['rooms'] ?? 0,
                            'adults'               => $rate['adults'] ?? 0,
                            'children'             => $rate['children'] ?? 0,
                            'cancellation_policies'=> $rate['cancellationPolicies'] ?? [],
                            'is_non_refundable'    => ($rate['rateType'] ?? '') === 'NRFN',
                        ])
                        ->values()->toArray(),
                ])
                ->values()->toArray();

            return $this->ok('Rooms fetched successfully', [
                'hotel_code' => $hotel['code'],
                'hotel_name' => $hotel['name'],
                'currency'   => $hotel['currency'],
                'min_rate'   => (float) ($hotel['minRate'] ?? 0),
                'max_rate'   => (float) ($hotel['maxRate'] ?? 0),
                'rooms'      => $rooms,
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] getAvailableRooms: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  5. CHECK RATE (verify price before booking)
    //
    //  $service->checkRate('RATE_KEY_HERE');
    // ══════════════════════════════════════════════════════════════════════
    public function checkRate(string $rateKey): array
    {
        try {
            $response = $this->client()->post('/hotel-api/1.0/checkrates', [
                'rooms' => [['rateKey' => $rateKey]],
            ]);

            if ($response->failed()) {
                return $this->apiError($response, 'Rate check failed');
            }

            $hotel = $response->json('hotel') ?? [];
            $rate  = $hotel['rooms'][0]['rates'][0] ?? [];

            return $this->ok('Rate verified successfully', [
                'hotel_code'    => $hotel['code'] ?? null,
                'hotel_name'    => $hotel['name'] ?? null,
                'currency'      => $hotel['currency'] ?? null,
                'net_price'     => (float) ($rate['net'] ?? 0),
                'selling_rate'  => (float) ($rate['sellingRate'] ?? $rate['net'] ?? 0),
                'board_name'    => $rate['boardName'] ?? null,
                'rate_comments' => $rate['rateComments'] ?? null,
                'rate_key'      => $rateKey,
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] checkRate: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  6. CREATE BOOKING
    //
    //  $service->createBooking([
    //      'rate_key'         => 'RATE_KEY_FROM_ROOM',
    //      'check_in'         => '2025-09-01',
    //      'check_out'        => '2025-09-05',
    //      'holder_name'      => 'Rahul',
    //      'holder_surname'   => 'Sharma',
    //      'guest_name'       => 'Rahul',
    //      'guest_surname'    => 'Sharma',
    //      'client_reference' => 'ORDER-001',
    //      'remark'           => 'Late check-in',
    //      'card_type'        => 'VI',
    //      'card_number'      => '4111111111111111',
    //      'card_holder'      => 'Rahul Sharma',
    //      'expiry_month'     => '12',
    //      'expiry_year'      => '2026',
    //      'card_cvc'         => '123',
    //      'contact_email'    => 'rahul@example.com',
    //      'contact_phone'    => '+919876543210',
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function createBooking(array $params): array
    {
        try {
            $body = [
                'holder' => [
                    'name'    => $params['holder_name'],
                    'surname' => $params['holder_surname'],
                ],
                'hotel' => [
                    'checkIn'  => $params['check_in']  ?? null,
                    'checkOut' => $params['check_out'] ?? null,
                    'rooms'    => [[
                        'rateKey' => $params['rate_key'],
                        'paxes'   => [[
                            'roomId'  => 1,
                            'type'    => 'AD',
                            'name'    => $params['guest_name'],
                            'surname' => $params['guest_surname'],
                        ]],
                    ]],
                ],
                'clientReference' => $params['client_reference'],
                'remark'          => $params['remark'] ?? '',
                'paymentData'     => [
                    'paymentCard' => [
                        'cardType'       => $params['card_type']  ?? 'VI',
                        'cardNumber'     => $params['card_number'],
                        'cardHolderName' => $params['card_holder'],
                        'expirationDate' => $params['expiry_month'] . '/' . $params['expiry_year'],
                        'cardCVC'        => $params['card_cvc'],
                    ],
                    'contactData' => [
                        'email'       => $params['contact_email'],
                        'phoneNumber' => $params['contact_phone'] ?? '',
                    ],
                ],
            ];

            $response = $this->client()->post('/hotel-api/1.0/bookings', $body);

            if ($response->failed()) {
                return $this->apiError($response, 'Booking creation failed');
            }

            $booking = $response->json('booking');

            Log::info('[HotelbedsService] Booking created', [
                'reference'        => $booking['reference'] ?? null,
                'client_reference' => $params['client_reference'],
            ]);

            return $this->ok('Booking created successfully', [
                'reference'        => $booking['reference'],
                'client_reference' => $booking['clientReference'] ?? $params['client_reference'],
                'status'           => $booking['status'],
                'total_net'        => (float) ($booking['totalNet'] ?? 0),
                'total_selling'    => (float) ($booking['totalSellingRate'] ?? 0),
                'currency'         => $booking['currency'] ?? null,
                'creation_date'    => $booking['creationDate'] ?? null,
                'holder'           => $booking['holder'] ?? [],
                'hotel' => [
                    'code'      => $booking['hotel']['code']     ?? null,
                    'name'      => $booking['hotel']['name']     ?? null,
                    'check_in'  => $booking['hotel']['checkIn']  ?? null,
                    'check_out' => $booking['hotel']['checkOut'] ?? null,
                    'rooms'     => $booking['hotel']['rooms']    ?? [],
                ],
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] createBooking: ' . $e->getMessage());
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
            $response = $this->client()->get("/hotel-api/1.0/bookings/{$reference}");

            if ($response->failed()) {
                return $this->apiError($response, 'Booking not found');
            }

            $booking = $response->json('booking');

            return $this->ok('Booking details fetched', [
                'reference'     => $booking['reference'],
                'status'        => $booking['status'],
                'total_net'     => (float) ($booking['totalNet'] ?? 0),
                'currency'      => $booking['currency'] ?? null,
                'creation_date' => $booking['creationDate'] ?? null,
                'holder'        => $booking['holder'] ?? [],
                'hotel'         => $booking['hotel'] ?? [],
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] getBooking: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  8. CANCEL BOOKING
    //
    //  $service->cancelBooking('HB-12345678');
    //  $service->cancelBooking('HB-12345678', simulation: true); // dry run
    // ══════════════════════════════════════════════════════════════════════
    public function cancelBooking(string $reference, bool $simulation = false): array
    {
        try {
            $url = "/hotel-api/1.0/bookings/{$reference}";
            if ($simulation) {
                $url .= '?flag=SIMULATION';
            }

            $response = $this->client()->delete($url);

            if ($response->failed()) {
                return $this->apiError($response, 'Cancellation failed');
            }

            $booking = $response->json('booking');

            Log::info('[HotelbedsService] Booking cancelled', [
                'reference'  => $reference,
                'simulation' => $simulation,
            ]);

            return $this->ok(
                $simulation ? 'Simulation only — booking NOT cancelled' : 'Booking cancelled successfully',
                [
                    'reference'           => $booking['reference'],
                    'status'              => $booking['status'],
                    'cancellation_amount' => (float) ($booking['cancellationAmount'] ?? 0),
                ]
            );

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] cancelBooking: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }

    // ══════════════════════════════════════════════════════════════════════
    //  9. LIST ALL BOOKINGS
    //
    //  $service->listBookings([
    //      'from'             => '2025-01-01',
    //      'to'               => '2025-12-31',
    //      'status'           => 'CONFIRMED',
    //      'client_reference' => 'ORDER-001',
    //  ]);
    // ══════════════════════════════════════════════════════════════════════
    public function listBookings(array $filters = []): array
    {
        try {
            $query = array_filter([
                'from'            => $filters['from']             ?? null,
                'to'              => $filters['to']               ?? null,
                'status'          => $filters['status']           ?? null,
                'clientReference' => $filters['client_reference'] ?? null,
            ]);

            $response = $this->client()->get('/hotel-api/1.0/bookings', $query);

            if ($response->failed()) {
                return $this->apiError($response, 'Failed to fetch bookings');
            }

            return $this->ok('Bookings listed', [
                'total'    => count($response->json('bookings') ?? []),
                'bookings' => $response->json('bookings') ?? [],
            ]);

        } catch (\Exception $e) {
            Log::error('[HotelbedsService] listBookings: ' . $e->getMessage());
            return $this->fail($e->getMessage());
        }
    }
}