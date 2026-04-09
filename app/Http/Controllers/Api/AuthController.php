<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use App\Services\Common\Auth\AuthService as CommonAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;
    protected $commonAuthService;

    public function __construct(AuthService $authService,CommonAuthService $commonAuthService)
    {
        $this->authService = $authService;
        $this->commonAuthService = $commonAuthService;
    }

    public function sendRegisterOtp(Request $request)
    {
        try {
            $request->validate([
                'name'             => 'required|string|min:2|max:100',
                'email'            => 'required|email',
                'password'         => 'required|min:8|same:confirm_password',
                'confirm_password' => 'required',
                'phone_number'     => 'nullable|string|min:7|max:15',
                'tc'               => 'accepted',
            ], [
                'tc.accepted' => 'You must accept the Terms & Conditions.',
            ]);

            $result = $this->authService->sendRegisterOtp([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => $request->password,
                'phone_number' => $request->phone_number,
                'tc'           => $request->tc,
            ]);

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success('OTP sent successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function verifyAndRegister(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|string|size:6',
            ], [
                'otp.size' => 'OTP must be exactly 6 digits.',
            ]);

            $result = $this->authService->verifyAndRegister([
                'email' => $request->email,
                'otp'   => $request->otp,
            ]);

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success(
                'User registered successfully.',
                $result,
                config('constant.httpCode.SUCCESS_CREATED')
            );
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($request->only(['email', 'password']));

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNAUTHORIZED'));
            }

            return $this->success('Login successfully.', $result, config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function logout(Request $request)
    {
        try {
            $result = $this->authService->logout($request->user());
            return $this->success('Logged out successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.UNAUTHORIZED'));
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
            ]);

            $check = $this->authService->findByEmail($request->input('email'));

            if (!$check['status']) {
                return $this->error($check['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->authService->forgotPassword($request->only(['email']));

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $result = $this->authService->forgotPassword($request->only(['email']));

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success('OTP sent successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function verifyForgotOtp(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'otp'   => 'required|string|size:6',
            ], [
                'otp.size' => 'OTP must be exactly 6 digits.',
            ]);

            $result = $this->authService->verifyForgotOtp([
                'email' => $request->email,
                'otp'   => $request->otp,
            ]);

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success('OTP verified successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function resetPasswordWithOtp(Request $request)
    {
        try {
            $request->validate([
                'email'            => 'required|email',
                'otp'              => 'required|string|size:6',
                'password'         => 'required|min:8|same:confirm_password',
                'confirm_password' => 'required',
            ], [
                'otp.size'        => 'OTP must be exactly 6 digits.',
                'password.same'   => 'Passwords do not match.',
            ]);

            $result = $this->authService->resetPasswordWithOtp([
                'email'    => $request->email,
                'otp'      => $request->otp,
                'password' => $request->password,
            ]);

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success('Password reset successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name'         => ['required', 'string', 'max:255'],
                'phone_number' => ['nullable', 'string', 'max:13'],
                'passport_id'  => ['nullable', 'string', 'max:20'],
            ]);

            $result = $this->commonAuthService->updateProfile($request->only([
                'name',
                'phone_number',
                'passport_id',
            ]));

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            return $this->success('Profile updated successfully.', $result['user'], config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}
