<?php

namespace Modules\Shop\App\Resources\Cart;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopProductResource extends JsonResource
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
            'sale' => $this->sale,
            'url' => $this->createUrl(),
            'count' => $this->count,
            'hash' => $this->hash,
            'first_preview' => $this->previewImages()->first()?->getPreview(),
        ];
    }
}
