<?php

namespace App\Livewire\Backend\EmailTemplate;

use App\Livewire\Backend\DataTable;
use App\Services\Common\CmsService;
use App\Services\Common\ChangeStatusService;
use App\Traits\Toast;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['changeStatus'];

    protected CmsService $service;
    protected ChangeStatusService $statusService;

    public function boot(CmsService $service,ChangeStatusService $statusService)
    {
        $this->service = $service;
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

        $emailTemplateList = $this->service->getEmailTemplateList($filters);

        return view('livewire.backend.email-template.listing', [
            'emailTemplateList' => $emailTemplateList
        ]);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\EmailTemplateModel::class,$id);
        $this->SessionToast('success', 'Status updated successfully!');
        $this->redirect(route('admin.emailTemplate'),navigate:true);
    }
}