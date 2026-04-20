<?php

use App\Models\GlobalSettingModel;
use App\Models\User;

if (!function_exists('getSetting')) {
    function getSetting($key, $default = null)
    {
        return GlobalSettingModel::where('name', $key)->value('value') ?? $default;
    }
}

if (!function_exists('calculateCommission')) {
    function calculateCommission($amount)
    {
        $percent = (float) getSetting('platform_commission_percent', 0);
        $fixed   = (float) getSetting('platform_commission_fixed', 0);
        return round(($amount * $percent / 100) + $fixed, 2);
    }
}


if (!function_exists('getUser')) {
    function getUser(int $userId, array|string $columns = ['*']): ?User
    {
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }

        return User::select($columns)->find($userId);
    }
}

if (!function_exists('getUserByEmail')) {
    function getUserByEmail(string $email, array|string $columns = ['*']): ?User
    {
        if (is_string($columns)) {
            $columns = array_map('trim', explode(',', $columns));
        }

        return User::select($columns)->where('email', $email)->first();
    }
}