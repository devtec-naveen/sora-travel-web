<?php

namespace App\Services\Api;

use App\Jobs\SendEmail;
use App\Repositories\Api\AuthRepository;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthService
{
    protected $authRepo;

    public function __construct(AuthRepository $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function register(array $data)
    {
        DB::beginTransaction();
        try {
            $userCreate = $this->authRepo->create($data);
            $token = $userCreate->createToken('auth_token')->plainTextToken;
            DB::commit();
            return [
                'user' => [
                    'name'  => $userCreate->name,
                    'email' => $userCreate->email,
                ],
                'token' => $token,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function login(array $data)
    {
        try {
            $user = $this->authRepo->findByEmailAndRole($data['email'], config('constant.roleText.user'));
            if (!$user || !Hash::check($data['password'], $user->password)) {
                throw new Exception('Invalid credentials');
            }
            $token = $user->createToken('auth_token')->plainTextToken;
            return [
                'user' => $user->only(['name', 'email', 'role']),
                'token' => $token,
            ];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function logout($user)
    {
        try {
            if (!$user) {
                throw new Exception('User not authenticated');
            }
            $user->currentAccessToken()->delete();
            return true;
        } catch (Exception $e) {
            throw $e;
        }
    }


    public function forgotPassword(array $data)
    {
        try {

            //===================== Check User Email =================
            $user = $this->authRepo->findByEmailAndRole($data['email'], config('constant.roleText.user'));
            if (!$user) {
                return [
                    'status' => false,
                    'message' => 'Invalid email address.'
                ];
            }

            //===================== Check Email Template =================
            $emailTemplate = $this->authRepo->findBySlugEmailTemplate('forgot-password');
            if (!$emailTemplate) {
                return [
                    'status' => false,
                    'message' => 'Email template not found.'
                ];
            }

            $plainToken = Str::random(64);
            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                [
                    'token' => Hash::make($plainToken),
                    'created_at' => now()
                ]
            );
            $resetUrl = config('app.url') . '/reset-password?token=' . $plainToken . '&email=' . urlencode($user->email);
            $expireMinutes = config('mail.expire_time', 60);
            $replaceVaribale =    [
                'name' => $user->name,
                'reset_url' => $resetUrl,
                'app_name' => config('app.name'),
                'expire_time' => $expireMinutes,
            ];

            //===================== Send Email Process =================
            SendEmail::dispatch(
                $user->email,
                $emailTemplate->subject,
                $emailTemplate->body,
                $replaceVaribale
            );

            return [
                'status' => true,
                'message' => 'Password reset link sent successfully.'
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
