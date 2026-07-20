<?php

namespace Modules\Reviews\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Reviews\App\Enums\ReviewSource;
use Modules\Storage\App\Resources\FileResource;
use Modules\Storage\App\Resources\ImageResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $product = match ($this->source) {
            ReviewSource::Ozon => $this->marketplace_product_id ? ($this->relationLoaded('ozonProduct') ? $this->ozonProduct : $this->ozonProduct()->first()) : null,
            ReviewSource::Wb => $this->marketplace_product_id ? ($this->relationLoaded('wbProduct') ? $this->wbProduct : $this->wbProduct()->first()) : null,
        };

        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'status' => $this->status,
            'rating' => $this->rating,
            'source' => $this->source->value,
            'source_label' => $this->source->label(),
            'product_title' => $product?->title,
            'marketplace_product_id' => $this->marketplace_product_id,
            'created_at' => $this->created_at->isoFormat('LLL'),
            'images' => $this->whenLoaded('images', fn () => ImageResource::collection($this->getImagesByGroup('editor'))),
            'videos' => $this->whenLoaded('files', fn () => FileResource::collection($this->getFilesByGroup('editor'))),
            'sort' => $this['sort'],
        ];
    }
}
