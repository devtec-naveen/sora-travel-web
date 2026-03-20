<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Frontend\AuthService;

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

    public function login(AuthService $auth): void
    {
        $this->validate();

        if (! $auth->login(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Invalid email or password.');
            return;
        }

        $this->dispatch('auth-success');
    }

    public function render()
    {
        return view('livewire.frontend.auth.login');
    }
}