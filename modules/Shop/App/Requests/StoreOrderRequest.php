<?php

namespace Modules\Shop\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'account_id' => 'required|integer|exists:accounts,id',
            'user_name' => 'required|string|max:250',
            'user_email' => 'required|email|max:250',
            'user_phone' => 'required|string|max:20',
            'comment' => 'nullable|string|max:1000',
            'is_pickup' => 'nullable|string|max:1000',
            'address' => 'nullable|string|max:1000',
            'payment_type' => 'nullable|string|max:1000',
            'user_type' => 'nullable|string|max:1000',

        ];
    }
}
