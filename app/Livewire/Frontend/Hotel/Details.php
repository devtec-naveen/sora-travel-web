<?php

namespace App\Livewire\Frontend\Hotel;

use Livewire\Component;
use App\Services\Common\Duffel\Hotel\DuffelHotelService;
use Illuminate\Support\Facades\Session;

class Details extends Component
{
    public string $accommodationId = '';
    public array $hotel = [];
    public array $searchParams = [];
    public string $price = '';
    public string $currency = '';
    public string $searchResultId = '';

    protected DuffelHotelService $hotelService;

    public function mount(DuffelHotelService $hotelService, string $accommodationId)
    {
        $this->hotelService    = $hotelService;
        $this->accommodationId = $accommodationId;
        $this->searchParams    = Session::get('hotel_search_params', []);

        $allResults = collect(Session::get('hotel_all_results', []));
        $match      = $allResults->firstWhere('accommodation.id', $accommodationId);

        $this->price          = $match['cheapest_rate_total_amount'] ?? '';
        $this->currency       = $match['cheapest_rate_base_currency'] ?? 'USD';
        $this->searchResultId = $match['id'] ?? '';

        $this->fetchAndMergeHotelData();
    }

    protected function fetchAndMergeHotelData(): void
    {
        $response = $this->hotelService->getHotelWithRooms(
            accommodationId: $this->accommodationId,
            searchResultId:  $this->searchResultId,
        );

        if ($response['error'] ?? false) {
            $this->hotel = [];
            session()->flash('error', $response['message'] ?? 'Unable to fetch hotel details.');
            return;
        }

        $this->hotel = $response['data'] ?? [];
    }

    public function render()
    {
        return view('livewire.frontend.hotel.details', [
            'hotel'        => $this->hotel,
            'searchParams' => $this->searchParams,
        ]);
    }
}