<?php

namespace App\Livewire\Backend\Users;

use App\Livewire\Backend\DataTable;
use App\Services\Common\ChangeStatusService;
use App\Models\User;
use App\Traits\Toast;

class Listing extends DataTable
{
    use Toast;

    protected $listeners = ['changeStatus'];

    protected ChangeStatusService $statusService;

    public function boot(ChangeStatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    public function render()
    {
        $users = User::query()
            ->where('role', 1)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.users.listing', [
            'users' => $users
        ]);
    }

    public function changeStatus($id)
    {
        $this->statusService->toggleStatus(\App\Models\User::class, $id);
        $this->SessionToast('success', 'Status updated successfully!');
        $this->redirect(route('admin.users'), navigate: true);
    }
}
