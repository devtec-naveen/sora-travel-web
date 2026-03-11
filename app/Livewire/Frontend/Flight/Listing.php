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

    protected $queryString = [
        'origin',
        'destination',
        'departureDate',
        'adults',
        'children',
        'infants',
        'cabinClass',
        'page'
    ];

    protected DuffelService $duffelService;

    public function mount(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
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

        $response = $this->duffelService->searchFlights([
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departureDate' => $this->departureDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'cabin' => $this->cabinClass,
            'limit' => 20,
        ]);
        $offers = $response['data']['offers'] ?? [];
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
}
