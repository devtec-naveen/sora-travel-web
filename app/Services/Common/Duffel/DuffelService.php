<?php

namespace App\Services\Common\Duffel;

use Illuminate\Support\Facades\Http;

class DuffelService
{
    private $auth;

    public function __construct(AuthService $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Main Flight Search
     * Automatically detects One-way, Round-trip, Multi-city
     */
    public function searchFlightsMain(array $data): array
    {
        // Multi-city
        if (!empty($data['trips']) && count($data['trips']) > 1) {
            return $this->searchMultiCity($data);
        }

        // Round-trip
        if (!empty($data['returnDate'])) {
            return $this->searchRoundTrip($data);
        }

        // Default -> One-way
        return $this->searchOneWay($data);
    }

    /**
     * One-way Flight Search
     */
    private function searchOneWay(array $data): array
    {
        $params = [
            "data" => [
                "slices" => [
                    [
                        "origin" => $data['origin'],
                        "destination" => $data['destination'],
                        "departure_date" => $data['departureDate']
                    ]
                ],
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];

        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }

    /**
     * Round-trip Flight Search
     */
    private function searchRoundTrip(array $data): array
    {
        $slices = [
            [
                "origin" => $data['origin'],
                "destination" => $data['destination'],
                "departure_date" => $data['departureDate']
            ],
            [
                "origin" => $data['destination'],
                "destination" => $data['origin'],
                "departure_date" => $data['returnDate']
            ]
        ];

        $params = [
            "data" => [
                "slices" => $slices,
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];

        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }

    /**
     * Multi-city Flight Search
     */
    private function searchMultiCity(array $data): array
    {
        $slices = [];

        foreach ($data['trips'] as $trip) {
            $slices[] = [
                "origin" => $trip['origin'],
                "destination" => $trip['destination'],
                "departure_date" => $trip['departureDate']
            ];
        }

        $params = [
            "data" => [
                "slices" => $slices,
                "passengers" => $this->buildPassengers($data),
                "cabin_class" => strtolower($data['cabin'] ?? 'economy'),
                "max_connections" => 0
            ]
        ];
        
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->post('/air/offer_requests?limit=50', $params);

        return $response->json();
    }

    /**
     * Build Passenger List
     */
    private function buildPassengers(array $data): array
    {
        $passengers = [];

        $adults   = (int) ($data['adults'] ?? 1);
        $children = (int) ($data['children'] ?? 0);
        $infants  = (int) ($data['infants'] ?? 0);

        for ($i = 0; $i < $adults; $i++) {
            $passengers[] = ["type" => "adult"];
        }

        for ($i = 0; $i < $children; $i++) {
            $passengers[] = ["type" => "child"];
        }

        for ($i = 0; $i < $infants; $i++) {
            $passengers[] = ["type" => "infant_without_seat"];
        }

        return $passengers;
    }

    public function getOfferWithServices(string $offerId): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth
            ->client()
            ->get("/air/offers/{$offerId}", [
                'return_available_services' => 'true',
            ]);
 
        if ($response->failed()) {
            return [
                'offer'    => [],
                'services' => [],
                'error'    => 'Failed to fetch offer services. Status: ' . $response->status(),
            ];
        }
 
        $offer    = $response->json('data', []);
        $allSvcs  = $offer['available_services'] ?? [];
 
        // Sirf baggage type services lo
        $baggage  = collect($allSvcs)
            ->filter(fn($s) => ($s['type'] ?? '') === 'baggage')
            ->keyBy('id')
            ->toArray();
 
        return [
            'offer'    => $offer,
            'services' => $baggage,
            'error'    => null,
        ];
    }

    public function getSeatMaps(string $offerId): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth->client()->get('/air/seat_maps', [
            'offer_id' => $offerId,
        ]);
 
        if ($response->failed()) {
            return [
                'seat_maps' => [],
                'error'     => 'Failed to fetch seat maps. Status: ' . $response->status(),
            ];
        }
 
        return [
            'seat_maps' => $response->json('data', []),
            'error'     => null,
        ];
    }

    public function createOrder(array $data): array
    {
        
        $offerId    = $data['offer_id'];
        $passengers = $data['passengers'];
        $services   = $data['services']  ?? [];
        $amount     = $data['amount'];
        $currency   = $data['currency'];
 
        $payload = [
            'data' => [
                'type'             => 'instant',
                'selected_offers'  => [$offerId],
                'passengers'       => $passengers,
                'payments'         => [[
                    'type'     => 'balance',
                    'currency' => $currency,
                    'amount'   => $amount,
                ]],
            ],
        ]; 
        if (! empty($services)) {
            $payload['data']['services'] = $services;
        }
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->auth->client()->post('/air/orders', $payload); 
        return $response->json();
    }
}