<?php

namespace Modules\Shop\App\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopOrderProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'alias' => $this->alias,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'currentPrice' => $this->pivot->price,
            'count' => $this->pivot->count,
            'first_preview' => $this->previewImages()->first()?->getPreview(),
            'attributesValue' => $this->attributesValue,
        ];
    }
}
