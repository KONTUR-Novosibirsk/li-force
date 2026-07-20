<?php

namespace Modules\Shop\App\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Shop\App\Services\Cart;
use Modules\Shop\App\Services\Cdek\CdekApiService;

class CdekController extends Controller
{
    public function __construct(protected CdekApiService $cdek)
    {

    }

    /**
     * Получает пункты выдачи товаров в запрошенном городе
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getPointsOfDelivery(Request $request): JsonResponse
    {
        return response()->json($this->cdek->getPointsOfDelivery($request->input('city')));
    }

    /**
     * Получает пункт выдачи товаров по координатам
     *
     * @param Request $request
     * @param Cart $cart
     *
     * @return JsonResponse
     */
    public function getShippingPrice(Request $request, Cart $cart): JsonResponse
    {
        return response()->json($this->cdek->getShippingPrice(
            $request->input('lat'),
            $request->input('lon'),
            $request->input('address'),
            $request->input('toLocation'),
            $cart
        ));
    }
}
