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

    protected $listeners = [
        'modal-opened' => 'handleOpen',
        'modal-closed' => 'handleClose',
    ];    

    protected array $rules = [
        'email'                 => 'required|email',
        'password'              => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ];

    protected array $messages = [
        'email.required'                 => 'Email is required.',
        'email.email'                    => 'Enter a valid email address.',
        'password.required'              => 'Password is required.',
        'password.min'                   => 'Password must be at least 8 characters.',
        'password.confirmed'             => 'Passwords do not match.',
        'password_confirmation.required' => 'Please confirm your password.',
    ];

    public function resetForm(): void
    {
        $this->reset([
            'email',
            'password',
            'password_confirmation',
            'otp',
            'step',
        ]);

        $this->step = 'email';
        $this->otp  = ['', '', '', '', '', ''];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function handleClose($id = null): void
    {
        if ($id === 'forgot_password_modal') {
            $this->resetForm();
        }
    }

    public function backToEmail(): void
    {
        $this->step = 'email';
        $this->otp  = ['', '', '', '', '', ''];
        $this->resetErrorBag();
    }

    public function switchToLogin(): void
    {
        $this->dispatch('close-modal', id: 'forgot_password_modal');
        $this->dispatch('open-modal', id: 'login_modal');
    }

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

        Auth::login($result['user']);
        $this->resetForm();
        $this->step = 'email';
        $this->dispatch('auth-success', message: $result['message']);
    }

    public function getPasswordStrengthProperty(): array
    {
        $password = $this->password;
        $score    = 0;
        $hints    = [
            'uppercase' => (bool) preg_match('/[A-Z]/', $password),
            'number'    => (bool) preg_match('/[0-9]/', $password),
            'special'   => (bool) preg_match('/[^A-Za-z0-9]/', $password),
            'length'    => strlen($password) >= 8,
        ];

        foreach ($hints as $passed) {
            if ($passed) $score++;
        }

        if (strlen($password) >= 12) $score++;

        $labels = ['', 'Very Weak', 'Weak', 'Fair', 'Strong', 'Very Strong'];
        $colors = ['', 'text-red-500', 'text-orange-500', 'text-yellow-500', 'text-blue-500', 'text-green-500'];
        $bars   = ['', 'bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-blue-500', 'bg-green-500'];

        return [
            'score'     => $score,
            'label'     => $labels[$score] ?? '',
            'textColor' => $colors[$score] ?? '',
            'barColor'  => $bars[$score] ?? '',
            'hints'     => $hints,
            'show'      => strlen($password) > 0,
        ];
    }

    public function getPasswordMatchProperty(): array
    {
        return [
            'show'  => strlen($this->password_confirmation) > 0,
            'match' => $this->password === $this->password_confirmation
                    && strlen($this->password_confirmation) > 0,
        ];
    }

    public function updated($field): void
    {
        if (in_array($field, ['password', 'password_confirmation'])) {
            $this->validateOnly('password');
        }
    }

    public function render()
    {
        return view('livewire.frontend.auth.forgot-password');
    }
}