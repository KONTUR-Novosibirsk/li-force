<?php

namespace Modules\Shop\App\Controllers\Public;

use Galtsevt\LaravelSeo\App\Facades\Seo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Reviews\App\Services\ReviewsService;
use Modules\Shop\App\Models\ShopCategory;
use Modules\Shop\App\Models\ShopProduct;
use Modules\Shop\App\Services\NestedService;
use Modules\Shop\App\Services\Public\ProductService;
use Modules\Shop\App\Services\Saby\SabyIntegration;

class ProductController extends \App\Http\Controllers\Controller
{
    public function __construct()
    {
        Seo::breadcrumbs()->add(settings('name', 'shop', 'Магазин'), route('shop.index'));
    }

    public function product(
        Request $request,
        string $category,
        ShopProduct $product,
        NestedService $nestedService,
        ProductService $productService,
        ReviewsService $reviews,
        SabyIntegration $saby
    ): \Illuminate\Http\Response {
        $uri = $request->getPathInfo();

        $category = ShopCategory::getByAliasFromUri($uri);

        if (! $category or ! $category->checkPathValid($uri)) {
            abort(404);
        }

        $category = $category->getByAliasFromUri($uri);

        if (empty($category) || !in_array($category->id, $product->categories->pluck('id')->toArray())) {
            abort(404);
        }

        $nestedService->createPublicChainOfBreadcrumbs($category);

        Session::push('recent.products', $product->id);

        Seo::breadcrumbs()->add($product->title);
        Seo::metaData()->prepare($product)->setTitle($product->title);

        $productService->setRecentProduct($product);

        return response()->view('shop::product', [
            'tradeOfferAttributes' => $product->tradeOffer?->makeOfferTree()?->getOfferAttributes($product),
            'product' => $product,
            'recentProducts' => $productService->getRecentProducts($product),
            'buyWithProducts' => $product->getBuyWithProduct($saby->getProductAllCart($product)),
            'similarProducts' => ShopProduct::query()->where([
                ['id', '!=', $product->id]
            ])->whereHas('categories', function ($q) use ($category) {
                $q->where('shop_categories.id', $category->id);
            })->active()->limit(10)->with(['images', 'categories'])->inRandomOrder()->get(),
            'reviews' => $reviews->getByProduct($product),
        ]);
    }

    public function search(Request $request): \Illuminate\Http\Response
    {
        $search = $request->query('search');

        Seo::breadcrumbs()->add('Поиск');
        Seo::metaData()->setTitle('Поиск по каталогу');

        $products = ShopProduct::query()->search($search)->active()->orderBy('sort')
            ->paginate(settings('per_page', default: 10))->withQueryString();

        return response()->view('shop::search', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function hitProducts(): \Illuminate\Http\Response
    {
        $products = ShopProduct::query()->where('hit', true)
            ->with(['images', 'categories'])
            ->active()->orderBy('sort')->orderBy('id', 'DESC')->paginate(settings('per_page', default: 10));

        Seo::breadcrumbs()->add('Хиты продаж');
        Seo::metaData()->setTitle('Хиты продаж');

        return response()->view('shop::hit-products', [
            'products' => $products,
            'categories' => ShopCategory::query()
                ->where('parent_id', null)
                ->orderBy('sort')
                ->get(),
        ]);
    }
}
