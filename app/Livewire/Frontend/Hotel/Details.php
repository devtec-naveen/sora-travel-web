<?php

namespace App\Livewire\Frontend\Hotel;

use Livewire\Component;
use App\Services\Common\Duffel\Hotel\DuffelHotelService;
use Illuminate\Support\Facades\Session;

class Details extends Component
{
    public string $accommodationId;     
    public array $hotel = [];          
    public array $searchParams = [];    

    protected DuffelHotelService $hotelService;

    public function mount(DuffelHotelService $hotelService, string $accommodationId)
    {
        $this->hotelService      = $hotelService;
        $this->accommodationId   = $accommodationId;
        $this->searchParams = Session::get('hotel_search_params', []);
        $this->fetchHotelDetails();
    }

    protected function fetchHotelDetails(): void
    {
        $response = $this->hotelService->getAccommodationDetail($this->accommodationId);

        // dd($response);

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