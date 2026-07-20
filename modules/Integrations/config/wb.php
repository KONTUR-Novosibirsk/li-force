<?php

return [
    'api' => [
        'feedbacks' => [
            'request_timeout' => 10,
            'url' => 'https://feedbacks-api.wildberries.ru',
            'key' => settings('wb_api_key', 'shop') ?? env('WB_API_KEY') ?? '',
        ],
    ],
];
