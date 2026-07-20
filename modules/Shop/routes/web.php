<?php

use Illuminate\Support\Facades\Route;
use Modules\Shop\App\Controllers\Admin\AttributeCategoryController;
use Modules\Shop\App\Controllers\Admin\AttributeController;
use Modules\Shop\App\Controllers\Admin\CategoryController;
use Modules\Shop\App\Controllers\Admin\FilterController;
use Modules\Shop\App\Controllers\Admin\ProductController;
use Modules\Shop\App\Controllers\Admin\TradeOfferController;
use Modules\Shop\App\Controllers\Public\CartController;
use Modules\Shop\App\Controllers\Public\CdekController;
use Modules\Shop\App\Controllers\Public\CompareController;
use Modules\Shop\App\Controllers\Public\DaDataController;
use Modules\Shop\App\Controllers\Public\IndexController;
use Modules\Shop\App\Controllers\Public\TBankController;

Route::middleware(['web', 'module:shop'])->group(function () {

    Route::group(['controller' => DaDataController::class, 'prefix' => 'DaData'], function () {
        Route::get('/getAddress', 'getAddress')->name('DaData.getAddress');
    });

    Route::group(['controller' => TBankController::class, 'prefix' => 'tbank'], function () {
        Route::get('/success/{order}', 'success')->name('tbank.success');
    });

    Route::group(['controller' => CdekController::class, 'prefix' => 'cdek'], function () {
        Route::post('/getPointsOfDelivery', 'getPointsOfDelivery')->name('cdek.getPointsOfDelivery');
        Route::post('/getShippingPrice', 'getShippingPrice')->name('cdek.getShippingPrice');
    });

    Route::group(['controller' => CartController::class, 'prefix' => 'cart'], function () {
        Route::get('/', 'index')->name('cart.index');
        Route::post('/add', 'addProduct')->name('cart.add');
        Route::post('/remove', 'remove')->name('cart.remove');
        Route::post('/flush', 'flush')->name('cart.flush');
    });
    Route::group(['controller' => CompareController::class, 'prefix' => 'compare'], function () {
        Route::get('/', 'compare')->name('compare.index');
        Route::get('/likes', 'likes')->name('compare.likes.index');
        // роут на сравнение
        Route::get('/sravnenie-tovarov/{productId}', 'sravnenieTovarov')
            ->where('productId', '[0-9]+')
            ->name('compare.sravnenie');
    });

    Route::group(['controller' => CompareController::class, 'prefix' => 'likes'], function () {
        Route::get('/', 'likes')->name('likes.index');
        Route::get('/add/{productId}', 'addProductToLikes')
            ->where('productId', '[0-9]+')
            ->name('likes.add');
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::get('/', [IndexController::class, 'index'])->name('shop.index');
        Route::post('/order/store', [\Modules\Shop\App\Controllers\Public\OrderController::class, 'store'])->name('shop.order.store');
        Route::post('/order/getBalance', [\Modules\Shop\App\Controllers\Public\OrderController::class, 'getBalance'])->name('shop.order.getBalance');
        Route::get('/search', [\Modules\Shop\App\Controllers\Public\ProductController::class, 'search'])->name('shop.search');
        Route::get('{category}/product/{product:alias}', [\Modules\Shop\App\Controllers\Public\ProductController::class, 'product'])
            ->where('category', '.*')
            ->name('shop.category.product')
            ->middleware('log.product.view');
        Route::get('hit-products', [\Modules\Shop\App\Controllers\Public\ProductController::class, 'hitProducts'])
            ->name('shop.hit-products');
        Route::get('{categoryPath}', [\Modules\Shop\App\Controllers\Public\CategoryController::class, 'categoryPath'])
            ->where('categoryPath', '.*');
    });

});

