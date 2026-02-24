<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Models\User;
use App\Traits\Toast;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    use Toast;

    public User $user;
    public $name;
    public $phone_number;

    public function mount()
    {
        $this->user = Auth::guard('admin')->user();
        $this->name = $this->user->name;
        $this->phone_number = $this->user->phone_number;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric',
        ]);

        $this->user->update([
            'name' => $this->name,
            'phone_number' => $this->phone_number,
        ]);

        $this->SessionToast('success', 'Update successful!');
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.backend.profile');
    }
}