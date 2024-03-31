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

    'mistserver' => [
        'push' => [
            'host' => env('MISTSERVER_PUSH_HOST'),
            'username' => env('MISTSERVER_PUSH_USERNAME'),
            'password' => env('MISTSERVER_PUSH_PASSWORD'),
        ],
        'playback' => [
            'host' => env('MISTSERVER_PLAYBACK_HOST'),
            'username' => env('MISTSERVER_PLAYBACK_USERNAME'),
            'password' => env('MISTSERVER_PLAYBACK_PASSWORD'),
        ],
        'vod' => [
            'host' => env('MISTSERVER_VOD_HOST', 'default-vod-host'), // Default as example
            'username' => env('MISTSERVER_VOD_USERNAME', 'default-vod-username'),
            'password' => env('MISTSERVER_VOD_PASSWORD', 'default-vod-password'),
        ],
    ],
];
