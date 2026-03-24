<?php

namespace App\Livewire\Frontend\Auth;

use Livewire\Component;
use App\Services\Common\Auth\AuthService;
use Illuminate\Support\Facades\Auth;


class Register extends Component
{
    public string $step = 'signup';

    public string $name                  = '';
    public string $email                 = '';
    public string $phone_number          = '';
    public string $password              = '';
    public string $password_confirmation = '';
    public bool   $terms                 = false;

    public array $otp       = ['', '', '', '', '', ''];
    public bool  $canResend = false;

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

        $result = $auth->sendRegisterOtp([
            'name'         => $this->name,
            'email'        => $this->email,
            'password'     => $this->password,
            'tc'           => $this->terms,
        ]);

        if (!$result['status']) {
            $this->addError('email', $result['message']);
            return;
        }

        $this->step      = 'otp';
        $this->canResend = false;

        $this->dispatch('otp-sent');
    }

    public function verifyOtp(AuthService $auth): void
    {
        $entered = implode('', $this->otp);

        if (strlen($entered) < 6) {
            $this->addError('otp', 'Please enter the complete 6-digit OTP.');
            return;
        }

        $otpResult = $auth->verifyRegisterOtp($this->email, $entered,'web');

        if (!$otpResult['status']) {
            $this->addError('otp', $otpResult['message']);
            return;
        }

        Auth::login($otpResult['user']);
        $this->dispatch('auth-success');
    }

    public function resendOtp(AuthService $auth): void
    {
        if (!$this->canResend) {
            return;
        }

        $result = $auth->sendRegisterOtp([
            'name'         => $this->name,
            'email'        => $this->email,
            'password'     => $this->password, 
            'phone_number' => $this->phone_number,
            'tc'           => $this->terms,
        ]);

        if (!$result['status']) {
            $this->addError('otp', $result['message']);
            return;
        }

        $this->otp       = ['', '', '', '', '', ''];
        $this->canResend = false;

        $this->dispatch('otp-sent');
    }

    public function backToSignup(): void
    {
        $this->step = 'signup';
        $this->otp  = ['', '', '', '', '', ''];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.frontend.auth.register');
    }
}