<?php

namespace Modules\Accounts\App\Services;

use Illuminate\Support\Facades\Auth;
use Modules\Accounts\App\Models\Account;

class AuthService
{
    public function attemptLogin(array $data): ?Account
    {
        $account = Account::firstWhere('email', $data['email']);

        if (! $account || ! $account->checkPassword($data['password'])) {
            return null;
        }

        return $account->login($data['remember'] ?? false);
    }
}
//public function attemptLogin(array $data): ?Account
//{
//    $credentials = [
//        'email' => $data['email'],       // используем email
//        'password' => $data['password'], // пароль
//    ];
//
//    if (Auth::guard('accounts')->attempt($credentials, $data['remember'] ?? false)) {
//        return Auth::guard('accounts')->user();
//    }
//
//    return null;
//}
