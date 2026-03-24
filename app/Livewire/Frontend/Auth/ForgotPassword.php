<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Common\Auth\AuthService;

class ForgotPassword extends Component
{
    public string $email = '';

    protected array $rules = [
        'email' => 'required|email',
    ];

    protected array $messages = [
        'email.required' => 'Email is required.',
        'email.email'    => 'Enter a valid email.',
    ];

    public function sendResetLink(AuthService $authService): void
    {
        $this->validate();

        $result = $authService->forgotPassword([
            'email' => $this->email,
        ]);

        if (!$result['status']) {
            $this->addError('email', $result['message']);
            return;
        }

        $this->reset('email');
        $this->dispatch('auth-success', message: $result['message']);
    }

    public function render()
    {
        return view('livewire.frontend.auth.forgot-password');
    }
}