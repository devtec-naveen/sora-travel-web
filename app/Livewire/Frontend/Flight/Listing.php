<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;

class Listing extends Component
{
    public $origin;
    public $destination;
    public $departureDate;
    public $adults = 1;
    public $children = 0;
    public $infants = 0;
    public $cabinClass = 'economy';
    public $page = 1;
    public $flights = [];
    public $total = 0;  
    public $returnDate;
    public $sortBy = '';

    protected $queryString = [
        'origin',
        'destination',
        'departureDate',
        'returnDate',
        'adults',
        'children',
        'infants',
        'cabinClass',
        'page'
    ];

    protected $duffelService;

    public function mount(DuffelService $duffelService)
    {
        $tripType = request('trip_type');
        $allowedTripTypes = config('constant.flight_trip_types');
        if (!$tripType || !in_array($tripType, $allowedTripTypes)) {
            return redirect()->route('home');
        }

        $this->duffelService = $duffelService;
        $this->loadFlights();
    }

    public function updatedSortBy($value)
    {
        $this->loadFlights();
    }

    public function updatedPage($value)
    {
        $this->page = $value;
        $this->loadFlights();
    }

    public function loadFlights()
    {
        if (!$this->origin || !$this->destination || !$this->departureDate) {
            $this->flights = [];
            return;
        }

        $duffelService = $this->duffelService ?? app(DuffelService::class);
        $requestData = [
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departureDate' => $this->departureDate,
            'returnDate' => $this->returnDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'cabin' => $this->cabinClass,
            'limit' => 20,
        ];


        if ($this->returnDate) {
            $requestData['returnDate'] = $this->returnDate;
        }

        $response = $duffelService->searchFlightsMain($requestData);

        $offers = $response['data']['offers'] ?? [];

        if ($this->sortBy) {
            $offers = $this->sortOffers($offers, $this->sortBy);
        }

        $this->flights = collect($offers);

        $this->total = count($offers);
    }

    public function render()
    {
        return view('livewire.frontend.flight.listing', [
            'flights' => $this->flights,
            'page' => $this->page,
            'limit' => 20,
            'total' => $this->total
        ]);
    }

    private function sortOffers(array $offers, string $sortBy): array
    {
        return match($sortBy) {
            'price_low_high' => collect($offers)->sortBy(fn($o) => $o['total_amount'] ?? 0)->values()->toArray(),
            'price_high_low' => collect($offers)->sortByDesc(fn($o) => $o['total_amount'] ?? 0)->values()->toArray(),
            'duration' => collect($offers)->sortBy(fn($o) => $o['slices'][0]['duration'] ?? 0)->values()->toArray(),
            default => $offers,
        };
    }
}
