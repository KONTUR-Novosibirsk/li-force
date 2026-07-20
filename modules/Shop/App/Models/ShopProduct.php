<?php

namespace Modules\Shop\App\Models;

use App\Models\BaseModel;
use Database\Factories\ShopProductFactory;
use Galtsevt\LaravelSeo\App\Interfaces\SitemapContract;
use Galtsevt\LaravelSeo\App\Traits\HasSeo;
use Galtsevt\LaravelStorage\App\Interfaces\Imageable;
use Galtsevt\LaravelStorage\App\Traits\HasImages;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Kalnoy\Nestedset\Collection;
use Modules\Reviews\App\Enums\ReviewSource;
use Modules\Reviews\App\Models\Review;
use Modules\Shop\App\Contracts\CartableContract;
use Modules\Shop\App\Models\Scopes\ShopProductScopes;

class ShopProduct extends BaseModel implements CartableContract, Imageable, SitemapContract
{
    use HasFactory, HasImages, HasSeo, ShopProductScopes;

    public $attrs;

    public $offer;

    protected $fillable = [
        'title',
        'alias',
        'description',
        'category_id',
        'trade_offer_id',
        'price',
        'old_price',
        'active',
        'hit',
        'show_on_index_page',
        'show_on_shop_index_page',
        'sort',
        'battery_type',
        'new_products',
        'stock',
        'gross',
        'link_wb',
        'link_ozon',
        'scope_of_application',
        'short_description',
        'ozon_id',
        'wb_id',
        'video_link',
        'point_saby',
        'price_list_saby',
        'external_id'
    ];

    protected $casts = [
        'active' => 'boolean',
        'not_available' => 'boolean',
        'is_new' => 'boolean',
        'hit' => 'boolean',
        'show_on_index_page' => 'boolean',
        'show_on_shop_index_page' => 'boolean',
    ];

    protected static function newFactory(): ShopProductFactory
    {
        return ShopProductFactory::new();
    }

    public function previewImages(): ?\Illuminate\Database\Eloquent\Collection
    {
        return $this->getImagesByGroup('preview');
    }

    public function category()
    {
        $categories = $this->categories()
            ->where('shop_category_product.sort', 1)
            ->get();

        $category = $categories->first();

        if (!$category) {
            $category = $this->categories()->get()->first();
        }

        return $category;
    }

    public function categories(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            ShopCategory::class, 'shop_category_product', 'product_id', 'category_id')->withPivot('sort')
            ->orderBy('pivot_sort');
    }

    public function attributesValue(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShopAttributeValue::class, 'product_id', 'id');
    }

    public function tradeOffer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ShopTradeOffer::class, 'trade_offer_id', 'id');
    }

    public function hashToCart(): string|false
    {
        $hash = [];
        $hash['id'] = $this->id;
        $hash['title'] = $this->title;
        $hash['price'] = $this->price;

        if ($this->attrs) {
            foreach ($this->attrs as $attr) {
                $hash[$attr['id']] = $attr['value'];
            }
        }

        if (! empty($this->offer)) {
            $hash['price'] = $this->offer['price'];
        }

        return mb_strcut(md5(json_encode($hash)), 0, 8);
    }

    public function cartAttributes(): array
    {
        $additionalAttrs = [];
        if ($this->attrs) {
            foreach ($this->attrs as $attr) {
                $additionalAttrs[$attr['id']] = $attr['value'];
            }
        }

        $attrs = [
            'id' => $this->getAttribute('id'),
            'title' => $this->getAttribute('title'),
            'price' => $this->price,
            'attributes' => $additionalAttrs,
        ];

        if (! empty($this->offer)) {
            $attrs['price'] = $this->offer['price'];
        }

        return $attrs;
    }

    public static function productsOnShopIndex()
    {
        return ShopProduct::query()->where([
            ['active', 1],
            ['show_on_shop_index_page', 1],
        ])->orderBy('sort')->latest()->paginate(12);
    }

    public function attributesForEdit(): ?array
    {
        foreach ($this->attributesValue->groupBy('attribute_id') as $attributeGroup) {
            $attributeArray = [];
            foreach ($attributeGroup as $attribute) {
                if ($attributeGroup->count() > 1) {
                    $attributeArray['value'][] = $attribute->value;
                } else {
                    $attributeArray['value'] = $attribute->value;
                }
                $attributeArray['attribute_id'] = $attribute->attribute_id;
                $attributeArray['name'] = $attribute->attribute->name;
            }
            $result[] = $attributeArray;
        }

        return $result ?? null;
    }

    public function attributes(): array
    {
        $attributes = [];
        if ($this->attributesForEdit()) {
            foreach ($this->attributesForEdit() as $attribute) {
                $attributes[] = [
                    'name' => $attribute['name'],
                    'value' => is_array($attribute['value']) ? implode(',', $attribute['value']) : $attribute['value'],
                ];
            }
        }

        return $attributes;
    }

    public function jsonDataForCartButton(): bool|string
    {
        return json_encode([
            'id' => $this->id,
            'price' => $this->price,
        ]);
    }

    public function createUrl(?ShopCategory $category = null): string
    {
        return ($category ? $category->createUrl() : $this->category()->createUrl()).'/product/'.$this->alias;
    }

    public function getSitemapUrl(): string
    {
        return $this->createUrl();
    }

    public function getSitemapDate(): string
    {
        return $this->updated_at->format('Y-m-dTH:i:sP');
    }

    public function ozonReviews()
    {
        return $this->hasMany(Review::class, 'marketplace_product_id', 'ozon_id')
            ->where('source', ReviewSource::Ozon);
    }

    public function wbReviews()
    {
        return $this->hasMany(Review::class, 'marketplace_product_id', 'wb_id')
            ->where('source', ReviewSource::Wb);
    }

    /**
     * Обработка ссылки на видео в формат для плеера
     *
     * @return string|null
     */
    public function getVideoLink(): string|null
    {
        $domain = null;

        if ($this['video_link']) $domain = parse_url($this['video_link'], PHP_URL_HOST);

        if ($domain) {
            $link = match ($domain) {
                'rutube.ru' => preg_match('#/video/([a-zA-Z0-9]+)#', $this['video_link'], $m)
                    ? "https://rutube.ru/play/embed/{$m[1]}" : null,
                'vkvideo.ru' => (function () {
                    preg_match('#video-([\d_]+)#', $this['video_link'], $m);

                    if (!isset($m[1])) {
                        return null;
                    }

                    [$ownerId, $videoId] = explode('_', $m[1]);

                    return "https://vkvideo.ru/video_ext.php?oid=-{$ownerId}&id={$videoId}&hash=";
                })()
            };

            return $link;
        }

        return null;
    }

    /**
     * Получает все продукты из бд которые присутствуют в массиве result (С этим товаром покупают)
     *
     * @param ?array $result
     *
     * @return \Illuminate\Database\Eloquent\Collection|array
     */
    public function getBuyWithProduct(?array $result): \Illuminate\Database\Eloquent\Collection|array
    {
        if ($result) {
            if (count($result['related_products'])) {
                $externalIds = array_column($result['related_products'], 'externalId');

                return $this::query()->whereIn('external_id', $externalIds)->get();
            }
        }

        return [];
    }

    public static function booted()
    {
        static::deleting(function ($product) {
            $product->categories()->detach();
        });
    }
}
