<?php

return [
    'name' => 'OZON',
    'sort' => 1,
    'data' => [
        'ozon_api_key' => [
            'name' => 'Ключ',
            'placeholder' => 'Ключ Ozon',
            'rules' => 'nullable|string|max:500',
        ],
        'ozon_client_id' => [
            'name' => 'Клиент ID',
            'placeholder' => 'Клиент ID',
            'rules' => 'nullable|string|max:250',
        ],
    ],
];
