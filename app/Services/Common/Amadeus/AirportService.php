<?php

namespace App\Services\Common\Amadeus;

use Illuminate\Support\Facades\Http;

class AirportService
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function search($keyword)
    {
        $token = $this->authService->getAccessToken();

        /** @var \Illuminate\Http\Client\Response $response */
        $response = Http::withToken($token)->get(
            config('services.amadeus.base_url') . '/v1/reference-data/locations',
            [
                'keyword' => $keyword,
                'subType' => 'AIRPORT',
                'page[limit]' => 10
            ]
        );

        return $response->json();
    }
}