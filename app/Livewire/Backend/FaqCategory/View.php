<?php

namespace App\Livewire\Backend\FaqCategory;
use Livewire\Component;
use App\Services\Backend\CmsService;

class View extends Component
{
    public $faqId;
    public $faq;
    public $id;

    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service =  $service;
    }

    public function mount($id)
    {
        $this->faqId = $id;
        $this->loadFaq();
    }

    public function loadFaq()
    {
        $this->faq = $this->service->getFaqCategoryById($this->faqId);
    }

    public function render()
    {
        return view('livewire.backend.faq-category.view', [
            'faq' => $this->faq
        ]);
    }
}