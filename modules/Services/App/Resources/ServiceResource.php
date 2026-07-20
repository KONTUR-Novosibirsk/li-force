<?php

namespace Modules\Services\App\Resources;

use Galtsevt\LaravelSeo\App\Resources\SeoResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Storage\App\Resources\ImageResource;

class ServiceResource extends JsonResource
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
            'name' => $this->name,
            'alias' => $this->alias,
            'price' => $this->price,
            'image' => $this->image() ? new ImageResource($this->image()) : null,
            'is_active' => $this->isActive(),
            'short_description' => $this->short_description,
            'description' => $this->description,
            'seo' => new SeoResource($this->whenLoaded('seo')),
            'created_at' => $this->created_at->isoFormat('LLL'),
            'sort' => $this['sort'],
        ];
    }
}
