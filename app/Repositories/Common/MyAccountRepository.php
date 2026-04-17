<?php

namespace App\Repositories\Common;

use App\Models\NotificationSettingModel;
use App\Models\User;
use App\Models\UserAddressModel;

class MyAccountRepository
{
    public function updatePersonalInfo(int $userId, array $data): bool
    {
        return (bool) User::where('id', $userId)->update($data);
    }

    public function getNotificationSettings(int $userId): ?NotificationSettingModel
    {
        return NotificationSettingModel::where('user_id', $userId)->first();
    }

    public function updateNotificationSettings(int $userId, array $settings)
    {
        return NotificationSettingModel::updateOrCreate(
            ['user_id' => $userId],
            ['settings' => $settings]
        );
    }

    public function getUserById(int $userId): ?User
    {
        return User::find($userId);
    }

    public function getAddresses(int $userId)
    {
        return UserAddressModel::where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getAddressById(int $userId, int $addressId): ?UserAddressModel
    {
        return UserAddressModel::where('user_id', $userId)->find($addressId);
    }

    public function createAddress(int $userId, array $data): UserAddressModel
    {
        return UserAddressModel::create([
            'user_id'        => $userId,
            'street_address' => $data['street_address'],
            'city'           => $data['city'],
            'postal_code'    => $data['postal_code'],
            'county'         => $data['county'],
        ]);
    }

    public function updateAddress(int $userId, int $addressId, array $data): bool
    {
        return (bool) UserAddressModel::where('user_id', $userId)
            ->where('id', $addressId)
            ->update([
                'street_address' => $data['street_address'],
                'city'           => $data['city'],
                'postal_code'    => $data['postal_code'],
                'county'         => $data['county'],
            ]);
    }

    public function deleteAddress(int $userId, int $addressId): bool
    {
        return (bool) UserAddressModel::where('user_id', $userId)
            ->where('id', $addressId)
            ->delete();
    }
}
