<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'saby' => [
        'SABY_APP_ID' => env('SABY_APP_ID'),
        'SABY_SECURE_KEY' => env('SABY_SECURE_KEY'),
        'SABY_SECRET_KEY' => env('SABY_SECRET_KEY'),
    ],
    'da_data' => ['DA_DATA_API_KEY' => env('DA_DATA_API_KEY')],
    'cdek' => [
        'CDEK_API_URL' => env('CDEK_API_URL'),
        'CDEK_API_CLIENT_ID' => env('CDEK_API_CLIENT_ID'),
        'CDEK_API_CLIENT_SECRET' => env('CDEK_API_CLIENT_SECRET')
    ],
    'tbank' => [
        'TBANK_TERMINAL_ID' => env('TBANK_TERMINAL_ID'),
        'TBANK_PASSWORD' => env('TBANK_PASSWORD')
    ]
];
