<?php

namespace Modules\Accounts\App\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Accounts\App\Rules\PhoneRule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $result = match ($this['counterparty']['type']) {
            'natural_person' => [
                'counterparty.data.patronymic.value' => 'nullable',
                'counterparty.data.inn.value' => 'nullable|string|size:12',
                'counterparty.data.snils.value' => 'nullable|string'
            ],
            'individual_entrepreneur' => [
                'counterparty.data.patronymic.value' => 'nullable',
                'counterparty.data.inn.value' => 'required|string|size:12'
            ],
            'organization' => [
                'counterparty.data.inn.value' => 'required|string|size:10',
                'counterparty.data.kpp.value' => 'required|string|size:9',
                'counterparty.data.name.value' => 'nullable'
            ]
        };

        $result = array_merge($result, [
            'full_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:accounts,email,'.$this->account->id,
            'phone' => ['required', 'string', new PhoneRule],
            'current_password' => 'required_with:password|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        return $result;
    }

    public function messages(): array
    {
        return [
            'phone' => 'Некорректный формат телефона. Используйте +7 (XXX) XXX-XX-XX или 8 (XXX) XXX-XX-XX',
            'password.confirmed' => 'Пароли не совпадают',
            'counterparty.data.inn.value.required_without' => 'ИНН обязателен, если не указан СНИЛС',
            'counterparty.data.inn.value.size' => 'ИНН должен содержать 10 символов для организации и 12 символов для ИП или физического лица',
            'counterparty.data.snils.value.required_without' => 'СНИЛС обязателен, если не указан ИНН',
            'counterparty.data.inn.value.required' => 'Поле ИНН обязательно для заполнения.',
            'counterparty.data.kpp.value.required' => 'Поле КПП обязательно для заполнения.'
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->all();

        if (empty($data['password'])) {
            unset($data['password']);
            unset($data['password_confirmation']);
            $this->replace($data);
        }
    }
}
