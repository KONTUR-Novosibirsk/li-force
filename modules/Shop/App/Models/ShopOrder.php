<?php

namespace Modules\Shop\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Shop\App\Events\OrderCreated;
use Modules\Shop\App\Services\Cdek\CdekApiService;
use Modules\Shop\App\Services\Saby\SabyIntegration;

class ShopOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_name',
        'user_phone',
        'user_email',
        'price',
        'address',
        'status',
        'is_pickup',
        'account_id',
        'payment_type',
        'point',
        'saby_id',
        'cdek_point_delivery',
        'cdek_price',
        't_bank_id',
        't_bank_status'
    ];

    protected $casts = [
        //        'is_pickup' => 'boolean',
        'cdek_point_delivery' => 'array',
        't_bank_status' => 'boolean'
    ];

    public function pivotProducts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShopOrderProduct::class, 'order_id', 'id');
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ShopProduct::class, 'shop_order_products', 'order_id', 'product_id')
            ->withPivot(['price', 'count'])->with('attributesValue');
    }

    public function sendNotifications()
    {
        event(new OrderCreated($this));
    }

    /**
     * Проверка статуса заказа на доставку в сдэк
     *
     * @param CdekApiService $cdek
     */
    public function cdekCheckStatus(CdekApiService $cdek): void
    {
        if ($this['cdek_uuid']) {
            $cdekResult = $cdek->getOrderInfo($this['cdek_uuid']);

            if ($cdekResult['entity']['statuses'][0]['code'] != $this['cdek_status']) {
                $this['cdek_status'] = $cdekResult['entity']['statuses'][0]['code'];
                $this->save();
            }
        }
    }

    /**
     * Берет все модели с существующим идентификатором саби и статусом 0, проверят их статус
     *
     * @param SabyIntegration $saby
     */
    public static function checkOrderSabyStatus(SabyIntegration $saby): void
    {
        $orders = ShopOrder::query()->whereNotNull('saby_id')->where('status', 0)->get();

        foreach ($orders as $order) {
            if ($saby->checkOrderInSaby($order)) {
                $order['status'] = 5;
                $order->save();
            }
        }
    }
}
