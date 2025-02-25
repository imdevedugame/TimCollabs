<?php
return [

    'default' => env('BROADCAST_DRIVER', 'null'),

    'connections' => [

        'pusher' => [
            'driver' => 'pusher',

            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'app_id' => env('PUSHER_APP_ID'),

            'options' => [
                // Wajib disesuaikan dengan info Pusher
                'cluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
                'useTLS' => true,

                // Host, port, scheme jika butuh override (opsional)
                'host' => env('PUSHER_HOST', 'api.pusherapp.com'),
                'port' => env('PUSHER_PORT', 443),
                'scheme' => env('PUSHER_SCHEME', 'https'),

                // 'encrypted' => true, // lama, tapi masih bisa dipakai
                // 'curl_options' => [ // jika perlu set curl khusus
                //     CURLOPT_SSL_VERIFYHOST => 0,
                //     CURLOPT_SSL_VERIFYPEER => 0,
                // ],
            ],
        ],

        // koneksi lain (redis, log, null, dll.)
        'redis' => [
            'driver' => 'redis',
            // ...
        ],

        'null' => [
            'driver' => 'null',
        ],

    ],
];

