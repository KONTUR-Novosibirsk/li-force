<?php

namespace Modules\Shop\App\Services\TBank;

use Modules\Shop\App\Models\ShopOrder;

class Tbank
{
    /**
     * Адрес инициализации платежа
     *
     * @var string $urlInit
     */
    private string $urlInit = "https://securepay.tinkoff.ru/v2/Init";

    /**
     * Адрес проверки статуса платежа
     *
     * @var string $urlCheck
     */
    private string $urlCheck = "https://securepay.tinkoff.ru/v2/GetState";

    /**
     * Данные заказа
     *
     * @var array $orderData
     */
    private array $orderData = [];

    /**
     * Терминал
     *
     * @var string $terminal
     */
    private string $terminal;

    /**
     * Пароль
     *
     * @var string $password
     */
    private string $password;

    public function __construct()
    {
        $this->terminal =  config('services.tbank.TBANK_TERMINAL_ID');
        $this->password =  config('services.tbank.TBANK_PASSWORD');
    }

    /**
     * Генерация токена
     *
     * @param array $tokenArrayData
     *
     * @return string
     */
    protected function generateToken(array $tokenArrayData): string
    {
        $concatenatedString = implode('', array_values($tokenArrayData));

        return hash('sha256', $concatenatedString);
    }

    /**
     * Проверка использования ssl сертификата
     *
     * @return bool
     */
    private function checkSsl(): bool
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') return true;
        else return false;
    }

    /**
     * Создание платежа
     *
     * @param ShopOrder $order
     * @param array $cart
     */
    public function paymentCreate(ShopOrder $order, array $cart): void
    {
        $items = [];

        foreach ($cart['products'] as $product) {
            $items[] = [
                "Name" => $product['title'],
                "Price" => $product['price'] * 100,
                "Quantity" => $product['count'],
                "Amount" => $product['price'] * 100 * $product['count'],
                "Tax" => "none"
            ];
        }

        $tokenArrayData = [
            "Amount" => $order['price'] * 100,
            "Description" => 'Оплата заказа №' . $order['id'],
            "OrderId" => $order['id'],
            "Password" => $this->password,
            "SuccessURL" => route('tbank.success', $order['id']),
            "TerminalKey" => $this->terminal
        ];

        $data = [
            "TerminalKey" => $this->terminal,
            "Amount" => $order['price'] * 100,
            "OrderId" => $order['id'],
            "Description" => 'Оплата заказа №' . $order['id'],
            "Token" => $this->generateToken($tokenArrayData),
            "SuccessURL" => route('tbank.success', $order['id']),
            "Receipt" => [
                "Email" => $order['user_email'],
                "Phone" => $order['user_phone'],
                "Taxation" => "osn",
                "Items" => $items
            ]
        ];

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $ch = curl_init($this->urlInit);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        if (!$this->checkSsl()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Content-Length: " . strlen($jsonData)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            $this->orderData = json_decode($response, true);
        }

        curl_close($ch);
    }

    /**
     * Получает ссылку на оплату из данных заказа
     *
     * @return ?string
     */
    public function paymentLink(): ?string
    {
        return $this->orderData['PaymentURL'] ?? null;
    }

    /**
     * Получает индификатор созданного платежа из данных заказа
     *
     * @return ?string
     */
    public function paymentId(): ?string
    {
        return $this->orderData['PaymentId'] ?? null;
    }

    /**
     * Проверка статуса платежа
     */
    public function paymentCheck(string $paymentId)
    {
        $paymentSuccess = false;

        $tokenArrayData = [
            "Password" => $this->password,
            "PaymentId" => $paymentId,
            "TerminalKey" => $this->terminal,
        ];

        $data = [
            "TerminalKey" => $this->terminal,
            "PaymentId" => $paymentId,
            "Token" => $this->generateToken($tokenArrayData),
        ];

        $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $ch = curl_init($this->urlCheck);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

        if (!$this->checkSsl()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Content-Length: " . strlen($jsonData)
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            $orderData = json_decode($response, true);
            if ($orderData['Status'] == 'CONFIRMED') $paymentSuccess = true;
        }

        curl_close($ch);

        return $paymentSuccess;
    }
}
