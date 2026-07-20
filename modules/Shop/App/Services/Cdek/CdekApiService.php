<?php

namespace Modules\Shop\App\Services\Cdek;

use Illuminate\Http\Request;
use Modules\Shop\App\Models\ShopOrder;
use Modules\Shop\App\Services\Cart;

class CdekApiService
{
    /**
     * Код города
     *
     * @var int $cityCode
     */
    public int $cityCode;

    /**
     * Uid города
     *
     * @var string $cityUid
     */
    public string $cityUid;

    /**
     * Эндпоинт (регистрация заказа и прочие процедуры с заказами)
     *
     * @var string $urlRegistrationOrder
     */
    private string $urlRegistrationOrder = '/v2/orders';

    public function __construct(protected CdekCurlService $curlService)
    {
        $this->cityCode = 0;
        $this->cityUid = '';
    }

    /**
     * Получает код запрошенного города
     *
     * @param string $city
     *
     * @return void
     */
    private function getCityCode(string $city): void
    {
        $url = '/v2/location/suggest/cities';
        $dataCurl = [
            "name"         => $city,
            "country_code" => 'RU'
        ];

        $responseArray = $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $url,
            method: 'GET',
            data: $dataCurl
        );

        if ($responseArray) {
            $this->cityCode = $responseArray[0]['code'];
            $this->cityUid = $responseArray[0]['city_uuid'];
        }
    }

    /**
     * Получает координаты города по коду города
     *
     * @return array
     */
    private function getCityCoordinates(): array
    {
        $url = '/v2/location/cities';
        $dataCurl = [
            "country_codes" => 'RU',
            "code"          => $this->cityCode,
            "lang"          => 'rus'
        ];

        $responseArray = $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $url,
            method: 'GET',
            data: $dataCurl
        );

        return ['lat' => $responseArray[0]['latitude'], 'lon' => $responseArray[0]['longitude']];
    }

    /**
     * Получает пункты выдачи в запрошенном городе
     *
     * @param string $city
     * @return array
     */
    public function getPointsOfDelivery(string $city): array
    {
        $url = '/v2/deliverypoints';
        $city = str_replace(['г', ' '], '', $city);
        $this->getCityCode(city: $city);
        $coordinates = $this->getCityCoordinates();

        $dataCurl = [
            "type"         => 'ALL',
            "city_code"    => $this->cityCode,
            "country_code" => 'RU',
            "lang"         => 'rus',
            "is_handout"   => 1
        ];

        $responseArray = $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $url,
            method: 'GET',
            data: $dataCurl
        );

        return ['points' => $responseArray, 'coordinates' => $coordinates];
    }

    /**
     * Получает пункт выдачи товаров по координатам
     *
     * @param float $lat
     * @param float $lon
     *
     * @return array
     */
    public function getPointOfDelivery(float $lat, float $lon): array
    {
        $url = '/v2/location/coordinates';

        $dataCurl = [
            "latitude"  => $lat,
            "longitude" => $lon
        ];

        return $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $url,
            method: 'GET',
            data: $dataCurl
        );
    }

    /**
     * Получает тарифы доставки
     *
     * @param float $lat
     * @param float $lon
     * @param string $address
     * @param array $toLocation
     * @param  Cart $cart
     *
     * @return array
     */
    public function getShippingPrice(float $lat, float $lon, string $address, array $toLocation, Cart $cart): array
    {
        $url = '/v2/calculator/tarifflist';
        $toLocation = $toLocation['original']['location'];
        $fromCity = $this->getPointOfDelivery($lat, $lon);#
        $cartData = $cart->getItemsFromDb();
        $weight = 0;

        foreach ($cartData['products'] as $product) {
            $value = collect($product->attributes())
                ->first(function ($attr) {
                    return mb_strtolower($attr['name']) === 'вес';
                })['value'] ?? null;

            preg_match('/\d+([.,]\d+)?/', $value, $matches);

            $valueNumber = isset($matches[0])
                ? (int) str_replace(',', '.', $matches[0])
                : null;

            $weight += $valueNumber * $product['count'];
        }

        $dataCurl = [
            "currency"      => 0,
            "lang"          => 'rus',
            "from_location" => [
                "code"         => $fromCity['code'],
                "postal_code"  => '',
                "country_code" => 'RU',
                "city"         => $fromCity['city'],
                "address"      => $address,
                "city_uuid"    => $fromCity['city_uuid'],
            ],
            "to_location"   => [
                "code"         => $toLocation['city_code'],
                "postal_code"  => $toLocation['postal_code'],
                "country_code" => 'RU',
                "city"         => $toLocation['city'],
                "address"      => $toLocation['address_full'],
                "city_uuid"    => $toLocation['city_uuid'],
            ],
            "packages"      => [
                "weight" => $weight
            ]
        ];

        $responseArray = $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $url,
            method: 'POST',
            data: $dataCurl,
            applicationJson: true
        );

        $tariff = [];
        foreach ($responseArray['tariff_codes'] as $tariffItem) {
            if (str_contains($tariffItem['tariff_name'], 'дверь-склад')) {
                $tariff[] = $tariffItem;
            }
        }

        return $tariff;
    }

    /**
     * Регистрация заказа-доставки
     *
     * @param Request $request
     * @param array $cartData
     * @param int $orderId
     *
     * @return array|null
     */
    public function RegistrationOrder(Request $request, array $cartData, int $orderId): ?array
    {
        $order = $request->toArray();
        $tariff = null;

        foreach ($order['cdek_tariffs'] as $item) {
            if ($item['tariff_name'] === 'Посылка дверь-склад') {
                $tariff = $item;
                break;
            }
        }

        $itemProductsArray = [];
        $totalWeight = 0;

        foreach ($cartData['products'] as $product) {
            $value = collect($product->attributes())
                ->first(function ($attr) {
                    return mb_strtolower($attr['name']) === 'вес';
                })['value'] ?? null;

            preg_match('/\d+([.,]\d+)?/', $value, $matches);

            $weight = isset($matches[0])
                ? (int) str_replace(',', '.', $matches[0])
                : null;

            $totalWeight += $weight * $product['count'];

            $itemProductsArray[] = [
                'name' => $product['title'],
                'ware_key' => $product['id'],
                'payment' => ['value' => $product['price']],
                'weight' => $weight,
                'amount' => $product['count'],
                'cost' => $product['price']
            ];
        }

        $fromCity = $this->getPointOfDelivery($order['coordinates']['lat'], $order['coordinates']['lon']);

        $dataCurl = [
            'tariff_code' => $tariff['tariff_code'],
            //'shipment_point' => '', // code pvz
            'delivery_point' => $order['cdek_point_delivery']['key'],
            'recipient' => [
                'name' => $order['user_name'],
                'email' => $order['user_email'],
                'phones' => ['number' => $order['user_phone']]
            ],
            'from_location' => [
                'code' => $fromCity['code'],
                'city_uuid' => $fromCity['city_uuid'],
                'city' => $fromCity['city'],
                'fias_guid' => $fromCity['fias_guid'],
                'country_code' => 'RU',
                'longitude' => $order['coordinates']['lon'],
                'latitude' => $order['coordinates']['lat'],
                'address' => $order['point']
            ],
            'packages' => [
                'number' => $orderId,
                'weight' => $totalWeight,
                'items' => $itemProductsArray
            ]
        ];

        return $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $this->urlRegistrationOrder,
            method: 'POST',
            data: $dataCurl,
            applicationJson: true
        );
    }

    /**
     * Получение информации о заказе-доставки
     *
     * @param string $uuid
     * @return array
     */
    public function getOrderInfo(string $uuid): array
    {
        return $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $this->urlRegistrationOrder . '/' . $uuid,
            method: 'GET'
        );
    }

    /**
     * Удаление заказа-доставки
     *
     * @param string $uuid
     * @return bool
     */
    public function deleteOrder(string $uuid): bool
    {
        $responseArray = $this->curlService->curlRequest(
            url: $this->curlService->apiUrl . $this->urlRegistrationOrder . '/' . $uuid,
            method: 'DELETE'
        );

        if ($responseArray['requests'][0]['state'] == 'ACCEPTED') return true;

        return false;
    }
}
