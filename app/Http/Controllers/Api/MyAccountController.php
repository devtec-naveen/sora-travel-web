<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Api\AuthService;
use App\Services\Common\MyAccountService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MyAccountController extends Controller
{
    use ApiResponse;

    protected $authService;
    protected $myAccountService;

    public function __construct(AuthService $authService, MyAccountService $myAccountService)
    {
        $this->authService = $authService;
        $this->myAccountService = $myAccountService;
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

            $result = $this->myAccountService->updateProfile([
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

    public function getUser(Request $request)
    {
        try {
            $userId = Auth::id();
            $user = $this->myAccountService->getUserById($userId);

            if (!$user) {
                return $this->error(
                    'User not found',
                    config('constant.httpCode.NOT_FOUND')
                );
            }

            $base = config('constant.image_base_url');
            $folder = 'user_profile/';

            $user->profile_image = $user->profile_image ? $base . $folder . $user->profile_image : null;
            return $this->success('User fetched successfully',$user,config('constant.httpCode.SUCCESS_OK'));
        } catch (Exception $e) {
            return $this->error(
                $e->getMessage(),
                config('constant.httpCode.INTERNAL_SERVER_ERROR')
            );
        }
    }

    public function getNotificationSettings()
    {
        try {
            $settings = $this->myAccountService->getNotificationSettings();

            return $this->success('Notification settings fetched successfully.', $settings, config('constant.httpCode.SUCCESS_OK'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function updateNotificationSettings(Request $request)
    {
        try {
            $request->validate([
                'booking_updates' => ['required', 'boolean'],
                'promotions'      => ['required', 'boolean'],
                'payment_alerts'  => ['required', 'boolean'],
            ]);

            $data = $this->myAccountService->updateNotificationSettings([
                'booking_updates' => $request->boolean('booking_updates'),
                'promotions'      => $request->boolean('promotions'),
                'payment_alerts'  => $request->boolean('payment_alerts'),
            ]);

            return $this->success('Notification settings updated successfully.', $data , config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE ACCOUNT
    | DELETE /api/account
    | Body: { "password": "..." }
    |--------------------------------------------------------------------------
    */
    public function deleteAccount(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'password' => ['required', 'string'],
            ], [
                'password.required' => 'Password is required.',
            ]);

            $this->myAccountService->deleteAccount($request->input('password'));

            return $this->success('Account deleted successfully.', [], config('constant.httpCode.SUCCESS_OK'));

        } catch (\RuntimeException $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function getAddresses(): \Illuminate\Http\JsonResponse
    {
        try {
            $addresses = $this->myAccountService->getAddresses();

            return $this->success('Addresses fetched successfully.', $addresses, config('constant.httpCode.SUCCESS_OK'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function storeAddress(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'street_address' => ['required', 'string', 'max:255'],
                'city'           => ['required', 'string', 'max:100'],
                'postal_code'    => ['required', 'string', 'max:20'],
                'county'         => ['required', 'string', 'max:100'],
            ], [
                'street_address.required' => 'Street address is required.',
                'city.required'           => 'City is required.',
                'postal_code.required'    => 'Postal code is required.',
                'county.required'         => 'County / State is required.',
            ]);

            $this->myAccountService->createAddress($request->only([
                'street_address',
                'city',
                'postal_code',
                'county',
            ]));

            return $this->success('Address added successfully.', [], config('constant.httpCode.SUCCESS_CREATED'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function updateAddress(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'street_address' => ['required', 'string', 'max:255'],
                'city'           => ['required', 'string', 'max:100'],
                'postal_code'    => ['required', 'string', 'max:20'],
                'county'         => ['required', 'string', 'max:100'],
            ], [
                'street_address.required' => 'Street address is required.',
                'city.required'           => 'City is required.',
                'postal_code.required'    => 'Postal code is required.',
                'county.required'         => 'County / State is required.',
            ]);

            $updated = $this->myAccountService->updateAddress($id, $request->only([
                'street_address',
                'city',
                'postal_code',
                'county',
            ]));

            if (! $updated) {
                return $this->error('Address not found.', config('constant.httpCode.NOT_FOUND'));
            }

            return $this->success('Address updated successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (ValidationException $e) {
            return $this->error($e->errors(), config('constant.httpCode.UNPROCESSABLE_ENTITY'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }

    public function deleteAddress(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $deleted = $this->myAccountService->deleteAddress($id);

            if (! $deleted) {
                return $this->error('Address not found.', config('constant.httpCode.NOT_FOUND'));
            }

            return $this->success('Address deleted successfully.', [], config('constant.httpCode.SUCCESS_OK'));
        } catch (Exception $e) {
            return $this->error($e->getMessage(), config('constant.httpCode.INTERNAL_SERVER_ERROR'));
        }
    }
}
