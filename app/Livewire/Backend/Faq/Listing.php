<?php

namespace App\Livewire\Backend\Faq;

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

        $faqList = $this->service->getfaqList($filters);

        return view('livewire.backend.faq.listing', [
            'faqList' => $faqList
        ]);
    }
}