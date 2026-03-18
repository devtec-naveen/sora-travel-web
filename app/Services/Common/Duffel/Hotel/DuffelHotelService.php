<?php

namespace App\Services\Common\Duffel\Hotel;

use App\Services\Common\Duffel\AuthService;

class DuffelHotelService
{
    protected AuthService $authService;
    protected string $placesUrl;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->placesUrl   = config('services.duffel.hotel_base_url') . '/places/suggestions';
    }

    public function suggestPlaces(string $keyword): array
    {
        $response = $this->authService
            ->client(true)
            ->get('/places/suggestions', ['query' => $keyword]);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed()) {
            return [];
        }

        $places = $response->json('data', []);

        $countryNames = [
            'IN' => 'India',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'AE' => 'UAE',
            'TH' => 'Thailand',
            'SG' => 'Singapore',
        ];

        return collect($places)
            ->filter(fn($place) => !empty($place['iata_city_code']) && !empty($place['city_name']))
            ->unique('iata_city_code')
            ->map(fn($place) => [
                'code'      => $place['iata_city_code'],
                'city'      => $place['city_name'],
                'name'      => 'Hotels in ' . $place['city_name'],
                'country'   => $countryNames[$place['iata_country_code'] ?? ''] ?? ($place['iata_country_code'] ?? ''),
                'latitude'  => $place['latitude'] ?? null,
                'longitude' => $place['longitude'] ?? null,
            ])
            ->values()
            ->all();
    }

    public function searchByLocation(
        float $latitude,
        float $longitude,
        string $checkIn,
        string $checkOut,
        int $adults = 1,
        int $children = 0,
        int $rooms = 1,
        int $radiusKm = 10,
        int $page = 1,
        int $perPage = 20
     ): array {
        $guests = array_merge(
            array_fill(0, $adults, ['type' => 'adult']),
            array_fill(0, $children, ['type' => 'child', 'age' => 10])
        );

        $response = $this->authService
            ->hotel()
            ->post('/search', [
                'data' => [
                    'check_in_date'  => $checkIn,
                    'check_out_date' => $checkOut,
                    'rooms'          => $rooms,
                    'guests'         => $guests,
                    'location'       => [
                        'radius' => (int) $radiusKm,
                        'geographic_coordinates' => [
                            'latitude'  => (float) $latitude,
                            'longitude' => (float) $longitude,
                        ],
                    ],
                ],
            ]);

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed()) {
            return ['error' => true, 'message' => $response->body()];
        }

        $body    = $response->json();
        $results = $body['data']['results'] ?? null;

        if (is_null($results)) {
            return ['error' => true, 'message' => 'No results returned from API'];
        }

        $total      = count($results);
        $offset     = ($page - 1) * $perPage;
        $paginated  = array_slice($results, $offset, $perPage);
        $totalPages = (int) ceil($total / $perPage);

        return [
            'error'        => false,
            'results'      => $paginated,
            'all_results' => $results,
            'total'        => $total,
            'per_page'     => $perPage,
            'current_page' => $page,
            'total_pages'  => $totalPages,
            'has_more'     => $page < $totalPages,
        ];
    }

    public function getAccommodationDetail(string $accommodationId): array
    {
        $response = $this->authService
            ->hotel()
            ->get("/accommodation/{$accommodationId}");

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed()) {
            return [
                'error'   => true,
                'message' => $response->body(),
            ];
        }

        return [
            'error' => false,
            'data'  => $response->json('data', []),
        ];
    }

    public function fetchAllRates(string $searchResultId): array
    {
        $response = $this->authService
            ->hotel()
            ->post("/search_results/{$searchResultId}/actions/fetch_all_rates");

        /** @var \Illuminate\Http\Client\Response $response */
        if ($response->failed()) {
            return ['error' => true, 'message' => $response->body()];
        }

        return [
            'error' => false,
            'data'  => $response->json('data', []),
        ];
    }

    public function getHotelWithRooms(string $accommodationId, string $searchResultId): array
    {
        $detailResponse = $this->getAccommodationDetail($accommodationId);

        if ($detailResponse['error'] ?? false) {
            return [
                'error'   => true,
                'message' => $detailResponse['message'] ?? 'Unable to fetch hotel details.',
            ];
        }

        $hotelData = $detailResponse['data'] ?? [];
        if (!empty($searchResultId)) {
            $roomsResponse = $this->fetchAllRates($searchResultId);

            if (!($roomsResponse['error'] ?? false)) {
                $hotelData['rooms'] = $roomsResponse['data']['accommodation']['rooms'] ?? [];
            }
        }

        return [
            'error' => false,
            'data'  => $hotelData,
        ];
    }
}
