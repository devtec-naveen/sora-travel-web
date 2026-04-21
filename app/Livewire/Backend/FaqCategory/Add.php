<?php

namespace App\Livewire\Backend\FaqCategory;

use App\Livewire\Backend\DataTable;
use App\Services\Common\CmsService;
use App\Traits\Toast;

class Add extends DataTable
{
    use Toast;

    protected CmsService $service;

    public $name = '';

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:100'
        ];
    }

    protected function messages()
    {
        return [
            'name.required' => 'Category name is required.',
            'name.max'      => 'Category name cannot exceed 100 characters.'
        ];
    }

    public function resetForm()
    {
        $this->reset('name');
    }

    public function saveCategory()
    {
        $this->validate();

        $saved = $this->service->saveFaqCategory([
            'name' => $this->name
        ]);

        if ($saved) {
            $this->SessionToast('success', 'FAQ Category added successfully!');
            $this->redirect(route('admin.faqCategoryList'),navigate:true);
        } else {
            $this->Toast('error', 'Something went wrong while saving FAQ Category!');
        }
    }

    public function render()
    {
        return view('livewire.backend.faq-category.add');
    }
}