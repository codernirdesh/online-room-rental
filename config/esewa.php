<?php

return [

    /*
    |--------------------------------------------------------------------------
    | eSewa Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your eSewa merchant credentials and environment settings.
    | Set ESEWA_ENVIRONMENT to 'production' when going live.
    |
    */

    'merchant_code' => env('ESEWA_MERCHANT_CODE', 'EPAYTEST'),

    'environment' => env('ESEWA_ENVIRONMENT', 'testing'), // 'testing' or 'production'

    'urls' => [
        'testing' => 'https://rc-epay.esewa.com.np',
        'production' => 'https://epay.esewa.com.np',
    ],

    'success_url' => env('ESEWA_SUCCESS_URL', '/payment/esewa/success'),
    'failure_url' => env('ESEWA_FAILURE_URL', '/payment/esewa/failure'),

];
