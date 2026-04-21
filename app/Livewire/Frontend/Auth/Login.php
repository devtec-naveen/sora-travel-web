<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Common\Auth\AuthService;
use App\Traits\Toast;


class Login extends Component
{
    use Toast;

    public string $email      = '';
    public string $password   = '';
    public bool   $rememberMe = false;

    protected array $rules = [
        'email'      => 'required|email',
        'password'   => 'required|min:6',
        'rememberMe' => 'boolean',
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

    public function resetForm(): void
    {
        $this->reset(['email', 'password', 'rememberMe']);
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

    public function login(AuthService $auth)
    {
        $this->validate();

        $result = $auth->login([
            'email'       => $this->email,
            'password'    => $this->password,
            'guard'       => 'web',
            'remember'    => $this->rememberMe,
        ]);

        if (!$result['status']) {
            $this->addError('email', $result['message']);
            return;
        }

        $this->resetForm();
        $this->Toast('success', 'Login successfully!');
        $this->dispatch('auth-success',redirect: false);
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