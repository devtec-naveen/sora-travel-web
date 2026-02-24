<?php

namespace App\Livewire\Backend\Users;
use App\Livewire\Backend\DataTable;
use App\Models\User;


class Listing extends DataTable
{    
    public function render()
    {
        $users = User::query()
            ->where('role',1)
            ->when($this->search, function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.backend.users.listing', [
            'users' => $users
        ]);
    }
}
