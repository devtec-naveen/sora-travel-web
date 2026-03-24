<?php

namespace App\Services\Api;
use App\Repositories\Api\AuthRepository;
use App\Services\Common\Auth\AuthService as CommonAuthService;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    protected $authRepo;
    protected $commonAuth;

    public function __construct(AuthRepository $authRepo, CommonAuthService $commonAuth)
    {
        $this->authRepo   = $authRepo;
        $this->commonAuth = $commonAuth;
    }

    public function sendRegisterOtp(array $data): array
    {
        return $this->commonAuth->sendRegisterOtp($data);
    }

    public function verifyAndRegister(array $data)
    {
        $otpResult = $this->commonAuth->verifyRegisterOtp($data['email'], $data['otp'], 'api');
        return $otpResult;
    }

    public function login(array $data): array
    {
        try {
            $user = $this->authRepo->findByEmailAndRole($data['email'], config('constant.roleText.user'));

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return [
                    'status'  => false,
                    'message' => 'Invalid credentials.',
                ];
            }

            if ($user->status !== 'active') {
                return [
                    'status'  => false,
                    'message' => 'Your account is not active.',
                ];
            }

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'status'  => true,
                'message' => 'Login successful.',
                'user'    => $user->only(['name', 'email', 'role']),
                'token'   => $token,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function logout($user): array
    {
        try {
            if (!$user) {
                return [
                    'status'  => false,
                    'message' => 'User not authenticated.',
                ];
            }

            $user->currentAccessToken()->delete();

            return [
                'status'  => true,
                'message' => 'Logged out successfully.',
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function forgotPassword(array $data): array
    {
        return $this->commonAuth->forgotPassword($data);
    }

    public function verifyRegisterOtp(string $email, string $enteredOtp): array
    {
        return $this->commonAuth->verifyRegisterOtp($email, $enteredOtp);
    }
}
