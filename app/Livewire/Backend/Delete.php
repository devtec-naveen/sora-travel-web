<?php

namespace App\Livewire;

use Livewire\Component;
use App\Services\Common\DeleteService;
use App\Traits\Toast;

class Delete extends Component
{
    use Toast;

    public $model;
    public $deleteId;

    protected DeleteService $deleteService;

    public function boot(DeleteService $deleteService)
    {
        $this->deleteService = $deleteService;
    }

    protected $listeners = ['confirmDelete'];

    public function confirmDelete($id, $model)
    {
        $this->deleteId = $id;
        $this->model = $model;

        $this->dispatch('show-delete-popup');
    }

    public function delete()
    {
        $response = $this->deleteService->delete($this->model, $this->deleteId);

        if ($response['status']) {
            $this->Toast('success',$response['message']);
        } else {
            $this->Toast('error',$response['message']);
        }

        $this->dispatch('refresh-page');
    }

    public function render()
    {
        return view('livewire.common.delete');
    }
}