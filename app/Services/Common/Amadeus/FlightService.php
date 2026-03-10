<?php

namespace App\Services\Common\Amadeus;

use Illuminate\Support\Facades\Http;

class FlightService
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function searchFlights($data)
    {
        $token = $this->authService->getAccessToken();

        $params = [
            'originLocationCode' => $data['origin'],
            'destinationLocationCode' => $data['destination'],
            'departureDate' => $data['departureDate'],
            'adults' => $data['adults'] ?? 1,
            'children' => $data['children'] ?? 0,
            'infants' => $data['infants'] ?? 0,
            'travelClass' => $data['cabin'] ?? 'ECONOMY',
            'max' => $data['limit'] ?? 10,
            'currencyCode' => 'EUR',
        ];

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($token)
            ->get(config('services.amadeus.base_url') . '/v2/shopping/flight-offers', $params);
        return $response->json();
    }
}
