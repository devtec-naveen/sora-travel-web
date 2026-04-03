<?php

namespace App\View\Components\frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Arr;
use Carbon\Carbon;

class FlightSearchTabs extends Component
{
    public string $tripType;
    public string $originValue;
    public string $originCity;
    public string $destinationValue;
    public string $destinationCity;
    public string $departureDate;
    public string $returnDate;
    public int    $totalRows;
    public array  $origins;
    public array  $destinations;
    public array  $originCities;
    public array  $depCities;
    public array  $depDates;

    public function __construct()
    {
        $hasRequest = request()->anyFilled([
            'trip_type', 'origin', 'destination', 'departureDate', 'departure_date'
        ]);

        if ($hasRequest) {
            $data = [
                'tripType'      => request('trip_type', 'oneway'),
                'origins'       => Arr::wrap(request('origin', [])),
                'destinations'  => Arr::wrap(request('destination', [])),
                'originCities'  => Arr::wrap(request('origin_city', [])),
                'depCities'     => Arr::wrap(request('departure_city', [])),
                'depDates'      => Arr::wrap(request('departure_date', [])),
                'departureDate' => request('departureDate', now()->format('Y-m-d')),
                'returnDate'    => request('returnDate', Carbon::now()->addDay()->format('Y-m-d')),
            ];
            session(['flight_search_tabs' => $data]);
        } else {
            $data = session('flight_search_tabs', [
                'tripType'      => 'oneway',
                'origins'       => [],
                'destinations'  => [],
                'originCities'  => [],
                'depCities'     => [],
                'depDates'      => [],
                'departureDate' => now()->format('Y-m-d'),
                'returnDate'    => '',
            ]);
        }

        $this->tripType         = $data['tripType'];
        $this->origins          = $data['origins'];
        $this->destinations     = $data['destinations'];
        $this->originCities     = $data['originCities'];
        $this->depCities        = $data['depCities'];
        $this->depDates         = $data['depDates'];
        $this->departureDate    = $data['departureDate'];
        $this->returnDate       = $data['returnDate'];
        $this->originValue      = $this->origins[0]      ?? 'JAI';
        $this->originCity       = $this->originCities[0] ?? 'Jaipur';
        $this->destinationValue = $this->destinations[0] ?? 'BLR';
        $this->destinationCity  = $this->depCities[0]    ?? 'Bangalore';
        $this->totalRows        = max(count($this->origins), count($this->destinations), 2);
    }

    public function render(): View|Closure|string
    {
        return view('components.frontend.flight-search-tabs');
    }
}