<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    use ApiResponse;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|same:confirm_password',
                'confirm_password' => 'required|string|min:6',
                'tc' => 'required|accepted'
            ], [
                'tc.required' => 'You must accept the Terms & Conditions.',
                'tc.accepted' => 'You must accept the Terms & Conditions.',
            ]);

            $result = $this->authService->register($request->all());
            return $this->success('User registered successfully', $result, config('constant.httpCode.SUCCESS_CREATED'));
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
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $result = $this->authService->login($request->all());
            return $this->success('Login successfully', $result, config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout($request->user());
            return $this->success('Logged out successfully', [], config('constant.httpCode.SUCCESS_OK'));
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

            $result = $this->authService->forgotPassword($request->all());
            return $this->success('If your email exists, a password reset link has been sent.', $result, config('constant.httpCode.SUCCESS_OK'));

        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}
