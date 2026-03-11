<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Services\Api\ContentService;

class ContentController extends Controller
{
    protected $service;

    public function __construct(ContentService $service)
    {
        $this->service = $service;
    }

    public function specialOffers()
    {
        $data = $this->service->getSpecialOffers();
        return response()->json([
            'status' => true,
            'message' => 'Special offers fetched successfully',
            'data' => $data
        ]);
    }

    public function popularDestinations()
    {
        $data = $this->service->getPopularDestinations();

        return response()->json([
            'status' => true,
            'message' => 'Popular destinations fetched successfully',
            'data' => $data
        ]);
    }
}