<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FlightController extends Controller
{
    public function __construct(protected DuffelService $duffelService) {}

    public function listing(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'origin'        => 'required|string',
                'destination'   => 'required|string',
                'departureDate' => 'required|date',
                'returnDate'    => 'nullable|date',
                'adults'        => 'nullable|integer|min:1',
                'children'      => 'nullable|integer|min:0',
                'infants'       => 'nullable|integer|min:0',
                'cabin'         => 'nullable|string',
                'max_price'     => 'nullable|numeric',
                'stops'         => 'nullable|array',
                'stops.*'       => 'integer|in:0,1,2',
                'airlines'      => 'nullable|array',
                'airlines.*'    => 'string',
                'refundable'    => 'nullable|boolean',
                'sort'          => 'nullable|string|in:price_low_high,price_high_low,duration,depart_earliest,arrive_earliest',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->searchFlightsMain($request->all());
            $offers = $result['data']['offers'] ?? [];

            $meta = $this->duffelService->extractFilterMeta($offers);

            $filteredOffers = $this->duffelService->filterAndSort($offers, [
                'max_price'  => $request->input('max_price', PHP_INT_MAX),
                'stops'      => $request->input('stops', []),
                'airlines'   => $request->input('airlines', []),
                'refundable' => $request->boolean('refundable', false),
                'sort'       => $request->input('sort', ''),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Flights fetched successfully',
                'meta'    => $meta,
                'data'    => $filteredOffers,
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Flight search failed',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function seats(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'offer_id' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation failed',
                    'errors'  => $validator->errors()
                ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->duffelService->getSeatMaps($request->input('offer_id'));

            if ($result['error']) {
                return response()->json([
                    'status'  => false,
                    'message' => $result['error'],
                ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
            }

            return response()->json([
                'status'  => true,
                'message' => 'Seat maps fetched successfully',
                'data'    => $result['seat_maps'],
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch seat maps',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}