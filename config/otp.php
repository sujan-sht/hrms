<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    |
    | true / false
    */
    'status' => env('OTP_STATUS', true),

    'middleware' => ['web', 'auth'],

    'user' => [
        'model' => \App\Modules\User\Entities\User::class,
    ],

    'defaults' => [
        'expire' => 30,
        'length' => 6
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Channels
    |--------------------------------------------------------------------------
    |
    | Available Channels
    | - mail
    | - sms
    |
    */
    'enable' => [
        'mail' => env('OTP_MAIL', true),
        'sms' => env('OTP_SMS', true),
    ],

    'notification' => [
        'sparrow' => [
            'loggable' => true,
            # base_url should end with trailing backslash `/`
            'base_url' => env('SPARROW_BASE_URL', 'http://api.sparrowsms.com/v2/'),
            'token'    => env('SPARROW_TOKEN', 'XXXXXXXX'), # `auth token` provided by sparrow sms
            'identity' => env('SPARROW_IDENTITY', 'XXXXXXXX'), # `identity` provided by sparrow sms

            # Available Apis
            'apis' => [
                'send' => 'sms/',
                'credit' => 'credit/'
            ],
        ]
    ],

    'routes' => [
        'success' => 'dashboard',
        'show' => 'otp.show',
        'login' => 'login'
    ]

];
