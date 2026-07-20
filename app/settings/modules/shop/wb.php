<?php

return [
    'name' => 'Wildberries',
    'sort' => 2,
    'data' => [
        'wb_api_key' => [
            'name' => 'Ключ',
            'placeholder' => 'Ключ Wildberries',
            'type' => 'textarea',
            'rules' => 'nullable|string|max:1000',
        ],
    ],
];
