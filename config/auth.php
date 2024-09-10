<?php
    return [

        'defaults' => [
            'guard' => 'admin',
            'passwords' => 'users',
        ],

        'guards' => [
            'admin' => [
                'driver' => 'jwt',
                'provider' => 'admins',
            ],
            'worker' => [
                'driver' => 'jwt',
                'provider' => 'workers',
            ],
            'client' => [
                'driver' => 'jwt',
                'provider' => 'clients',
            ],
        ],

        'providers' => [
            'admins' => [
                'driver' => 'eloquent',
                'model' => \App\Models\Admin::class,
            ],
            'workers' => [
                'driver' => 'eloquent',
                'model' => \App\Models\Worker::class,
            ],
            'clients' => [
                'driver' => 'eloquent',
                'model' => \App\Models\Client::class,
            ],
        ],

        'passwords' => [
            'users' => [
                'provider' => 'users',
                'table' => 'password_reset_tokens',
                'expire' => 60,
                'throttle' => 60,
            ],
        ],

        'password_timeout' => 10800,

    ];
