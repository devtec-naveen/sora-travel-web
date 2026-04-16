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
                'country_code' => ['nullable', 'string', 'max:5'],
                'passport_id'  => ['nullable', 'string', 'max:20'],
                'profile_image'=> ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
            ]);

            $result = $this->commonAuthService->updateProfile([
                ...$request->only([
                    'name',
                    'email',
                    'phone_number',
                    'country_code',
                    'passport_id',
                ]),
                'profile_image' => $request->file('profile_image'),
            ]);

            if (!$result['status']) {
                return $this->error($result['message'], config('constant.httpCode.UNPROCESSABLE_ENTITY'));
            }

            $base = config('constant.image_base_url');
            $folder = 'user_profile/';

            $user = $result['user'];

            $user->profile_image = $user->profile_image ? $base . $folder . $user->profile_image : null;

            return $this->success('Profile updated successfully.',$user,config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}
