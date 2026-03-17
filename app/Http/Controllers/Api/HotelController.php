<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Common\Duffel\Hotel\DuffelHotelService;
use Illuminate\Validation\ValidationException;

class HotelController extends Controller
{
    protected DuffelHotelService $duffelHotelService;

    public function __construct(DuffelHotelService $duffelHotelService)
    {
        $this->duffelHotelService = $duffelHotelService;
    }

    public function search(Request $request)
    {
        try {
            $request->validate([
                'keyword' => 'required|string|min:2',
            ]);

            $keyword = $request->input('keyword');
            $places  = $this->duffelHotelService->suggestPlaces($keyword);

            return response()->json([
                'success' => true,
                'data'    => $places,
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching places.',
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function listing(Request $request)
    {
        try {
            $request->validate([
                'latitude'   => 'required|numeric',
                'longitude'  => 'required|numeric',
                'check_in'   => 'required|date',
                'check_out'  => 'required|date|after:check_in',
                'adults'     => 'sometimes|integer|min:1',
                'children'   => 'sometimes|integer|min:0',
                'rooms'      => 'sometimes|integer|min:1',
                'radius_km'  => 'sometimes|integer|min:1',
                'page'       => 'sometimes|integer|min:1',
                'per_page'   => 'sometimes|integer|min:1|max:50',
            ]);

            $data = $request->only([
                'latitude', 'longitude', 'check_in', 'check_out',
                'adults', 'children', 'rooms', 'radius_km', 'page', 'per_page'
            ]);

            $result = $this->duffelHotelService->searchByLocation(
                $data['latitude'],
                $data['longitude'],
                $data['check_in'],
                $data['check_out'],
                $data['adults'] ?? 1,
                $data['children'] ?? 0,
                $data['rooms'] ?? 1,
                $data['radius_km'] ?? 10,
                $data['page'] ?? 1,
                $data['per_page'] ?? 20
            );

            if (!empty($result['error']) && $result['error']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'] ?? 'No results found.',
                ], config('constant.httpCode.BAD_REQUEST'));
            }

            return response()->json([
                'success'      => true,
                'data'         => $result['results'],
                'all_results'  => $result['all_results'],
                'pagination'   => [
                    'total'        => $result['total'],
                    'per_page'     => $result['per_page'],
                    'current_page' => $result['current_page'],
                    'total_pages'  => $result['total_pages'],
                    'has_more'     => $result['has_more'],
                ]
            ], config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->errors(),
            ], config('constant.httpCode.UNPROCESSABLE_ENTITY'));

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while fetching hotels.',
            ], config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}