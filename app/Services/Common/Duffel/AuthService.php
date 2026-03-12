<?php

namespace App\Services\Common\Duffel;

use Illuminate\Support\Facades\Http;

class AuthService
{
    private $baseUrl;
    private $token;

    public function __construct()
    {
        $this->baseUrl = config('services.duffel.base_url');
        $this->token   = config('services.duffel.token');
    }

    /**
     * Duffel HTTP Client
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    public function client()
    {
        return Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Duffel-Version' => 'v2',
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Get Base URL
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->baseUrl;
    }
}