<?php

use App\Models\GlobalSettingModel;

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