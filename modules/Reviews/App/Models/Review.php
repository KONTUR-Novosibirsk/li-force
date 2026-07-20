<?php

namespace Modules\Reviews\App\Models;

use App\Models\BaseModel;
use Database\Factories\ReviewFactory;
use Galtsevt\LaravelStorage\App\Interfaces\Fileable;
use Galtsevt\LaravelStorage\App\Interfaces\Imageable;
use Galtsevt\LaravelStorage\App\Traits\HasFiles;
use Galtsevt\LaravelStorage\App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kontur\Dashboard\App\Casts\HtmlPurify;
use Modules\Reviews\App\Enums\ReviewSource;
use Modules\Shop\App\Models\ShopProduct;

class Review extends BaseModel implements Fileable, Imageable
{
    use HasFactory, HasFiles, HasImages;

    protected $guarded = false;

    protected $casts = [
        'source' => ReviewSource::class,
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'text' => HtmlPurify::class,
    ];

    protected static function newFactory(): ReviewFactory
    {
        return ReviewFactory::new();
    }

    public function scopeFilter($query, array $filters)
    {
        $query
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('text', 'like', "%{$search}%")
                        ->orWhere('marketplace_product_id', "$search")
                        ->orWhereHas('ozonProduct', fn ($q) => $q->where('title', 'like', "%{$search}%"))
                        ->orWhereHas('wbProduct', fn ($q) => $q->where('title', 'like', "%{$search}%"));
                });
            })
            ->when(
                array_key_exists('status', $filters),
                fn ($query) => $query->where('status', filter_var($filters['status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE))
            );
    }

    public function ozonProduct(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class, 'marketplace_product_id', 'ozon_id');
    }

    public function wbProduct(): BelongsTo
    {
        return $this->belongsTo(ShopProduct::class, 'marketplace_product_id', 'wb_id');
    }
}
