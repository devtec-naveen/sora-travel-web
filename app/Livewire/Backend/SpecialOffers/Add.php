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
    public $start_date;
    public $end_date;
    public $image;

    public function boot(SpecialOffersService $service)
    {
        $this->service = $service;
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'start_date',
            'end_date',
            'image'
        ]);
    }

    public function saveOffer()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'required|image|max:2048',
        ]);
            
        $data = [
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'image' => $this->image,
        ];

        $saved = $this->service->create($data);
        if ($saved) {
            $this->SessionToast('success', 'Special Offer added successfully!');
            $this->redirect(route('admin.offersList'), navigate: true);
        } else {
            $this->Toast('error', 'Something went wrong while saving Special Offer!');
        }
    }

    public function render()
    {
        return view('livewire.backend.special-offers.add');
    }
}
