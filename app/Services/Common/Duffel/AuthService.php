<?php

namespace App\Services\Common\Duffel;

use Illuminate\Support\Facades\Http;

class AuthService
{
    private string $baseUrl;
    private string $hotelBaseUrl;
    private string $token;

    public function __construct()
    {
        $this->baseUrl      = config('services.duffel.base_url');
        $this->hotelBaseUrl = config('services.duffel.hotel_base_url');
        $this->token        = config('services.duffel.token');
    }

    public function client()
    {
        if (!$this->baseUrl) {
            throw new \Exception('Duffel air base URL not configured');
        }

        return Http::withHeaders([
            'Authorization'   => 'Bearer ' . $this->token,
            'Duffel-Version'  => 'v2',
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    public function hotel()
    {
        if (!$this->hotelBaseUrl) {
            throw new \Exception('Duffel hotel base URL not configured');
        }

        return Http::withHeaders([
            'Authorization'   => 'Bearer ' . $this->token,
            'Duffel-Version'  => 'v2',
            'Accept'          => 'application/json',
            'Content-Type'    => 'application/json',
        ])->baseUrl($this->hotelBaseUrl);
    }
}