<?php

namespace App\Livewire\Backend\Pages;

use Livewire\Component;
use App\Services\Backend\CmsService;

class View extends Component
{
    public $pageId;
    public $page;

    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->pageId = $id;
        $this->loadPage();
    }

    public function loadPage()
    {
        $this->page = $this->service->getPagesById($this->pageId);
    }

    public function render()
    {
        return view('livewire.backend.pages.view', [
            'page' => $this->page
        ]);
    }
}