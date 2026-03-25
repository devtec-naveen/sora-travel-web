<?php

namespace App\Livewire\Frontend\Auth;
use Livewire\Component;
use App\Services\Common\Auth\AuthService;
use Illuminate\Support\Facades\Auth;

class ForgotPassword extends Component
{
    public string $email                 = '';
    public array  $otp                   = ['', '', '', '', '', ''];
    public string $password              = '';
    public string $password_confirmation = '';

    public string $step = 'email';

    public function sendOtp(AuthService $authService): void
    {
        $this->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Email is required.',
            'email.email'    => 'Enter a valid email.',
        ]);

        $result = $authService->forgotPassword([
            'email' => $this->email,
        ]);

        if (!$result['status']) {
            $this->addError('email', $result['message']);
            return;
        }

        $this->step = 'otp';
    }

    public function verifyOtp(AuthService $authService): void
    {
        $entered = implode('', $this->otp);

        if (strlen($entered) < 6) {
            $this->addError('otp', 'Please enter the complete 6-digit OTP.');
            return;
        }

        $result = $authService->verifyForgotOtp([
            'email' => $this->email,
            'otp'   => $entered,
        ]);

        if (!$result['status']) {
            $this->addError('otp', $result['message']);
            return;
        }

        $this->step = 'password';
    }

    public function resendOtp(AuthService $authService): void
    {
        $result = $authService->forgotPassword([
            'email' => $this->email,
        ]);

        if (!$result['status']) {
            $this->addError('otp', $result['message']);
            return;
        }

        $this->otp = ['', '', '', '', '', ''];
        $this->dispatch('otp-sent');
    }

    public function resetPassword(AuthService $authService): void
    {
        $this->validate([
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.required'              => 'Password is required.',
            'password.min'                   => 'Password must be at least 8 characters.',
            'password.confirmed'             => 'Passwords do not match.',
            'password_confirmation.required' => 'Please confirm your password.',
        ]);

        $entered = implode('', $this->otp);

        $result = $authService->resetPasswordWithOtp([
            'email'    => $this->email,
            'otp'      => $entered,
            'password' => $this->password,
        ]);

        if (!$result['status']) {
            $this->addError('password', $result['message']);
            return;
        }

        dd($result);
        Auth::login($result['user']);
        $this->reset();
        $this->step = 'email';
        $this->dispatch('auth-success', message: $result['message']);
    }

    public function render()
    {
        return view('livewire.frontend.auth.forgot-password');
    }
}