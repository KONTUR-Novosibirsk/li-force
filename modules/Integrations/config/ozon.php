<?php

return [
    'api' => [
        'seller' => [
            'request_timeout' => 10,
            'url' => 'https://api-seller.ozon.ru',
            'key' => settings('ozon_api_key', 'shop') ?? env('OZON_API_KEY') ?? '',
            'client_id' => settings('ozon_client_id', 'shop') ?? env('OZON_API_CLIENT_ID') ?? '',
        ],
    ],
];
