<?php

return [

  /*
  |--------------------------------------------------------------------------
  | Default Broadcaster
  |--------------------------------------------------------------------------
  |
  | This option controls the default broadcaster that will be used by the
  | framework when an event needs to be broadcast. You may set this to
  | any of the connections defined in the "connections" array below.
  |
  | Supported: "pusher", "ably", "redis", "log", "null"
  |
  */

    'default' => env('BROADCAST_DRIVER', 'null'),

  /*
  |--------------------------------------------------------------------------
  | Broadcast Connections
  |--------------------------------------------------------------------------
  |
  | Here you may define all of the broadcast connections that will be used
  | to broadcast events to other systems or over websockets. Samples of
  | each available type of connection are provided inside this array.
  |
  */

    'connections' => [

        'pusher' => [
            'driver'         => 'pusher',
            'app_id'         => env('PUSHER_APP_ID'),
            'key'            => env('PUSHER_APP_KEY'),
            'secret'         => env('PUSHER_APP_SECRET'),
            'options'        => [
                'cluster' => env('PUSHER_APP_CLUSTER'),
//                'encrypted' => env('PUSHER_APP_ENCRYPTED'),
                'useTLS'  => env('PUSHER_APP_TLS'), true,
//                'host' => env('WEBSOCKET_HOST'),
//                'port' => env('WEBSOCKET_PORT'),
//                'scheme' => env('WEBSOCKET_SCHEME')
//                'host' => 'socket.not.tv',
//                'port' => 443,
//                'scheme' => 'https'
//                'host' => '127.0.0.1',
//                'port' => 6001,
//                'scheme' => 'http'

            ],
            'client_options' => [
              // Guzzle client options: https://docs.guzzlephp.org/en/stable/request-options.html
            ],
        ],

        'reverb' => [
            'driver'    => 'reverb',
            'app_id'    => env('REVERB_APP_ID'),
            'key'       => env('REVERB_APP_KEY'),
            'secret'    => env('REVERB_APP_SECRET'),
            'options'   => [
                'host'   => env('REVERB_HOST'),
                'port'   => env('REVERB_PORT'),
                'scheme' => env('REVERB_SCHEME'),
                'tls'    => [
                    'local_cert' => env('REVERB_SERVER_CERT'),
                    'local_pk'   => env('REVERB_SERVER_PK'),
                ],
            ],
            'encrypted' => true,
        ],

        'ably' => [
            'driver' => 'ably',
            'key'    => env('ABLY_KEY'),
        ],

        'redis' => [
            'driver'     => 'redis',
            'connection' => 'default',
        ],

        'log' => [
            'driver' => 'log',
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],

];
