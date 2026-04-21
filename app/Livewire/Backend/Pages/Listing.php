<?php

namespace App\Livewire\Backend\Pages;

use App\Livewire\Backend\DataTable;
use App\Services\Common\CmsService;
use App\Services\Common\ChangeStatusService;
use App\Services\Common\DeleteService;
use App\Traits\Toast;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['deleteConfirmed','changeStatus'];

    protected CmsService $service;
    protected DeleteService $deleteService;
    protected ChangeStatusService $statusService;

    public function boot(CmsService $service, DeleteService $deleteService,ChangeStatusService $statusService)
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

        $pageList = $this->service->getpageList($filters);

        return view('livewire.backend.pages.listing', [
            'pageList' => $pageList
        ]);
    }

    public function deleteConfirmed($id)
    {
        $this->deleteService->deleteRecord(\App\Models\PagesModel::class,$id);
        $this->SessionToast('success', 'Page deleted successfully!');
        $this->redirect(route('admin.faqCategoryList'),navigate:true);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\PagesModel::class,$id);
        $this->SessionToast('success', 'Status updated successfully!');
        $this->redirect(route('admin.pagesList'),navigate:true);
    }

}
