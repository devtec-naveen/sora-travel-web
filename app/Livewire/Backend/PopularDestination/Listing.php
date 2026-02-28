<?php

namespace App\Livewire\Backend\PopularDestination;

use App\Livewire\Backend\DataTable;
use App\Services\Backend\PopularDestinationService;
use App\Services\Common\ChangeStatusService;
use App\Services\Common\DeleteService;
use App\Traits\Toast;
use App\Models\PopularDestinationModel;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['deleteConfirmed', 'changeStatus'];

    protected PopularDestinationService $service;
    protected DeleteService $deleteService;
    protected ChangeStatusService $statusService;

    public function boot(PopularDestinationService $service,DeleteService $deleteService,ChangeStatusService $statusService)
    {
        $this->service = $service;
        $this->deleteService = $deleteService;
        $this->statusService = $statusService;
    }

    public function render()
    {
        sleep(1);
        $filters = [
            'search'        => $this->search,
            'sortField'     => $this->sortField,
            'sortDirection' => $this->sortDirection,
            'perPage'       => $this->perPage,
        ];

        $destinations = $this->service->getPopularDestinationList($filters);
        return view('livewire.backend.popular-destination.listing', [
            'destinationList' => $destinations
        ]);
    }

    public function deleteConfirmed($id)
    {
        $this->deleteService->deleteRecordWithFile(\App\Models\PopularDestinationModel::class,$id,'image','popular_destination');
        $this->SessionToast('success', 'Destination deleted successfully!');
        $this->redirect(route('admin.destinationsList'), navigate: true);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\PopularDestinationModel::class,$id);
        $this->SessionToast('success', 'Destination status updated successfully!');
        $this->redirect(route('admin.destinationsList'), navigate: true);
    }
}