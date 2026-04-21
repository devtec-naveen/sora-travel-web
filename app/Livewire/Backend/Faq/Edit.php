<?php

namespace App\Livewire\Backend\Faq;

use Livewire\Component;
use App\Services\Common\CmsService;
use App\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public $faqId;
    public $faq;

    public $faqCategoryList = [];

    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->faqId = $id;
        $this->loadFaq();
        $this->loadCategories();
    }

    public function loadFaq()
    {
        $this->faq = $this->service->getFaqById($this->faqId)->toArray();
        $this->faq['faq_category_id'] = $this->faq['c_id'];
    }

    public function loadCategories()
    {
        $this->faqCategoryList = $this->service->getFaqCategoryList(); 
    }

    public function updateFaq()
    {
        $this->validate([
            'faq.faq_category_id' => 'required',
            'faq.question'        => 'required|string',
            'faq.answer'          => 'required|string',
            'faq.status'          => 'required|in:active,inactive',
        ]);

        $this->service->updateFaq($this->faqId, $this->faq);
        $this->SessionToast('success', 'FAQ updated successfully!');
        $this->redirect(route('admin.faqList'),navigate:true);
    }

    public function cancelEdit()
    {
        $this->redirect(route('admin.faqList'),navigate:true);
    }

    public function render()
    {
        return view('livewire.backend.faq.edit', [
            'faq' => $this->faq,
        ]);
    }
}