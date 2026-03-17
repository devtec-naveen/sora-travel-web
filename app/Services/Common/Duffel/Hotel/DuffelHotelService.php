<?php

namespace App\Services\Common\Duffel\Hotel;
use App\Services\Common\Duffel\AuthService;
use Illuminate\Support\Facades\Http;

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

        return $response->json('data', []);
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

}