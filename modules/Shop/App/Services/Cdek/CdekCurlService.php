<?php

namespace Modules\Shop\App\Services\Cdek;

class CdekCurlService
{
    public string $apiUrl;
    private string $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('services.cdek.CDEK_API_URL');
        $this->apiToken = $this->getToken();
    }

    /**
     * Запрос токена
     *
     * @return string
     */
    private function getToken(): string
    {
        $url = '/v2/oauth/token';
        $clientId = config('services.cdek.CDEK_API_CLIENT_ID');
        $clientSecret = config('services.cdek.CDEK_API_CLIENT_SECRET');

        $data = [
            "grant_type"    => "client_credentials",
            "client_id"     => $clientId,
            "client_secret" => $clientSecret,
        ];

        $responseArray = $this->curlRequest(
            url: $this->apiUrl . $url,
            method: 'POST',
            data: $data,
            token: false
        );

        return $responseArray['access_token'];
    }

    /**
     * Curl запрос
     *
     * @param string $url
     * @param string $method - метод запрос
     * @param array $data - form-data
     * @param bool $token - флаг использования токена
     * @param bool $applicationJson - флаг использования json формат данных в заголовке
     * @return array|null
     */
    public function curlRequest(
        string $url,
        string $method,
        array $data = [],
        bool $token = true,
        bool $applicationJson = false
    ): array|null
    {
        $ch = curl_init();

        curl_setopt(handle: $ch, option: CURLOPT_URL, value: $url);                                                     // Установка URL

        $headers = [];
        if ($applicationJson) {
            $headers[] = 'Content-Type: application/json';
            $data = json_encode($data);
        } else {
            $headers[] = 'Content-Type: application/x-www-form-urlencoded';
            $data = http_build_query($data);
        }
        if ($token) {                                                                                                   // Добавление токена в заголовок
            $headers[] = 'Authorization: Bearer ' . $this->apiToken;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        switch (strtoupper($method)) {
            case 'POST':                                                                                                // Параметры для POST
                curl_setopt(handle: $ch, option: CURLOPT_POST, value: true);
                curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: $data);
                break;
            case 'PUT':                                                                                                 // Параметры для PUT
                curl_setopt(handle: $ch, option: CURLOPT_CUSTOMREQUEST, value: 'PUT');
                curl_setopt(handle: $ch, option: CURLOPT_POSTFIELDS, value: $data);
                break;
            case 'GET':
                if (!empty($data)) {
                    $url .= '?' . $data;
                    curl_setopt(handle: $ch, option: CURLOPT_URL, value:  $url);
                }
                break;
            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (!$this->checkSsl()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo "Ошибка cURL: " . curl_error($ch);
        } else {
            return json_decode($response, true);
        }

        curl_close($ch);

        return null;
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
}
