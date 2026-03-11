<?php

namespace App\Services\Common\Duffel;

use Illuminate\Support\Facades\Http;

class DuffelService
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl = config('services.duffel.base_url');
        $this->token = config('services.duffel.token');
    }

    private function client()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Duffel-Version' => 'v1',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @param array $data
     * @return array
     */
    public function searchFlights(array $data): array
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
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.duffel.token'),
            'Duffel-Version' => 'v2',
            'Accept' => 'application/json',
        ])
            ->post(config('services.duffel.base_url') . '/air/offer_requests?limit=50', $params);

        /** @var array $responseData */
        $responseData = $response->json();
        return $responseData;
    }

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