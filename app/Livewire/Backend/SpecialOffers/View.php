<?php

namespace App\Livewire\Backend\SpecialOffers;
use Livewire\Component;
use App\Services\Backend\SpecialOffersService;

class View extends Component
{
    public $offerId;
    public $offer;

    protected SpecialOffersService $service;

    public function boot(SpecialOffersService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->offerId = $id;
        $this->loadOffer();
    }

    public function loadOffer()
    {
        $this->offer = $this->service->find($this->offerId);
    }

    public function render()
    {
        return view('livewire.backend.special-offers.view', [
            'offer' => $this->offer
        ]);
    }
}