<?php

namespace App\Livewire\Backend\Users;

use Livewire\Component;
use App\Models\User;

class View extends Component
{
    public $userId;
    public $user;

    public function mount($id)
    {
        $this->userId = $id;
        $this->user = User::find($id);
    }

    public function render()
    {
        return view('livewire.backend.users.view');
    }
}