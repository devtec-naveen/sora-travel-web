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

    protected $listeners = [
        'modal-opened' => 'handleOpen',
        'modal-closed' => 'handleClose',
    ];

    public array $data = [];

    protected array $rules = [
        'name'                  => 'required|string|min:2|max:100',
        'email'                 => 'required|email',
        'phone_number'          => 'nullable|string|min:7|max:15',
        'password'              => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
        'terms'                 => 'accepted',
    ];

    protected array $messages = [
        'name.required'      => 'Full name is required.',
        'email.required'     => 'Email is required.',
        'email.email'        => 'Enter a valid email address.',
        'password.required'  => 'Password is required.',
        'password.min'       => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Passwords do not match.',
        'terms.accepted'     => 'You must accept the Terms & Conditions.',
    ];

    public function mount($data = [])
    {
        $this->data = $data;
    }

    public function resetForm()
    {
        $this->reset([
            'name',
            'email',
            'phone_number',
            'password',
            'password_confirmation',
            'terms',
            'otp',
            'canResend',
        ]);

        $this->step = 'signup';
        $this->otp  = ['', '', '', '', '', ''];

        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function switchToLogin(): void
    {
        $this->dispatch('close-modal', id: 'signup_modal');
        $this->dispatch('open-modal', id: 'login_modal');
    }

    public function handleClose($id = null): void
    {
        if ($id === 'signup_modal') {
            $this->resetForm();
        }
    }

    public function register(AuthService $auth): void
    {
        $this->validate();

        $existingUser = $auth->findByEmail($this->email);

        if ($existingUser && $existingUser->status === 'active') {
            $this->addError('email', 'This email is already registered.');
            return;
        }

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

        $otpResult = $auth->verifyRegisterOtp($this->email, $entered, 'web');

        if (!$otpResult['status']) {
            $this->addError('otp', $otpResult['message']);
            return;
        }

        Auth::login($otpResult['user']);
        $this->resetForm();
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

    public function updated($field): void
    {
        if ($field === 'password') {
            $this->validateOnly('password', [
                'password' => 'required|min:8'
            ]);
            return;
        }

        if ($field === 'password_confirmation') {
            $this->validateOnly('password_confirmation');
            return;
        }

        $this->validateOnly($field);
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
            'score'      => $score,
            'label'      => $labels[$score] ?? '',
            'textColor'  => $colors[$score] ?? '',
            'barColor'   => $bars[$score] ?? '',
            'hints'      => $hints,
            'show'       => strlen($password) > 0,
        ];
    }

    public function getPasswordMatchProperty(): array
    {
        $match = $this->password === $this->password_confirmation 
                && strlen($this->password_confirmation) > 0;

        return [
            'show'  => strlen($this->password_confirmation) > 0,
            'match' => $match,
        ];
    }

    public function render()
    {
        return view('livewire.frontend.auth.register');
    }
}
