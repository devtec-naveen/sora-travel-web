<?php

namespace App\Livewire\Backend\EmailTemplate;

use App\Livewire\Backend\DataTable;
use App\Services\Backend\CmsService;

class Listing extends DataTable
{
    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service = $service;
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
}