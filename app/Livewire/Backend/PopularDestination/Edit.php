<?php

namespace App\Livewire\Backend\PopularDestination;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Services\Backend\PopularDestinationService;
use App\Traits\Toast;
use Illuminate\Http\Request;


class Edit extends Component
{
    use WithFileUploads, Toast;

    public $destinationId;
    public $title;
    public $image;
    public $oldImage;

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
        $destination = $this->service->getDestinationById($this->destinationId);

        if (!$destination) {
            abort(404);
        }

        $this->title    = $destination->title;
        $this->oldImage = $destination->image;
    }

    public function updateDestination()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $request = new Request([
            'title' => $this->title,
        ]);

        if ($this->image) {
            $request->files->set('image', $this->image);
        }

        $updated = $this->service->update($this->destinationId, $request);

        if (!$updated) {
            $this->SessionToast('error', 'Something went wrong!');
            return;
        }

        $this->SessionToast('success', 'Destination updated successfully!');
        $this->redirect(route('admin.destinationsList'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->redirect(route('admin.destinationsList'), navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.popular-destination.edit');
    }
}
