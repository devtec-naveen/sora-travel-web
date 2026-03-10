<?php

namespace App\Services\Common\Amadeus;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    public function getAccessToken()
    {
        return Cache::remember('amadeus_token', 1700, function () {
        /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::asForm()->post(
                config('services.amadeus.base_url') . '/v1/security/oauth2/token',
                [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('services.amadeus.client_id'),
                    'client_secret' => config('services.amadeus.client_secret')
                ]
            );

            return $response->json()['access_token'] ?? null;
        });
    }
}