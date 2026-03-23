<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Api\AuthService;

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

    public function sendResetLink(AuthService $authService)
    {
        $this->validate();

        $response = $authService->forgotPassword([
            'email' => $this->email
        ]);

        if ($response['status']) {
            $this->dispatch('auth-success', message: $response['message']);
            $this->reset('email');
        } else {
            $this->addError('email', $response['message']);
        }
    }

    public function render()
    {
        return view('livewire.frontend.auth.forgot-password');
    }
}