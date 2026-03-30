<?php

namespace App\Services\Frontend;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function register(array $data): User
    {
        $user = User::create([
            'name'         => $data['name'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'phone_number' => $data['phone_number'] ?? null,
            'tc'           => $data['tc'] ?? false,
        ]);

        Auth::login($user);

        request()->session()->regenerate();

        return $user;
    }
    
    public function login(array $data): bool
    {
        if (! Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return false;
        }

        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return false;
        }

        request()->session()->regenerate();

        return true;
    }

    public function logout(): void
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}
