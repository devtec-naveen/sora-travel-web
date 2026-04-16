<?php

namespace App\Services\Common;

use App\Repositories\Common\MyAccountRepository;
use Illuminate\Support\Facades\Auth;
use App\Services\Common\FileService;
use App\Repositories\Common\Auth\AuthRepository;
use Exception;


class MyAccountService
{
    public function __construct(
        protected MyAccountRepository $repository,
        protected FileService $fileService,
        protected AuthRepository $authRepo
    ) {}

    public function getUserById(int $userId)
    {
        return $this->repository->getUserById($userId);
    }

    public function updatePersonalInfo(array $data): bool
    {
        return $this->repository->updatePersonalInfo(Auth::id(), $data);
    }

    public function getNotificationSettings(): array
    {
        $record = $this->repository->getNotificationSettings(Auth::id());

        $settings = $record?->settings ?? [];

        return [
            'booking_updates' => (bool) ($settings['booking_updates'] ?? false),
            'promotions'      => (bool) ($settings['promotions']      ?? false),
            'payment_alerts'  => (bool) ($settings['payment_alerts']  ?? false),
        ];
    }

    public function updateNotificationSettings(array $settings)
    {
        $data = $this->repository->updateNotificationSettings(Auth::id(), [
            'booking_updates' => $settings['booking_updates'] ?? false,
            'promotions'      => $settings['promotions']      ?? false,
            'payment_alerts'  => $settings['payment_alerts']  ?? false,
        ]);

        return $data;
    }

    public function updateProfile(array $data): array
    {
        try {
            $user = request()->user();

            if (!$user) {
                return [
                    'status'  => false,
                    'message' => 'Unauthorized.',
                ];
            }

            $updateData = [
                'name'         => $data['name'] ?? $user->name,
                'email'        => $data['email'] ?? $user->email,
                'phone_number' => $data['phone_number'] ?? $user->phone_number,
                'country_code' => $data['country_code'] ?? $user->country_code,
                'passport_id'  => $data['passport_id'] ?? $user->passport_id,
            ];

            if (!empty($data['profile_image'])) {

                if ($user->profile_image) {
                    $this->fileService->remove('user_profile/' . $user->profile_image);
                }

                $fileName = $this->fileService->upload(
                    $data['profile_image'],
                    'user_profile',
                    'user'
                );

                if ($fileName) {
                    $updateData['profile_image'] = $fileName;
                }
            }

            $this->authRepo->updateProfile($user, $updateData);

            return [
                'status'  => true,
                'message' => 'Profile updated successfully.',
                'user'    => $user->fresh(),
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }
}
