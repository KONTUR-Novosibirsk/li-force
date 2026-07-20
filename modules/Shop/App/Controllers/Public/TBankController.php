<?php

namespace Modules\Shop\App\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Shop\App\Models\ShopOrder;
use Modules\Shop\App\Services\TBank\Tbank;

class TBankController extends Controller
{
    /**
     * Проверка статуса оплаты
     */
    public function success(ShopOrder $order, Tbank $tbank): Response
    {
        if ($tbank->paymentCheck($order['t_bank_id'])) {
            $order['t_bank_status'] = 1;
            $order->save();
        }

        return response()->view('shop::tbank.success');
    }
}
