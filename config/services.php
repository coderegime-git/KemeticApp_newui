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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
    ],
    'facebook' => [
        'client_id' => env('FACEBOOK_APP_ID'),
        'client_secret' => env('FACEBOOK_APP_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'paytm-wallet' => [
        'env' => env('PAYTM_ENVIRONMENT'), // values : (local | production)
        'merchant_id' => env('PAYTM_MERCHANT_ID'),
        'merchant_key' => env('PAYTM_MERCHANT_KEY'),
        'merchant_website' => env('PAYTM_MERCHANT_WEBSITE'),
        'channel' => env('PAYTM_CHANNEL'),
        'industry_type' => env('PAYTM_INDUSTRY_TYPE'),
    ],

    // 'lulu' => [
    //     'base_url' => "https://api.sandbox.lulu.com",
    //     'client_key' => "9f605b15-6c3c-49e5-919b-84f7341a2283",
    //     'client_secret' => "20aiFIjqs1ZnCRFBkcbRLIxUUX83ogIp",
    //     'base64' => "Basic OWY2MDViMTUtNmMzYy00OWU1LTkxOWItODRmNzM0MWEyMjgzOjIwYWlGSWpxczFabkNSRkJrY2JSTEl4VVVYODNvZ0lw",
    // ],

    // 'lulu' => [
    //     'base_url' => env('LULU_BASE_URL', 'https://api.sandbox.lulu.com'),
    //     'client_key' => env('LULU_CLIENT_KEY'),
    //     'client_secret' => env('LULU_CLIENT_SECRET'),
    //     'base64' => env('LULU_BASE64_AUTH'),
    // ],

    // SMS Channel
    "msg91" => [
        'key' => '', // set from Channel
    ],

    'aws' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
    ],

];
