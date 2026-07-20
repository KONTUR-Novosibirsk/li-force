<?php

namespace Modules\Shop\App\Controllers\Public;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shop\App\Models\ShopOrder;
use Modules\Shop\App\Requests\StoreOrderRequest;
use Modules\Shop\App\Services\Cart;
use Modules\Shop\App\Services\Cdek\CdekApiService;
use Modules\Shop\App\Services\Saby\SabyIntegration;
use Modules\Shop\App\Services\TBank\Tbank;

class OrderController extends \App\Http\Controllers\Controller
{
    public function store(
        Cart $cart,
        StoreOrderRequest $request,
        SabyIntegration $saby,
        CdekApiService $cdek,
        Tbank $tbank
    ): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $cartData = $cart->getItemsFromDb();

        if ($cartData['products']->count() === 0) {
            return redirect()->route('index');
        }

        $result = $saby->addOrderInSaby($cartData, $request->input('user_name'));
        $accountId = null;

        if ($user = current_auth_user()) $accountId = $user['id'];

        $order = new ShopOrder([
            'account_id' => $accountId,
            'user_name' => $request->input('user_name'),
            'user_email' => $request->input('user_email'),
            'user_phone' => $request->input('user_phone'),
            'address' => $request->input('address'),
            'payment_type' => $request->input('payment_type'),
            'user_type' => $request->input('user_type'),
            'comment' => $request->input('comment'),
            'is_pickup' => $request->input('is_pickup'),
            'price' => $cartData['totalPrice'],
            'point' => $request->input('point'),
            'saby_id' => $result['result']['d']['ИдентификаторДокумента'] ?? null,
            'cdek_point_delivery' => $request->input('cdek_point_delivery'),
            'cdek_price' => $request->input('cdek_price')
            //'status' => '1',
        ]);

        $order->save();

        if (count($request->input('cdek_tariffs'))) {
            $orderCdek = $cdek->RegistrationOrder($request, $cartData, $order['id']);
            $order['cdek_uuid'] = $orderCdek['entity']['uuid'] ?? null;
            $order['cdek_status'] = $orderCdek['requests'][0]['type'] ?? null;
            $order->save();
        }

        foreach ($cartData['products'] as $product) {
            $order->products()->attach($product->id, [
                'price' => $product->price,
                'count' => $product->count,
            ]);
        }

        //        $order->sendNotifications();
        $cart->flush();

        $tbank->paymentCreate($order, $cartData);
        $link = $tbank->paymentLink();
        $order['t_bank_id'] = $tbank->paymentId();
        $order['t_bank_status'] = 0;
        $order->save();

        return response()->json([
            'success' => true,
            'paymentLink' => $link,
        ]);
    }

    /**
     * Запрос остатков для продуктов корзины
     *
     * @param Request $request
     * @param Cart $cart
     * @param SabyIntegration $saby
     *
     * @return array
     */
    public function getBalance(Request $request, Cart $cart, SabyIntegration $saby): array
    {
        return $saby->getBalancesOrderProducts($request->input('pointId'), $cart->getItemsFromDb());
    }
}
