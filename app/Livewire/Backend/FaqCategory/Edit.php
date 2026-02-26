<?php

namespace App\Livewire\Backend\FaqCategory;

use Livewire\Component;
use App\Services\Backend\CmsService;
use App\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public $categoryId;
    public $name;
    public $status;

    protected CmsService $service;

    public function boot(CmsService $service)
    {
        $this->service = $service;
    }

    public function mount($id)
    {
        $this->categoryId = $id;
        $this->loadCategory();
    }

    public function loadCategory()
    {
        $category = $this->service->getFaqCategoryById($this->categoryId);
        $this->name = $category->name;
        $this->status = $category->status;
    }

    public function updateCategory()
    {
        $this->validate([
            'name'   => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $this->service->updateFaqCategory($this->categoryId, [
            'name'   => $this->name,
            'status' => $this->status,
        ]);

        $this->SessionToast('success', 'FAQ Category updated successfully!');
        return redirect()->route('admin.faqCategoryList');
    }

    public function cancelEdit()
    {
        return redirect()->route('admin.faqCategoryList');
    }

    public function render()
    {
        return view('livewire.backend.faq-category.edit');
    }
}