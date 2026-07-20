<?php

namespace Modules\Reviews\App\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Reviews\App\Models\Review;
use Modules\Shop\App\Models\ShopProduct;

final readonly class ReviewsService
{
    public function getByProduct(ShopProduct $product): LengthAwarePaginator
    {
        return Review::query()
            ->where('status', true)
            ->where(function ($q) use ($product) {
                $q->orWhereIn('id', $product->ozonReviews()->pluck('id'))
                    ->orWhereIn('id', $product->wbReviews()->pluck('id'));
            })
            ->orderByDesc('sort')
            ->orderByDesc('created_at')
            ->paginate(20);
    }
}
