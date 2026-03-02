<?php

namespace App\Livewire\Backend\PopularDestination;
use Livewire\Component;
use App\Services\Backend\PopularDestinationService;

class View extends Component
{
    public $destinationId;
    public $destination;

    protected PopularDestinationService $service;

    public function boot(PopularDestinationService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->destinationId = $id;
        $this->loadDestination();
    }

    public function loadDestination()
    {
        $this->destination = $this->service
            ->getDestinationById($this->destinationId);
    }

    public function render()
    {
        return view('livewire.backend.popular-destination.view', [
            'destination' => $this->destination
        ]);
    }
}