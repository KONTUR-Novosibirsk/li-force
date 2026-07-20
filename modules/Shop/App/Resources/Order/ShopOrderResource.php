<?php

namespace Modules\Shop\App\Resources\Order;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Shop\App\Models\ViewedProduct;
use Modules\Storage\App\Resources\ImageResource;

class ShopOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = [];
        foreach ($this->resource->products as $product) {
            $images = [];
            //            dd($product->images);
            // в продуктах связь с картинками
            foreach ($product->images as $image) {
                $images[] = [
                    'id' => $image->id,
                    'group' => $image->group,
                    'name' => $image->name,
                    'preview_url' => $image->getPreview(), // Сжатая картинка
                    'full_url' => $image->getFull(), // Оригинальное изображение
                ];
            }
            $products[] = [
                'id' => $product->id,
                'title' => $product->title,
                'price' => $product->price,
                'old_price' => $product->old_price,
                'count' => $product->pivot->count,
                'images' => $images,
            ];
        }
        $story_watch = [];
        if (current_auth_user() !== null) {
            $viewed = ViewedProduct::where('account_id', current_auth_user()->id)
                ->with('product.images')
                ->orderBy('viewed_at', 'desc')
                ->get()
                ->unique('product_id')  // если несколько раз смотреть один товар
                ->take(9);               // нужно только 9 как лимит

            foreach ($viewed as $view) {
                $product = $view->product;

                if ($product) {
                    $story_watch[] = [
                        'id' => $product->id,
                        'title' => $product->title,
                        'price' => $product->price,
                        'images' => ImageResource::collection($product->images),
                        'viewed_at' => $view->viewed_at,
                        'url' => $product->createUrl(),
                    ];
                }
            }
        }

        return [
            'id' => $this->id,
            'user_name' => $this->user_name,
            'user_phone' => $this->user_phone,
            'user_email' => $this->user_email,
            'price' => $this->price,
            'address' => $this->address, // пока нулевой
            'status' => $this->status,
            'is_pickup' => $this->is_pickup,
            'date_for_human' => $this->created_at->format('d.m.Y'),
            'updated_at' => $this->updated_at->isoFormat('DMY'),
            'products' => $products,
            'id_zakaza' => $products,
            'payment_type' => $this->payment_type,
            'story_watch' => $story_watch,
            'point' => $this['point'],
            'cdek_point_delivery' => $this['cdek_point_delivery'],
            'cdek_status' => $this['cdek_status'],
            'cdek_price' => $this['cdek_price'],
            't_bank_status' => (int) $this['t_bank_status']
        ];
    }
}
