<?php

namespace App\Livewire\Backend\SpecialOffers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Backend\SpecialOffersService;
use App\Traits\Toast;

class Add extends Component
{
    use WithFileUploads, Toast;

    protected SpecialOffersService $service;

    public $title;
    public $start_date_time;
    public $end_date_time;
    public $status;
    public $image;

    public function boot(SpecialOffersService $service)
    {
        $this->service = $service;
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'start_date_time',
            'end_date_time',
            'image'
        ]);
    }

    public function saveOffer()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'start_date_time' => 'required|date',
            'end_date_time' => 'required|date|after:start_date_time',
            'image' => 'required|image|max:2048',
            'status' => 'required|boolean'
        ]);

        $saved = $this->service->create(request());

        if ($saved) {
            $this->SessionToast('success', 'Special Offer added successfully!');
            $this->redirect(route('admin.specialOfferList'), navigate: true);
        } else {
            $this->Toast('error', 'Something went wrong while saving Special Offer!');
        }
    }

    public function render()
    {
        return view('livewire.backend.special-offers.add');
    }
}