Route::group(['prefix' => 'admin/shop', 'middleware' => ['web', 'dashboard', 'module:shop']], function () {

    Route::group(['prefix' => 'offer'], function () {
        Route::get('/tradeOffers/{shopCategory}', [TradeOfferController::class, 'tradeOffers'])->name('admin.shop.offer.index');
        Route::get('/create/{shopCategory}', [TradeOfferController::class, 'create'])->name('admin.shop.offer.create');
        Route::post('/store', [TradeOfferController::class, 'store'])->name('admin.shop.offer.store');
        Route::get('/edit/{tradeOffer}', [TradeOfferController::class, 'edit'])->name('admin.shop.offer.edit');
        Route::patch('/update/{tradeOffer}', [TradeOfferController::class, 'update'])->name('admin.shop.offer.update');
        Route::delete('/delete/{tradeOffer}', [TradeOfferController::class, 'destroy'])->name('admin.shop.offer.delete');
        Route::post('/{tradeOffer}/update-sort', [TradeOfferController::class, 'updateSort'])->name('admin.shop.offer.updateSort');
        Route::post('/getOfferSort', [TradeOfferController::class, 'getOfferSort'])->name('admin.shop.offer.getOfferSort');
    });

    Route::group(['prefix' => 'filter'], function () {
        Route::get('/', [FilterController::class, 'index'])->name('admin.shop.filter.index');
        Route::get('/create', [FilterController::class, 'create'])->name('admin.shop.filter.create');
        Route::post('/store', [FilterController::class, 'store'])->name('admin.shop.filter.store');
        Route::get('/edit/{shopFilter}', [FilterController::class, 'edit'])->name('admin.shop.filter.edit');
        Route::patch('/update/{shopFilter}', [FilterController::class, 'update'])->name('admin.shop.filter.update');
        Route::delete('/delete/{shopFilter}', [FilterController::class, 'destroy'])->name('admin.shop.filter.delete');
    });

    Route::group(['prefix' => 'category'], function () {
        Route::get('/show/{shopCategory}', [CategoryController::class, 'show'])->name('admin.shop.category.show');
        Route::get('/create/{shopCategory?}', [CategoryController::class, 'create'])->name('admin.shop.category.create');
        Route::post('/store', [CategoryController::class, 'store'])->name('admin.shop.category.store');
        Route::get('/edit/{shopCategory}', [CategoryController::class, 'edit'])->name('admin.shop.category.edit');
        Route::patch('/update/{shopCategory}', [CategoryController::class, 'update'])->name('admin.shop.category.update');
        Route::delete('/delete/{shopCategory}', [CategoryController::class, 'destroy'])->name('admin.shop.category.delete');
        Route::post('/{category}/update-sort', [CategoryController::class, 'updateSort'])->name('admin.shop.category.updateSort');
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/create/{shopCategory}', [ProductController::class, 'create'])->name('admin.shop.product.create');
        Route::post('/store', [ProductController::class, 'store'])->name('admin.shop.product.store');
        Route::get('/edit/{shopProduct}', [ProductController::class, 'edit'])->name('admin.shop.product.edit');
        Route::patch('/update/{shopProduct}', [ProductController::class, 'update'])->name('admin.shop.product.update');
        Route::delete('/delete/{shopProduct}', [ProductController::class, 'destroy'])->name('admin.shop.product.delete');
        Route::get('/clone/{shopProduct}', [ProductController::class, 'clone'])->name('admin.shop.product.clone');
        Route::post('/{product}/update-sort', [ProductController::class, 'updateSort'])->name('admin.shop.product.updateSort');
        Route::post('/getProductsSort', [ProductController::class, 'getProductsSort'])->name('admin.shop.product.getProductsSort');
    });

    Route::group(['prefix' => 'attribute'], function () {
        Route::get('/', [AttributeController::class, 'index'])->name('admin.shop.attribute.index');
        Route::post('/store', [AttributeController::class, 'store'])->name('admin.shop.attribute.store');
        Route::patch('/update/{shopAttribute}', [AttributeController::class, 'update'])->name('admin.shop.attribute.update');
        Route::delete('/delete/{shopAttribute}', [AttributeController::class, 'destroy'])->name('admin.shop.attribute.delete');

        Route::group(['prefix' => 'category'], function () {
            Route::post('/store', [AttributeCategoryController::class, 'store'])->name('admin.attribute.category.store');
            Route::patch('/update/{shopCategoryAttribute}', [AttributeCategoryController::class, 'update'])->name('admin.attribute.category.update');
            Route::delete('/delete/{shopCategoryAttribute}', [AttributeCategoryController::class, 'destroy'])->name('admin.attribute.category.delete');
        });
    });

    Route::group(['prefix' => 'order'], function () {
        Route::get('/', [\Modules\Shop\App\Controllers\Admin\OrderController::class, 'index'])->name('admin.shop.order.index');
        Route::get('/show/{shopOrder}', [\Modules\Shop\App\Controllers\Admin\OrderController::class, 'show'])->name('admin.shop.order.show');
        Route::patch('/status/{shopOrder}', [\Modules\Shop\App\Controllers\Admin\OrderController::class, 'changeStatus'])->name('admin.shop.order.changeStatus');
        Route::delete('/delete/{shopOrder}', [\Modules\Shop\App\Controllers\Admin\OrderController::class, 'destroy'])->name('admin.shop.order.delete');
        Route::post('/updateOrder/{shopOrder}', [\Modules\Shop\App\Controllers\Admin\OrderController::class, 'updateOrder'])->name('admin.shop.order.update');
    });

    Route::get('/index', [\Modules\Shop\App\Controllers\Admin\IndexController::class, 'index'])->name('admin.shop.index');
    Route::post('/run-update-catalog', [\Modules\Shop\App\Controllers\Admin\SabyController::class, 'updateCatalog'])->name('admin.shop.updateCatalog');
    Route::get('/update-catalog/progress', [\Modules\Shop\App\Controllers\Admin\SabyController::class, 'getProgress'])->name('admin.shop.updateCatalog.progress');
});
