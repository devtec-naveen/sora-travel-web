<?php

namespace App\Livewire\Frontend\Flight;
use Livewire\Component;
use App\Services\Common\Amadeus\FlightService;

class Listing extends Component
{
    public $origin;
    public $destination;
    public $departureDate;
    public $adults = 1;
    public $page = 1;
    public $flights = [];
    public $total = 0;
    public $children = 0;  
    public $infants = 0;   
    public $cabinClass = 'Economy'; 

    protected $flightService;

    protected $queryString = ['origin','destination','departureDate','adults','children','infants','cabinClass','page'];

    public function mount(FlightService $flightService)
    {
        $this->flightService = $flightService;
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

        $limit = 20;
        $offset = ($this->page - 1) * $limit;
        $response = $this->flightService->searchFlights([
            'origin' => $this->origin,
            'destination' => $this->destination,
            'departureDate' => $this->departureDate,
            'adults' => $this->adults,
            'children' => $this->children,
            'infants' => $this->infants,
            'cabin' => strtoupper($this->cabinClass),
            'max' => $limit,
        ]);
        
        $this->flights = collect($response['data'] ?? []);;
        $this->total = $response['meta']['count'] ?? count($this->flights);
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