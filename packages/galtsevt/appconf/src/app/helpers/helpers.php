<?php

use Galtsevt\AppConf\app\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Modules\Accounts\App\Models\Account;

function settings(string $key, $group = 'main', $default = null)
{
    if (! $settings = Cache::get('admin_settings'.$group, null)) {
        $settings = Setting::getByGroup($group);
        Cache::put('admin_settings'.$group, $settings);
    }

    return $settings[$key] ?? $default;
}
function current_auth_user(): ?Account
{
    $token = session('auth') ?: request()->cookie('remember_token') ?: request()->bearerToken();

    if (! $token) {
        return null;
    }

    $plainToken = str_contains($token, '|') ? explode('|', $token, 2)[1] : $token;

    return Account::whereHas('tokens', function ($query) use ($plainToken) {
        $query->where('token', hash('sha256', $plainToken));
    })->first();
}
