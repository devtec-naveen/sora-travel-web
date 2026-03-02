<?php

namespace App\Livewire\Backend\PopularDestination;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Backend\PopularDestinationService;
use App\Traits\Toast;

class Add extends Component
{
    use WithFileUploads, Toast;

    protected PopularDestinationService $service;

    public $title;
    public $image;

    public function boot(PopularDestinationService $service)
    {
        $this->service = $service;
    }

    public function resetForm()
    {
        $this->reset([
            'title',
            'image',
        ]);
    }

    public function saveDestination()
    {
        $this->validate([
            'title'  => 'required|string|max:255',
            'image'  => 'required|max:1024',
        ]);

        $data = [
            'title'  => $this->title,
            'image'  => $this->image,
        ];

        $saved = $this->service->create($data);

        if (isset($saved['title']) && $saved['title'] === 'already_used') {
            $this->addError('title', $saved['message']);
            return;
        }

        if ($saved) {
            $this->SessionToast('success', 'Popular Destination added successfully!');
            return $this->redirect(route('admin.destinationsList'), navigate: true);
        } else {
            $this->Toast('error', 'Something went wrong while saving Popular Destination!');
        }
    }

    public function render()
    {
        return view('livewire.backend.popular-destination.add');
    }
}
