<?php

namespace Modules\Reviews\App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:250|required',
            'text' => 'nullable|string|max:2000',
            'sort' => 'nullable|integer',
            'status' => 'required|boolean',
            'rating' => 'nullable|integer|min:1|max:5',
            'source' => 'nullable|string',
        ];
    }
}
