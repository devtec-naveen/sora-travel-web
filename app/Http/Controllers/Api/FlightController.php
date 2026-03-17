<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\Common\Duffel\DuffelService;

class FlightController extends Controller
{
    protected $duffelService;

    public function __construct(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
    }

    public function listing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'origin' => 'required|string',
                'destination' => 'required|string',
                'departureDate' => 'required|date',
                'returnDate' => 'nullable|date',
                'adults' => 'nullable|integer|min:1',
                'children' => 'nullable|integer|min:0',
                'infants' => 'nullable|integer|min:0',
                'cabin' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->searchFlightsMain($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Flights fetched successfully',
                'data' => $result
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'Flight search failed',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}