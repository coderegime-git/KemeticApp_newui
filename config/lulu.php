<?php

return [
    'sandbox' => env('LULU_SANDBOX', true),
    'client_key' => env('LULU_CLIENT_KEY'),
    'client_secret' => env('LULU_CLIENT_SECRET'),
    'contact_email' => env('LULU_CONTACT_EMAIL'),
    
    'api_urls' => [
        'production' => 'https://api.lulu.com',
        'sandbox' => 'https://api.sandbox.lulu.com',
        'auth' => [
            'production' => 'https://api.lulu.com/auth/realms/glasstree/protocol/openid-connect/token',
            'sandbox' => 'https://api.sandbox.lulu.com/auth/realms/glasstree/protocol/openid-connect/token'
        ]
    ],
    
    'default_shipping_level' => 'MAIL',
    'default_timeout' => 30,
];