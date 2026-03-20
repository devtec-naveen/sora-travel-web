<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Frontend\AuthService;

class Register extends Component
{
    public string $name                  = '';
    public string $email                 = '';
    public string $phone_number          = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public bool   $terms                 = false;

    protected array $rules = [
        'name'                  => 'required|string|min:2|max:100',
        'email'                 => 'required|email|unique:users,email',
        'phone_number'          => 'nullable|string|min:7|max:15',
        'password'              => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
        'terms'                 => 'accepted',
    ];

    protected array $messages = [
        'name.required'      => 'Full name is required.',
        'email.required'     => 'Email is required.',
        'email.unique'       => 'This email is already registered.',
        'password.required'  => 'Password is required.',
        'password.min'       => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Passwords do not match.',
        'terms.accepted'     => 'You must accept the Terms & Conditions.',
    ];

    public function register(AuthService $auth): void
    {
        $this->validate();

        $auth->register([
            'name'         => $this->name,
            'email'        => $this->email,
            'password'     => $this->password,
            'phone_number' => $this->phone_number ?: null,
            'tc'           => $this->terms,
        ]);

        $this->dispatch('auth-success');
    }

    public function render()
    {
        return view('livewire.frontend.auth.register');
    }
}