<?php

namespace App\Services\Common\Duffel;

class AirportService
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Search Airports / Cities
     *
     * @param string $keyword
     * @return array
     */
    public function search($keyword): array
    {
        /** @var \Illuminate\Http\Client\Response $response */
        $response = $this->authService
            ->client()
            ->get(
                $this->authService->baseUrl() . '/places/suggestions',
                [
                    'query' => $keyword
                ]
            );

        $data['data'] = collect($response['data'] ?? [])
            ->where('type', 'airport')
            ->values()
            ->all();

        return $data;
    }
}
