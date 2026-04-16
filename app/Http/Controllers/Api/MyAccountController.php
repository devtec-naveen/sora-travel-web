<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use App\Services\Common\Auth\AuthService as CommonAuthService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Validation\ValidationException;

class MyAccountController extends Controller
{
    use ApiResponse;

    protected $authService;
    protected $commonAuthService;

    public function __construct(AuthService $authService, CommonAuthService $commonAuthService)
    {
        $this->authService = $authService;
        $this->commonAuthService = $commonAuthService;
    }

    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'name'         => ['required', 'string', 'max:255'],
                'email'        => ['required', 'email', 'max:255'],
                'phone_number' => ['nullable', 'string', 'max:13'],
                'passport_id'  => ['nullable', 'string', 'max:20'],
            ]);

            $result = $this->commonAuthService->updateProfile($request->only([
                'name',
                'email',
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
