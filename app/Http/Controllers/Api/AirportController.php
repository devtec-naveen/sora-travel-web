<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Common\Duffel\AirportService;
use Illuminate\Support\Facades\Validator;


class AirportController extends Controller
{
    protected $airportService;

    public function __construct(AirportService $airportService)
    {
        $this->airportService = $airportService;
    }

    /**
     * Search Airports
     */
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'keyword' => 'required|string|min:2'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed.',
                    'errors'  => $validator->errors(),
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $airports = $this->airportService->search($request->keyword);

            return response()->json([
                'status'  => true,
                'message' => 'Airports fetched successfully.',
                'data'    => $airports
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Something went wrong.',
                'error'   => $e->getMessage(),
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}