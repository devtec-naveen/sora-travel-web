<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Traits\Toast;

class ChangePassword extends Component
{
    use Toast;

    public $old_password;
    public $password;
    public $password_confirmation;

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->rules());
    }

    protected function rules()
    {
        return [
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function changePassword()
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::guard('admin')->user();        

        if (!$user) {
            $this->addError('old_password', 'Session expired. Please login again.');
            return;
        }

        if (!Hash::check($this->old_password, $user->password)) {
            $this->addError('old_password', 'Old password is incorrect.');
            return;
        }

        if (Hash::check($this->password, $user->password)) {
            $this->addError('password', 'New password cannot be the same as old password.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->SessionToast('success', 'Password updated successfully!');
        $this->reset(['old_password', 'password', 'password_confirmation']);

        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.backend.change-password');
    }
}