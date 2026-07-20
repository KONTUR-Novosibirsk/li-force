<?php

namespace Modules\Shop\App\Resources;

use Galtsevt\LaravelSeo\App\Resources\SeoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Storage\App\Resources\ImageResource;

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
            'active' => $this->active,
            'hit' => $this->hit,
            'show_on_index_page' => $this->show_on_index_page,
            'show_on_shop_index_page' => $this->show_on_shop_index_page,
            'category_id' => $this->category_id,
            'category_ids' => $this->categories->pluck('id')->toArray(),
            'description' => $this->description,
            'seo' => new SeoResource($this->whenLoaded('seo')),
            'attributes_for_edit' => $this->whenLoaded('attributesValue', fn () => $this->attributesForEdit()),
            'attributes' => ShopAttributeValueResource::collection($this->whenLoaded('attributesValue')),
            'preview' => ImageResource::collection($this->getImagesByGroup('preview')),
            'first_preview' => $this->previewImages()->first()?->getPreview(),
            'sort' => $this['sort'],
            'battery_type' => $this->battery_type,
            'stock' => $this->stock,
            'gross' => $this->gross,
            'link_wb' => $this->link_wb,
            'link_ozon' => $this->link_ozon,
            'scope_of_application' => $this->scope_of_application,
            'short_description' => $this->short_description,
            'new_products' => $this->new_products,
            'ozon_id' => $this->ozon_id,
            'wb_id' => $this->wb_id,
            'video_link' => $this['video_link']
        ];
    }
}
