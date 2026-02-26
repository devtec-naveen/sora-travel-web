<?php

namespace App\Livewire\Backend\Faq;

use App\Livewire\Backend\DataTable;
use App\Services\Backend\CmsService;
use App\Traits\Toast;

class Add extends DataTable
{
    use Toast;

    protected CmsService $service;

    public $faqs = [];
    public $faqCategoryList = [];

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount()
    {
        $this->faqCategoryList = $this->service->getfaqCategoryList();
        $this->faqs = [
            ['question' => '', 'answer' => '', 'status' => 'active']
        ];
    }

    public function addFaq()
    {
        $this->faqs[] = ['question' => '', 'answer' => '', 'status' => 'active'];
    }

    public function removeFaq($index)
    {
        unset($this->faqs[$index]);
        $this->faqs = array_values($this->faqs);
    }

    public function resetForm()
    {
        $this->faqs = [
            ['question' => '', 'answer' => '', 'status' => 'active']
        ];
    }

    public function saveFaqs()
    {
        $rules = [];
        $messages = [];

        foreach ($this->faqs as $i => $faq) {

            $rules["faqs.$i.faq_category_id"] = 'required';
            $rules["faqs.$i.question"] = 'required|string|max:100';
            $rules["faqs.$i.answer"]   = 'required|string|max:200';

            $messages["faqs.$i.faq_category_id.required"] = "Category Id field is required" . ($i + 1);
            $messages["faqs.$i.question.required"] = "Question field is required for row " . ($i + 1);
            $messages["faqs.$i.question.max"]      = "Question cannot exceed 100 characters for row " . ($i + 1);

            $messages["faqs.$i.answer.required"] = "Answer field is required for row " . ($i + 1);
            $messages["faqs.$i.answer.max"]      = "Answer cannot exceed 200 characters for row " . ($i + 1);
        }

        $this->validate($rules, $messages);

        $saved = $this->service->saveFaq($this->faqs);

        if ($saved) {
            $this->SessionToast('success', 'FAQs added successfully!');
            $this->redirect(route('admin.faqList'),navigate:true);
            $this->resetForm();
        } else {
            $this->Toast('error', 'Something went wrong while saving FAQs!');
        }
    }

    public function render()
    {
        return view('livewire.backend.faq.add', [
            'faqCategoryList' => $this->faqCategoryList
        ]);
    }
}
