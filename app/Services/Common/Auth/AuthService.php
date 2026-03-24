<?php

namespace App\Services\Common\Auth;

use App\Repositories\Common\Auth\AuthRepository;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendEmail;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class AuthService
{
    public function __construct(protected AuthRepository $authRepo) {}

    public function sendRegisterOtp(array $data): array
    {
        try {
            $existingUser = $this->authRepo->findByEmail($data['email']);

            if ($existingUser && $existingUser->status === 'active') {
                return [
                    'status'  => false,
                    'message' => 'This email is already registered.',
                ];
            }

            $emailTemplate = $this->authRepo->findEmailTemplate('register-otp');

            if (!$emailTemplate) {
                return [
                    'status'  => false,
                    'message' => 'Email template not found.',
                ];
            }

            $user       = $this->authRepo->createOrUpdatePendingUser($data);
            $expireTime = config('mail.otp_expire_time', 10);

            SendEmail::dispatch(
                $user->email,
                $emailTemplate->subject,
                $emailTemplate->body,
                [
                    'name'        => $user->name,
                    'otp_code'    => $user->otp,
                    'app_name'    => config('app.name'),
                    'expire_time' => $expireTime,
                ]
            );

            return [
                'status'  => true,
                'message' => 'OTP sent successfully.',
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function verifyRegisterOtp(string $email, string $enteredOtp, string $guard = 'web'): array
    {
        $user = $this->authRepo->findByEmail($email);

        if (!$user) {
            Log::warning('OTP verification failed - user not found', ['email' => $email]);
            return [
                'status'  => false,
                'message' => 'Invalid request.',
            ];
        }

        if ($user->status === 'active') {
            Log::warning('OTP verification failed - user already active', [
                'email'  => $email,
                'userId' => $user->id,
            ]);
            return [
                'status'  => false,
                'message' => 'Invalid request.',
            ];
        }

        if (!$user->otp || !$user->otp_expires_at) {
            Log::warning('OTP verification failed - OTP not found in DB', [
                'email'  => $email,
                'userId' => $user->id,
            ]);
            return [
                'status'  => false,
                'message' => 'OTP not found. Please request a new one.',
            ];
        }

        if (now()->isAfter($user->otp_expires_at)) {
            Log::warning('OTP verification failed - OTP expired', [
                'email'          => $email,
                'userId'         => $user->id,
                'otp_expires_at' => $user->otp_expires_at,
            ]);
            return [
                'status'  => false,
                'message' => 'OTP has expired. Please request a new one.',
            ];
        }

        if ($enteredOtp !== $user->otp) {
            Log::warning('OTP verification failed - invalid OTP entered', [
                'email'  => $email,
                'userId' => $user->id,
            ]);
            return [
                'status'  => false,
                'message' => 'Invalid OTP. Please try again.',
            ];
        }

        DB::beginTransaction();
        try {
            $user = $this->authRepo->activateUser($user);
            DB::commit();

            Log::info('OTP verification successful - user activated', [
                'email'  => $email,
                'userId' => $user->id,
                'guard'  => $guard,
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('OTP activation failed - DB rollback', [
                'email'   => $email,
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
            throw $e;
        }

        if ($guard === 'api') {
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'status'  => true,
                'message' => 'Registration successful.',
                'user'    => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
                'token'   => $token,
            ];
        }

        Auth::login($user);
        request()->session()->regenerate();

        return [
            'status'  => true,
            'message' => 'Registration successful.',
            'user'    => $user,
            'token'   => null,
        ];
    }

    public function login(array $data): array
    {
        try {
            $user = $this->authRepo->findByEmail($data['email']);

            if (!$user || !\Illuminate\Support\Facades\Hash::check($data['password'], $user->password)) {
                return [
                    'status'  => false,
                    'message' => 'Invalid email or password.',
                ];
            }

            if ($user->status !== 'active') {
                return [
                    'status'  => false,
                    'message' => 'Your account is not active. Please verify your email.',
                ];
            }

            if (($data['guard'] ?? 'web') === 'api') {
                $token = $user->createToken('auth_token')->plainTextToken;
                return [
                    'status'  => true,
                    'message' => 'Login successful.',
                    'user'    => $user,
                    'token'   => $token,
                ];
            }

            Auth::login($user);
            request()->session()->regenerate();

            return [
                'status'  => true,
                'message' => 'Login successful.',
                'user'    => $user,
                'token'   => null,
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function forgotPassword(array $data): array
    {
        try {
            $user = $this->authRepo->findByEmailAndRole(
                $data['email'],
                config('constant.roleText.user')
            );

            if (!$user) {
                return [
                    'status'  => false,
                    'message' => 'Invalid email address.',
                ];
            }

            $emailTemplate = $this->authRepo->findEmailTemplate('forgot-password');

            if (!$emailTemplate) {
                return [
                    'status'  => false,
                    'message' => 'Email template not found.',
                ];
            }

            $plainToken    = $this->authRepo->upsertPasswordResetToken($user->email);
            $expireMinutes = config('mail.expire_time', 60);
            $resetUrl      = config('app.url') . '/reset-password?token=' . $plainToken . '&email=' . urlencode($user->email);

            SendEmail::dispatch(
                $user->email,
                $emailTemplate->subject,
                $emailTemplate->body,
                [
                    'name'        => $user->name,
                    'reset_url'   => $resetUrl,
                    'app_name'    => config('app.name'),
                    'expire_time' => $expireMinutes,
                ]
            );

            return [
                'status'  => true,
                'message' => 'Password reset link sent successfully.',
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function logout(string $guard = 'web'): array
    {
        try {
            if ($guard === 'api') {
                request()->user()->currentAccessToken()->delete();
                return [
                    'status'  => true,
                    'message' => 'Logged out successfully.',
                ];
            }

            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return [
                'status'  => true,
                'message' => 'Logged out successfully.',
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
