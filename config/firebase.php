<?php

return [
    'credentials' => [
        'file' => env('FIREBASE_CREDENTIALS'),
        'auto_discovery' => true,
    ],

    'project_id' => env('FIREBASE_PROJECT_ID', 'kemetic-39b07'),

    'messaging' => [
        'sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'default_sender' => 'default',
    ],
];

// declare(strict_types=1);

// return [

//     'default' => env('FIREBASE_PROJECT', 'app'),

//     'projects' => [
//         'app' => [
//             'credentials' => "firebase-auth.json",

//             'auth' => [
//                 'tenant_id' => env('FIREBASE_AUTH_TENANT_ID'),
//             ],

//             'firestore' => ['database' => env('FIREBASE_FIRESTORE_DATABASE'),
//             ],

//             'database' => [

//                 'url' => env('FIREBASE_DATABASE_URL'),

//             ],

//             'dynamic_links' => [

//                 'default_domain' => env('FIREBASE_DYNAMIC_LINKS_DEFAULT_DOMAIN'),
//             ],

//             'storage' => [

//                 'default_bucket' => env('FIREBASE_STORAGE_DEFAULT_BUCKET'),

//             ],

//             'cache_store' => env('FIREBASE_CACHE_STORE', 'file'),

//             'logging' => [
//                 'http_log_channel' => env('FIREBASE_HTTP_LOG_CHANNEL'),
//                 'http_debug_log_channel' => env('FIREBASE_HTTP_DEBUG_LOG_CHANNEL'),
//             ],

//             'http_client_options' => [

//                 'proxy' => env('FIREBASE_HTTP_CLIENT_PROXY'),

//                 'timeout' => env('FIREBASE_HTTP_CLIENT_TIMEOUT'),

//                 'guzzle_middlewares' => [],
//             ],
//         ],
//     ],
// ];
