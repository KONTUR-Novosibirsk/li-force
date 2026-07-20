<?php

namespace Modules\Accounts\App\Services;

use Illuminate\Support\Facades\Password;

class PasswordResetService
{
    public function sendResetLink(string $email): array
    {
        $user = Password::broker('accounts')->getUser(['email' => $email]);
        if (! $user) {
            return ['message' => 'Такого email не существует'];
        }

        $status = Password::broker('accounts')->sendResetLink(['email' => $email]);
        if ($status === Password::RESET_LINK_SENT) {
            return ['message' => 'Ссылка для сброса пароля была отправлена на ваш email'];
        }

        return ['message' => 'Не удалось отправить ссылку для сброса пароля'];
    }

    public function update(array $data): array
    {
        $status = Password::broker('accounts')->reset(
            [
                'email' => $data['email'],
                'password' => $data['password'],
                'password_confirmation' => $data['password_confirmation'] ?? $data['password'],
                'token' => $data['token'],
            ],
            function ($account, $password) {
                $account->password = bcrypt($password); // хешируем один раз
                $account->save();
            }
        );

        return [
            'success' => $status === Password::PASSWORD_RESET,
            'message' => $status === Password::PASSWORD_RESET
                ? 'Пароль успешно сброшен.'
                : 'Ссылка недействительна или пользователь не найден.',
        ];
    }
}
