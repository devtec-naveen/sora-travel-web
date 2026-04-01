<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Common\Auth\AuthService;

class Login extends Component
{
    public string $email    = '';
    public string $password = '';

    protected array $rules = [
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ];

    protected array $messages = [
        'email.required'    => 'Email is required.',
        'email.email'       => 'Enter a valid email address.',
        'password.required' => 'Password is required.',
        'password.min'      => 'Password must be at least 6 characters.',
    ];

    protected $listeners = [
        'modal-opened' => 'handleOpen',
        'modal-closed' => 'handleClose',
    ];

    public function resetForm()
    {
        $this->reset(['email', 'password']); 
        $this->resetErrorBag();              
        $this->resetValidation();            
    }

    public function handleClose($id = null): void
    {
        if ($id === 'login_modal') {
            $this->resetForm();
        }
    }

    public function switchToForgot(): void
    {
        $this->dispatch('close-modal', id: 'login_modal');
        $this->dispatch('open-modal', id: 'forgot_password_modal');
    }

    public function switchToSignup(): void
    {
        $this->dispatch('close-modal', id: 'login_modal');
        $this->dispatch('open-modal', id: 'signup_modal');
    }

    public function login(AuthService $auth): void
    {
        $this->validate();

        $result = $auth->login([
            'email'    => $this->email,
            'password' => $this->password,
            'guard'    => 'web',
        ]);

        if (!$result['status']) {
            $this->addError('email', $result['message']);
            return;
        }

        $this->resetForm();
        $this->dispatch('auth-success');
    }

    public function updated($field): void
    {
        $this->validateOnly($field);
    }

    public function render()
    {
        return view('livewire.frontend.auth.login');
    }
}