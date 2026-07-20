<?php

namespace Modules\Shop\App\Services\Saby;

abstract class CurlService
{
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
     * POST запрос
     */
    protected function queryPost(array $data, string $url, string $token = ''): array|null
    {
        $data = json_encode($data, JSON_UNESCAPED_UNICODE);

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: '.strlen($data),
                'X-SBISAccessToken: ' . $token,
            ],
        ]);

        if (!$this->checkSsl()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            exit('Curl error: '.curl_error($ch));
        }

        curl_close($ch);

        return json_decode($response, true);
    }

    /**
     * GET запрос
     */
    protected function queryGet(array $data, string $url, string $token, bool $file = false): array|string
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => $url.http_build_query($data),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json; charset=utf-8',
                'X-SBISAccessToken: ' . $token,
            ],
        ]);

        if (!$this->checkSsl()) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            exit(curl_error($ch));
        }

        curl_close($ch);

        if ($file) {
            return $response;
        }

        return json_decode($response, true);
    }
}
