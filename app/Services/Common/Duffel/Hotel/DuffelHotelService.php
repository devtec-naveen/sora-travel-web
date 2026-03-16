<?php

namespace App\Services\Common\Duffel\Hotel;

use Illuminate\Support\Facades\Http;

class DuffelHotelService
{
    protected string $baseUrl;
    protected string $token;
    protected string $placesUrl;

    public function __construct()
    {
        $this->baseUrl   = config('services.duffelHotel.base_url', 'https://api.duffel.com/v1/stays');
        $this->token     = config('services.duffelHotel.token');
        $this->placesUrl = 'https://api.duffel.com/places/suggestions';
    }

    /**
     * Get geographic coordinates from a city/neighborhood name
     *
     * @param string $query
     * @return array|null ['latitude' => float, 'longitude' => float] or null if not found
     */
    public function getCoordinatesFromPlace(string $query): ?array
    {
        $response = Http::withToken($this->token)
            ->get($this->placesUrl, ['query' => $query]);

            /** @var Response $response */
        if (!$response->successful()) {
            return null;
        }

        $data = $response->json('data', []);

        foreach ($data as $place) {
            if (isset($place['geographic_coordinates'])) {
                return [
                    'latitude'  => $place['geographic_coordinates']['latitude'],
                    'longitude' => $place['geographic_coordinates']['longitude'],
                ];
            }
        }

        return null;
    }

    /**
     * Search hotels by city/neighborhood name
     *
     * @param string $locationName
     * @param string $checkIn YYYY-MM-DD
     * @param string $checkOut YYYY-MM-DD
     * @param int $guests Number of guests
     * @param int $rooms Number of rooms
     * @param int $radiusKm Search radius in km
     * @return array
     */
public function searchByLocation(
    float $latitude,
    float $longitude,
    string $checkIn,
    string $checkOut,
    int $adults   = 1,
    int $children = 0,
    int $rooms    = 1,
    int $radiusKm = 10
): array {
    // Guests array banao — adults + children
    $guests = array_merge(
        array_fill(0, $adults,   ['type' => 'adult']),
        array_fill(0, $children, ['type' => 'child', 'age' => 10])
    );
 
    // Duffel Stays search — EXACT format
    // POST /stays/searches  (NOT /search)
    $response = Http::withToken($this->token)
        ->withHeaders([
            'Duffel-Version' => 'v2',
            'Accept'         => 'application/json',
            'Content-Type'   => 'application/json',
        ])
        ->post($this->baseUrl . '/stays/searches', [
            'data' => [                          // "data" wrapper zaroori hai
                'check_in_date'  => $checkIn,    // "2026-03-16"
                'check_out_date' => $checkOut,   // "2026-03-26"
                'rooms'          => $rooms,
                'guests'         => $guests,
                'location'       => [
                    'geographic' => [            // "geographic" key, "geographic_coordinates" nahi
                        'latitude'  => $latitude,
                        'longitude' => $longitude,
                        'radius'    => $radiusKm,
                    ],
                ],
            ],
        ]);
 
    if ($response->failed()) {
        return [
            'error'   => true,
            'status'  => $response->status(),
            'message' => $response->json()['errors'][0]['message'] ?? $response->body(),
        ];
    }
 
    $body = $response->json();
 
    // Duffel returns search_id — results async poll karne padte hain
    $searchId = $body['data']['id'] ?? null;
 
    if (!$searchId) {
        return ['error' => true, 'message' => 'No search ID returned'];
    }
 
    // Poll karo results ke liye — max 10 attempts
    return $this->pollResults($searchId);
}
 
private function pollResults(string $searchId, int $maxAttempts = 10): array
{
    for ($i = 0; $i < $maxAttempts; $i++) {
 
        $response = Http::withToken($this->token)
            ->withHeaders(['Duffel-Version' => 'v2'])
            ->get($this->baseUrl . '/stays/searches/' . $searchId);
 
        if ($response->failed()) {
            return ['error' => true, 'message' => $response->body()];
        }
 
        $data = $response->json('data');
 
        // Ready ho gaya
        if (($data['status'] ?? '') === 'ready') {
            return [
                'error'     => false,
                'search_id' => $searchId,
                'results'   => $data['results'] ?? [],
            ];
        }
 
        // Abhi pending hai — 1 second wait karo
        sleep(1);
    }
 
    return ['error' => true, 'message' => 'Search timed out after ' . $maxAttempts . ' seconds'];
}

    /**
     * Search hotels by known accommodation IDs
     *
     * @param array $accommodationIds
     * @param string $checkIn
     * @param string $checkOut
     * @param int $guests
     * @param int $rooms
     * @param bool $fetchRates
     * @return array
     */
    public function searchByAccommodationIds(
        array $accommodationIds,
        string $checkIn,
        string $checkOut,
        int $guests = 1,
        int $rooms = 1,
        bool $fetchRates = false
    ): array {
        $body = [
            'accommodation' => [
                'ids'         => $accommodationIds,
                'fetch_rates' => $fetchRates,
            ],
            'check_in_date'  => $checkIn,
            'check_out_date' => $checkOut,
            'guests'         => array_fill(0, $guests, ['type' => 'adult']),
            'rooms'          => $rooms,
        ];

        $response = Http::withToken($this->token)
            ->post($this->baseUrl . '/search', $body);

            /** @var Response $response */
        if ($response->successful()) {
            return $response->json();
        }

        return [
            'error'   => true,
            'message' => $response->body(),
        ];
    }
}