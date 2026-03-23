<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;

class Listing extends Component
{
    public $origin;
    public $destination;
    public $departureDate;
    public $returnDate;
    public $adults               = 1;
    public $childrens             = 0;
    public $infants              = 0;
    public $cabin_class           = 'economy';
    public $page                 = 1;

    public $flights              = [];
    public $total                = 0;
    public array $selectedFlight = [];

    public string $sortBy         = '';
    public int    $maxPrice       = 9999999;
    public $stops                 = [];
    public $airlines              = [];
    public bool   $refundableOnly = false;

    public array $availableAirlines = [];
    public int   $minPossiblePrice  = 0;
    public int   $maxPossiblePrice  = 9999999;
    public array $allOffers         = [];

    public bool $isLoading = false;

    protected $queryString = [
        'origin', 'destination', 'departureDate', 'returnDate',
        'adults', 'childrens', 'infants', 'cabin_class', 'page',
    ];

    protected $duffelService;

    public function mount(DuffelService $duffelService)
    {
        $this->duffelService = $duffelService;
        session()->forget([
            'passenger_info',
            'addons_info',
            'seats_info',
            'booking_info',
        ]);
    }

    public function updatedSortBy()
    {
        $this->isLoading = true;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function updatedMaxPrice()
    {
        $this->isLoading = true;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function updatedStops()
    {
        $this->isLoading = true;
        $this->stops = is_array($this->stops) ? $this->stops : [];
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function updatedAirlines()
    {
        $this->isLoading = true;
        $this->airlines = is_array($this->airlines) ? $this->airlines : [];
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function updatedRefundableOnly()
    {
        $this->isLoading = true;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function updatedPage()
    {
        $this->isLoading = true;
        $this->loadFlights();
        $this->isLoading = false;
    }

    public function removeStop(int $stop): void
    {
        $this->stops = array_values(array_filter(
            is_array($this->stops) ? $this->stops : [],
            fn($s) => (int) $s !== $stop
        ));
        $this->isLoading = true;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function removeAirline(string $airline): void
    {
        $this->airlines = array_values(array_filter(
            is_array($this->airlines) ? $this->airlines : [],
            fn($a) => $a !== $airline
        ));
        $this->isLoading = true;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function clearFilters(): void
    {
        $this->isLoading      = true;
        $this->sortBy         = '';
        $this->maxPrice       = $this->maxPossiblePrice;
        $this->stops          = [];
        $this->airlines       = [];
        $this->refundableOnly = false;
        $this->applyFilters();
        $this->isLoading = false;
    }

    public function selectFlight(int $index): void
    {
        $this->selectedFlight = $this->flights[$index] ?? [];
        $this->dispatch('open-modal', id: 'flight_details_modal');
    }

    public function proceedToPassengers(): void
    {
        session([
            'selected_flight' => [
                'flight'     => $this->selectedFlight,
                'adults'     => $this->adults,
                'children'   => $this->childrens,
                'infants'    => $this->infants,
                'cabinClass' => $this->cabin_class,
            ],
        ]);

        $this->redirect(route('airport.passengers'));
    }

    public function closeModal(): void
    {
        $this->selectedFlight = [];
        $this->dispatch('close-modal', id: 'flight_details_modal');
    }

    public function loadFlights(): void
    {
        $this->isLoading = true;

        $duffelService = $this->duffelService ?? app(DuffelService::class);
        $tripType      = request('trip_type');

        $requestData = [
            'adults'   => $this->adults,
            'children' => $this->childrens,
            'infants'  => $this->infants,
            'cabin'    => $this->cabin_class,
            'limit'    => 50,
        ];

        if ($tripType === 'multicity') {
            $origins      = request('origin', []);
            $destinations = request('destination', []);
            $dates        = request('departure_date', []);
            $trips        = [];
            foreach ($origins as $i => $origin) {
                $trips[] = [
                    'origin'        => $origin,
                    'destination'   => $destinations[$i] ?? null,
                    'departureDate' => $dates[$i] ?? null,
                ];
            }
            $requestData['trips'] = $trips;
        } else {
            $requestData['origin']        = $this->origin;
            $requestData['destination']   = $this->destination;
            $requestData['departureDate'] = $this->departureDate;
            if ($this->returnDate) {
                $requestData['returnDate'] = $this->returnDate;
            }
        }

        $response = $duffelService->searchFlightsMain($requestData);
        $offers   = $response['data']['offers'] ?? [];

        $this->availableAirlines = collect($offers)
            ->map(fn($o) => $o['slices'][0]['segments'][0]['operating_carrier']['name'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $prices = collect($offers)
            ->map(fn($o) => (float) ($o['total_amount'] ?? 0))
            ->filter();

        $this->minPossiblePrice = (int) ($prices->min() ?? 0);
        $this->maxPossiblePrice = (int) ($prices->max() ?? 9999999);

        if ($this->maxPrice === 9999999) {
            $this->maxPrice = $this->maxPossiblePrice;
        }

        $this->allOffers = $offers;
        $this->applyFilters();

        $this->isLoading = false;
    }

    public function applyFilters(): void
    {
        $duffelService = $this->duffelService ?? app(DuffelService::class); 
        $this->flights = $duffelService->filterAndSort($this->allOffers, [
            'max_price'  => $this->maxPrice,
            'stops'      => is_array($this->stops)   ? $this->stops   : [],
            'airlines'   => is_array($this->airlines) ? $this->airlines : [],
            'refundable' => $this->refundableOnly,
            'sort'       => $this->sortBy,
        ]);
 
        $this->total = count($this->flights);
    }
 

    public function render()
    {
        return view('livewire.frontend.flight.listing', [
            'flights'           => $this->flights,
            'total'             => $this->total,
            'selectedFlight'    => $this->selectedFlight,
            'availableAirlines' => $this->availableAirlines,
            'minPossiblePrice'  => $this->minPossiblePrice,
            'maxPossiblePrice'  => $this->maxPossiblePrice,
        ]);
    }
}