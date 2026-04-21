<?php

namespace App\Livewire\Backend\Faq;

use App\Livewire\Backend\DataTable;
use App\Services\Common\CmsService;
use App\Services\Common\ChangeStatusService;
use App\Services\Common\DeleteService;
use App\Traits\Toast;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['changeStatus','deleteConfirmed'];

    protected CmsService $service;
    protected ChangeStatusService $statusService;
    protected DeleteService $deleteService;

    public function boot(CmsService $service,ChangeStatusService $statusService,DeleteService $deleteService)
    {
        $this->service = $service;
        $this->statusService = $statusService;
        $this->deleteService = $deleteService;
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

        $faqList = $this->service->getfaqList($filters);

        return view('livewire.backend.faq.listing', [
            'faqList' => $faqList
        ]);
    }

    public function deleteConfirmed($id)
    {
        $this->deleteService->deleteRecord(\App\Models\FaqModel::class,$id);
        $this->SessionToast('success', 'FAQ deleted successfully!');
        $this->redirect(route('admin.faqList'),navigate:true);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\FaqModel::class,$id);
        $this->SessionToast('success', 'Status updated successfully!');
        $this->redirect(route('admin.faqList'),navigate:true);
    }
}