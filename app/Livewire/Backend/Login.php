<?php

namespace App\Livewire\Backend;

use Livewire\Component;
use App\Services\Backend\AdminAuthService;
use App\Traits\Toast;


class Login extends Component
{
    use Toast;

    public $email;
    public $password;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login(AdminAuthService $service)
    {
        $this->validate();
        try {
            $login = $service->login($this->email, $this->password);

            if (!$login) {
                $this->Toast('error', 'Invalid credentials!');
                return;
            }

            $this->SessionToast('success', 'Login successful!');
            return redirect()->route('admin.dashboard');
            
        } catch (\Exception $e) {
            $this->Toast('error', 'Something went wrong. Please try again later!');
            return;
        }
    }

    public function render()
    {
        return view('livewire.backend.login');
    }
}
