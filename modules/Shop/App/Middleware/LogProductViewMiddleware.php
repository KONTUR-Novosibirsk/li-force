<?php

namespace Modules\Shop\App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Shop\App\Models\ShopProduct;
use Modules\Shop\App\Models\ViewedProduct;

class LogProductViewMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        //пользователь авторизован и маршрут содержит модель ShopProduct
        if (current_auth_user() && $request->route('product') instanceof ShopProduct) {
            // таблица продукта
            $product = $request->route('product');
            //            dd($product->id); 15
            // таблица акаунт и функция
            $user = current_auth_user(); // объект пользователя
            $userId = $user->id;
            //dd($userId->id); 1
            // база
            ViewedProduct::create([
                'account_id' => $userId,
                'product_id' => $product->id,
            ]);
            // сбор
            $ids = ViewedProduct::where('account_id', $userId)
                ->orderBy('id', 'desc')
                ->limit(9)
                ->pluck('id')
                ->toArray();
            ViewedProduct::where('account_id', $userId)
                ->whereNotIn('id', $ids)
                ->delete();
        }

        return $response;

    }
}
