<?php

namespace App\Livewire\Frontend\Flight;

use Livewire\Component;
use App\Services\Common\Duffel\DuffelService;
use Illuminate\Support\Facades\Auth;
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
    public int    $maxPrice       = 0;
    public $stops                 = [];
    public $airlines              = [];
    public bool   $refundableOnly = false;
    public array $availableAirlines = [];
    public int   $minPossiblePrice  = 0;
    public int   $maxPossiblePrice  = 9999999;
    public array $allOffers         = [];
    public bool $isLoading = false;
    public $offerRequestId = null;
    public $cursor = null;
    public $limit;
    private $allFlights = [];

    protected $queryString = [
        'origin',
        'destination',
        'departureDate',
        'returnDate',
        'adults',
        'childrens',
        'infants',
        'cabin_class',
        'page',
    ];

    protected $duffelService;

    public function mount(DuffelService $duffelService)
    {
        $this->limit = config('constant.duffel.offer_limit');
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

    public function closeModalFligtDetails(): void
    {
        $this->selectedFlight = [];
        $this->dispatch('close-modal', id: 'flight_details_modal');
    }

    public function loadFlights(): void
    {
        $this->isLoading = true;

        $duffelService = $this->duffelService ?? app(DuffelService::class);

        $requestData = [
            'origin'        => $this->origin,
            'destination'   => $this->destination,
            'departureDate' => $this->departureDate,
            'returnDate'    => $this->returnDate,
            'adults'        => $this->adults,
            'children'      => $this->childrens,
            'infants'       => $this->infants,
            'cabin'         => $this->cabin_class,
        ];

        $response = $duffelService->searchFlightsMain($requestData);

        $this->offerRequestId = $response['offer_request_id'] ?? null;

        $allOffers = $response['offers'] ?? [];
        $cursor    = $response['cursor'] ?? null;

        while ($cursor) {
            $next = $duffelService->getNextOffers($this->offerRequestId, $cursor);

            $allOffers = collect($allOffers)
                ->merge($next['offers'] ?? [])
                ->unique('id')
                ->values()
                ->toArray();

            $cursor = $next['cursor'] ?? null;
        }

        $this->allFlights = $allOffers;
        session(['allFlights' => $allOffers]);
        $this->total = count($this->allFlights);
        $this->availableAirlines = collect($this->allFlights)
            ->map(fn($o) => $o['slices'][0]['segments'][0]['operating_carrier']['name'] ?? null)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $prices = collect($this->allFlights)
            ->map(fn($o) => (float) ($o['total_amount'] ?? 0))
            ->filter();

        $this->minPossiblePrice = (int) ($prices->min() ?? 0);
        $this->maxPossiblePrice = (int) ($prices->max() ?? 0);

        if ($this->maxPrice === 0) {
            $this->maxPrice = $this->maxPossiblePrice;
        }

        $this->page = 1;

        $this->applyFilters();

        $this->isLoading = false;
    }

    public function applyFilters(): void
    {
        $duffelService = $this->duffelService ?? app(DuffelService::class);
        $allFlights = session('allFlights', []);
        $filters = [
            'stops'      => is_array($this->stops) ? $this->stops : [],
            'airlines'   => is_array($this->airlines) ? $this->airlines : [],
            'refundable' => $this->refundableOnly,
            'sort'       => $this->sortBy,
        ];

        if ($this->maxPrice && $this->maxPrice < $this->maxPossiblePrice) {
            $filters['max_price'] = $this->maxPrice;
        }

        $filtered = $duffelService->filterAndSort($allFlights, $filters);
        $this->total = count($filtered);
        $this->flights = collect($filtered)
            ->take($this->page * $this->limit)
            ->values()
            ->toArray();
    }

    public function cancelBooking($orderId)
    {
        $result = app(DuffelService::class)->cancelOrder([
            'order_id' => $orderId,
            'user_id'  => Auth::id(),
        ]);

        if (!$result['success']) {
            session()->flash('error', $result['message']);
            return;
        }

        session()->flash('success', 'Flight cancelled successfully');
    }

    public function loadMore()
    {
        if (count($this->flights) >= $this->total) {
            return;
        }
        $this->page++;
        $this->applyFilters();
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
