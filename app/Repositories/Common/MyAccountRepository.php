<?php

namespace App\Repositories\Common;

use App\Models\NotificationSettingModel;
use App\Models\User;

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
}
