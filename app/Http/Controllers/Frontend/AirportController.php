<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Common\Duffel\AirportService;

class AirportController extends Controller
{
    protected $airportService;

    public function __construct(AirportService $airportService)
    {
        $this->airportService = $airportService;
    }

    public function index()
    {
        return view('flight.listing');
    }

    public function passengers()
    {
        return view('flight.passengers');
    }

    public function addon()
    {
        return view('flight.addons');
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $airports = $this->airportService->search($keyword);
        return response()->json($airports);
    }
}
