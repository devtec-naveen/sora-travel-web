<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\Common\Duffel\Hotel\DuffelHotelService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    protected DuffelHotelService $hotelService;

    public function __construct(DuffelHotelService $hotelService)
    {
        $this->hotelService = $hotelService;
    }

    public function index(Request $request)
    {
        $searchParams = $request->only([
            'city',
            'latitude',
            'longitude',
            'check_in',
            'check_out',
            'rooms',
            'adults',
            'children'
        ]);
        session(['hotel_search_params' => $searchParams]);
        return view('hotel.listing');
    }

    public function details(string $hotelId)
    {
        return view('hotel.details', ['id' => $hotelId]);
    }

    public function suggest(Request $request)
    {
        $request->validate(['keyword' => 'required|string|max:255']);
        return response()->json([
            'data' => $this->hotelService->suggestPlaces($request->input('keyword'))
        ]);
    }
}
