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
            ->post($this->auth->baseUrl() . '/air/offer_requests?limit=50', $params);

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
            ->post($this->auth->baseUrl() . '/air/offer_requests?limit=50', $params);

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
            ->post($this->auth->baseUrl() . '/air/offer_requests?limit=50', $params);

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
}