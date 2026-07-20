<?php

namespace Modules\Services\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:250',
            'alias' => 'required|string|max:250|unique:sale,alias,'.$this->service->id,
            'price' => 'nullable|integer',
            'is_active' => 'required|boolean',
            'short_description' => 'nullable|string|max:1000',
            'description' => 'required|string|max:10000',
            'sort' => 'nullable|integer',
        ];
    }
}
