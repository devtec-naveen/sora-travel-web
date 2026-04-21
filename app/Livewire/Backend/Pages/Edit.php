<?php

namespace App\Livewire\Backend\Pages;

use Livewire\Component;
use App\Services\Common\CmsService;
use App\Traits\Toast;

class Edit extends Component
{
    use Toast;

    public $pageId;

    public $page_title;
    public $slug;
    public $meta_title;
    public $meta_keywords;
    public $content;
    public $status;

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
        $page = $this->service->getPagesById($this->pageId);

        $this->page_title    = $page->page_title;
        $this->slug          = $page->slug;
        $this->meta_title    = $page->meta_title;
        $this->meta_keywords = $page->meta_keywords;
        $this->content       = $page->content;
        $this->status        = $page->status;
    }

    public function updatePage()
    {
        $this->validate([
            'page_title' => 'required|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string|max:255',
            'content'    => 'nullable|string',
            'status'     => 'required|in:active,inactive',
        ]);

        $this->service->updatePage($this->pageId, [
            'page_title'    => $this->page_title,
            'meta_title'    => $this->meta_title,
            'meta_keywords' => $this->meta_keywords,
            'content'       => $this->content,
            'status'        => $this->status,
        ]);

        $this->SessionToast('success', 'Page updated successfully!');
        $this->redirect(route('admin.pagesList'), navigate: true);
    }

    public function cancelEdit()
    {
        $this->redirect(route('admin.pagesList'), navigate: true);
    }

    public function render()
    {
        return view('livewire.backend.pages.edit');
    }
}