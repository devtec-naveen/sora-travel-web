<?php

namespace App\Livewire\Backend\SpecialOffers;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Backend\SpecialOffersService;
use App\Traits\Toast;
use Illuminate\Http\Request;

class Edit extends Component
{
    use WithFileUploads, Toast;

    public $offerId;

    public $title;
    public $start_date;
    public $end_date;
    public $status;
    public $image;

    public $oldImage;

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
        $offer = $this->service->find($this->offerId);

        if (!$offer) {
            abort(404);
        }

        $this->title           = $offer->title;
        $this->start_date = $offer->start_date;
        $this->end_date   = $offer->end_date;
        $this->status          = $offer->status;
        $this->oldImage        = $offer->image;
    }

    public function updateOffer()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        $request = new Request([
            'title' => $this->title,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
        ]);

        if ($this->image) {
            $request->files->set('image', $this->image);
        }

        $this->service->update($this->offerId, $request);

        $this->SessionToast('success', 'Special Offer updated successfully!');
        $this->redirect(route('admin.offersList'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->redirect(route('admin.offersList'), navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.special-offers.edit');
    }
}