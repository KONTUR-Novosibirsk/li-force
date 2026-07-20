<?php

namespace Modules\Shop\App\Services\DaData;

use Illuminate\Support\Facades\Http;

class DaDataApiService
{
    /**
     * Секретный ключ
     *
     * @var string $secretKey
     */
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('services.da_data.DA_DATA_API_KEY');
    }

    /**
     * Получает список адресов в количестве 10
     *
     * @param string $address
     *
     * @return array
     * @throws
     */
    public function getAddresses(string $address): array
    {
        $response = Http::withHeaders([
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
            'Authorization' => 'Token ' . $this->secretKey,
        ])->post('https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address', [
            'query' => $address,
            'count' => 10
        ]);

        return collect($response->json()['suggestions'])
            ->map(fn ($item) => [
                'value' => $item['value'],
                'label' => $item['value'],
            ])
            ->toArray();
    }
}
