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

    public function index()
    {
        return view('hotel.listing');
    }

    public function suggest(Request $request)
    {
        $request->validate(['keyword' => 'required|string|max:255']);

        $places = $this->hotelService->suggestPlaces($request->input('keyword'));
        $countryNames = [
            'IN' => 'India', 'US' => 'United States', 'GB' => 'United Kingdom',
            'AE' => 'UAE', 'TH' => 'Thailand', 'SG' => 'Singapore',
        ];

        $data['data'] = collect($places)
            ->filter(fn($place) => !empty($place['iata_city_code']) && !empty($place['city_name']))
            ->unique('iata_city_code')
            ->map(fn($place) => [
                'code'      => $place['iata_city_code'],
                'city'      => $place['city_name'],
                'name'      => 'Hotels in ' . $place['city_name'],
                'country'   => $countryNames[$place['iata_country_code'] ?? ''] ?? ($place['iata_country_code'] ?? ''),
                'latitude'  => $place['latitude'] ?? null,
                'longitude' => $place['longitude'] ?? null,
            ])
            ->values()
            ->all();

        return response()->json($data);
    }
 
    // public function search(Request $request)
    // {
    //     $request->validate([
    //         'latitude'  => 'required|numeric',
    //         'longitude' => 'required|numeric',
    //         'check_in'  => 'required|date',
    //         'check_out' => 'required|date|after:check_in',
    //         'adults'    => 'integer|min:1',
    //         'children'  => 'integer|min:0',
    //         'rooms'     => 'integer|min:1',
    //     ]);

    //     $results = $this->hotelService->searchByLocation(
    //         (float)$request->input('latitude'),
    //         (float)$request->input('longitude'),
    //         $request->input('check_in'),
    //         $request->input('check_out'),
    //         $request->input('adults', 1),
    //         $request->input('children', 0),
    //         $request->input('rooms', 1),
    //         $request->input('radius', 10)
    //     );

    //     return response()->json($results);
    // }
}