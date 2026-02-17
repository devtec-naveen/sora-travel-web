<?php

namespace App\Livewire\Admin;
use Livewire\Component;
use App\Services\AdminAuthService;
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

        $login = $service->login($this->email, $this->password);
        
        if (!$login) {
            $this->Toast('error', 'Invalid credentials!');
            return;
        }

        $this->SessionToast('success', 'Login successful!');         
        return redirect()->route('admin.dashboard');
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
