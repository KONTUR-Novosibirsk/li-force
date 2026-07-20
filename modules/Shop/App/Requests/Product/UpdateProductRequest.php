<?php

namespace Modules\Shop\App\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            'title' => 'required|string|max:250',
            'alias' => ['required', 'alpha_dash:ascii', Rule::unique('shop_products', 'alias')
                ->when($this->category_id, fn ($query) => $query->where('category_id', $this->category_id))
                ->whereNot('id', $this->shopProduct->id),
            ],
            'description' => 'nullable|string',
            'category_ids' => 'required|array|min:1',
            'price' => 'required|integer',
            'old_price' => 'nullable|integer',
            'active' => 'nullable|boolean',
            'hit' => 'nullable|boolean',
            'show_on_index_page' => 'nullable|boolean',
            'show_on_shop_index_page' => 'nullable|boolean',
            'attributes_for_edit.*.attribute_id' => 'integer|exists:shop_attributes,id',
            'attributes_for_edit.*.value' => 'required',
            'sort' => 'nullable|integer',
            'battery_type' => 'integer|nullable',
            'stock' => 'string|nullable',
            'gross' => 'string|nullable',
            'link_wb' => 'string|nullable',
            'link_ozon' => 'string|nullable',
            'scope_of_application' => 'string|nullable',
            'short_description' => 'string|nullable',
            'new_products' => 'nullable|boolean',
            'ozon_id' => 'nullable|integer',
            'wb_id' => 'nullable|integer',
            'video_link' => 'nullable|string'
        ];
    }
}
