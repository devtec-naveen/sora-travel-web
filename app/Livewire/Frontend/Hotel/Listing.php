<?php

namespace App\Livewire\Frontend\Hotel;

use Livewire\Component;
use App\Services\Common\Duffel\Hotel\DuffelHotelService;
use Illuminate\Support\Collection;

class Listing extends Component
{
    public ?string $city      = null;
    public ?string $latitude  = null;
    public ?string $longitude = null;
    public ?string $checkIn   = null;
    public ?string $checkOut  = null;
    public int $adults        = 1;
    public int $children      = 0;
    public int $rooms         = 1;
    public int $radius        = 10;
    public string $sortBy     = '';
    public int $page          = 1;
    public int $perPage       = 20;
    public int $total       = 0;
    public int $totalPages  = 0;
    public array $hotels      = [];

    protected $queryString = [
        'city',
        'latitude',
        'longitude',
        'checkIn'  => ['as' => 'check_in'],
        'checkOut' => ['as' => 'check_out'],
        'adults',
        'children',
        'rooms',
        'radius',
    ];

    public function boot(DuffelHotelService $hotelService): void
    {
        $this->hotelService = $hotelService;
    }

    public function mount(): void
    {
        $this->city      = request('city');
        $this->latitude  = request('latitude');
        $this->longitude = request('longitude');
        $this->checkIn   = request('check_in');
        $this->checkOut  = request('check_out');
        $this->adults    = (int) request('adults', 1);
        $this->children  = (int) request('children', 0);
        $this->rooms     = (int) request('rooms', 1);

        $this->loadHotels();
    }

    public function loadHotels(): void
    {
        $response = $this->hotelService->searchByLocation(
            (float) $this->latitude,
            (float) $this->longitude,
            $this->checkIn,
            $this->checkOut,
            $this->adults,
            $this->children,
            $this->rooms,
            $this->radius,
            $this->page,
            $this->perPage,
        );

        if ($response['error']) {
            $this->total      = 0;
            $this->totalPages = 0;
            return;
        }

        session(['hotel_all_results' => $response['all_results']]);
        $this->hotels     = $response['results'];
        $this->total      = $response['total'];
        $this->totalPages = $response['total_pages'];
        $this->hotels = $this->getPaginatedHotels();
    }

    public function setPage(int $page): void
    {
        $this->page = $page;
        $this->hotels = $this->getPaginatedHotels();
    }

    public function updatedSortBy(): void
    {
        $this->page = 1;
        $this->hotels = $this->getPaginatedHotels();
    }

    private function getPaginatedHotels(): array
    {
        $all = collect(session('hotel_all_results', []));

        $sorted = match($this->sortBy) {
            'price_low'   => $all->sortBy(fn($h)    => (float) ($h['cheapest_rate_public_amount'] ?? 0)),
            'price_high'  => $all->sortByDesc(fn($h) => (float) ($h['cheapest_rate_public_amount'] ?? 0)),
            'rating_high' => $all->sortByDesc(fn($h) => (int)   ($h['accommodation']['rating'] ?? 0)),
            default       => $all,
        };

        return $sorted
            ->slice(($this->page - 1) * $this->perPage, $this->perPage)
            ->values()
            ->all();
    }

    public function render()
    {
        return view('livewire.frontend.hotel.listing', [
            'hotels'      => $this->hotels,
            'total'       => $this->total,
            'totalPages'  => $this->totalPages,
            'currentPage' => $this->page,
            'perPage'     => $this->perPage,
        ]);
    }
}