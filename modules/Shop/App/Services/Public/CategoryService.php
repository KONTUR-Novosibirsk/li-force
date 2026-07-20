<?php

namespace Modules\Shop\App\Services\Public;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Modules\Shop\App\Contracts\ProductFilterContract;
use Modules\Shop\App\Filters\ProductFilter;
use Modules\Shop\App\Models\ShopCategory;
use Modules\Shop\App\Models\ShopProduct;

class CategoryService
{
    public function __construct(
        protected Request $request,
    ) {

    }

    public function productsOrTradeOffers(ShopCategory $shopCategory, ProductFilterContract $filter)
    {
        // ...?battery_type=2
        $batteryType = $this->request->get('battery_type');

        $categoryIds = ShopCategory::query()
            ->whereDescendantOrSelf($shopCategory)
            ->pluck('id');

        // только активные продукты все
        $query = ShopProduct::query()
            ->whereHas('categories', function ($q) use ($categoryIds) {
                $q->whereIn('shop_categories.id', $categoryIds);
            })
            ->where('active', 1)
            ->orderBy('sort');

        // если есть батарейка
        if ($batteryType !== null) {
            // в активных продуктах выбираем батарейку по типу
            $query->where('battery_type', $batteryType);
        }

        $filterAttributes = $shopCategory->getFilterableAttributes();

        if ($shopCategory['on_index']) {
            $attributeIds = $filterAttributes->pluck('attribute_id')->toArray();
            $products = ShopProduct::query()->whereHas('attributesValue', function ($query) use ($attributeIds) {
                $query->whereIn('attribute_id', $attributeIds);
            })
                ->publicFilter($filter)
                ->with(['images'])
                ->paginate(settings('per_page', default: 10))
                ->withQueryString();
        } else $products = $query->publicFilter($filter)
            ->with(['images'])
            ->paginate(settings('per_page', default: 10))
            ->withQueryString();

        $mergeData = [
            'products' => $products,
        ];

        return $this->request->ajax()
            ? [
                'html' => view('shop::partial.products', array_merge([
                    'category' => $shopCategory,
                ], $mergeData))->render(),
                'url' => url()->full(),
            ]
            : response()->view('shop::category', array_merge([
                'filterData' => $this->request->all(),
                'category' => $shopCategory,
                'categories' => $shopCategory->children,
                'sortAttributes' => ProductFilter::getSortAttributes(),
                'filterableAttributes' => $filterAttributes,
                'activeBatteryType' => $batteryType,
            ], $mergeData));
    }

    protected function tradeOffers(ShopCategory $shopCategory, ProductFilterContract $filter)
    {
        return [
            'offers' => $shopCategory->offers()->orderBy('sort')->publicFilter($filter)
                ->with(['products' => fn (HasMany $query) => $query->where('active', true), 'products.images'])
                ->paginate(settings('per_page', default: 10))->withQueryString(),
        ];
    }

    protected function products(ShopCategory $shopCategory, ProductFilterContract $filter)
    {
        return [
            'products' => $shopCategory->products()->orderBy('sort')->publicFilter($filter)->where('active', 1)
                ->with(['images', 'categories'])->paginate(settings('per_page', default: 10))->withQueryString(),
        ];
    }
}
