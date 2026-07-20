<?php

namespace Modules\Reviews\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'name' => 'string|max:250|required',
            'text' => 'nullable|string|max:2000',
            'sort' => 'nullable|integer',
            'status' => 'required|boolean',
            'rating' => 'required|integer|min:1|max:5',
            'source' => 'required|string',
        ];
    }
}
