<?php

namespace App\Livewire\Backend\EmailTemplate;

use Livewire\Component;
use App\Services\Common\CmsService;

class View extends Component
{
    public $templateId;
    public $template;

    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->templateId = $id;
        $this->loadTemplate();
    }

    public function loadTemplate()
    {
        $this->template = $this->service->getEmailTemplateById($this->templateId);
    }

    public function render()
    {
        return view('livewire.backend.email-template.view', [
            'template' => $this->template
        ]);
    }
}