<?php

namespace Modules\Accounts\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'login' => 'required|string|min:3|max:255|unique:accounts',
            'email' => 'required|email|unique:accounts',
            'password' => 'required|min:6',
        ];
    }

    public function messages(): array
    {
        return [
            'login.required' => 'Логин обязателен',
            'email.unique' => 'Такой email уже существует',
            'login.unique' => 'Такой логин уже существует',
            'email.required' => 'Email обязателен',
            'password.required' => 'Пароль обязателен',
            'password.min' => 'Пароль минимум 6 символов',
        ];
    }
}
