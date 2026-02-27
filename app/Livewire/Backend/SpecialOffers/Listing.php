<?php

namespace App\Livewire\Backend\SpecialOffers;

use App\Livewire\Backend\DataTable;
use App\Services\Backend\SpecialOffersService;
use App\Services\Common\ChangeStatusService;
use App\Services\Common\DeleteService;
use App\Traits\Toast;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['deleteConfirmed', 'changeStatus'];

    protected SpecialOffersService $service;
    protected DeleteService $deleteService;
    protected ChangeStatusService $statusService;

    public function boot(
        SpecialOffersService $service,
        DeleteService $deleteService,
        ChangeStatusService $statusService
    ) {
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

        $specialOffers = $this->service->getSpecialOfferList($filters);

        return view('livewire.backend.special-offers.listing', [
            'offerList' => $specialOffers
        ]);
    }

    public function deleteConfirmed($id)
    {
        $this->deleteService->deleteRecord(\App\Models\SpecialOffersModel::class, $id);
        $this->SessionToast('success', 'Special Offer deleted successfully!');
        $this->redirect(route('admin.specialOfferList'), navigate: true);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\SpecialOffersModel::class, $id);
        $this->SessionToast('success', 'Special Offer status updated successfully!');
        $this->redirect(route('admin.specialOfferList'), navigate: true);
    }
}