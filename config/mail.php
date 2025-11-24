<?php

return [
    'default' => env('MAIL_MAILER', 'smtp'),
    
    'mailers' => [
        'smtp' => [
            'transport' => 'smtp',
            'host' => env('MAIL_HOST', 'smtp.gmail.com'),
            'port' => env('MAIL_PORT', 587),
            'encryption' => env('MAIL_ENCRYPTION', 'tls'),
            'username' => env('blackbeaconbv@gmail.com'),
            'password' => env('reupvqirbqmbpupo'),
            'timeout' => null,
            'auth_mode' => null,
        ],
    ],

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'hello@example.com'),
        'name' => env('MAIL_FROM_NAME', 'Example'),
    ],
];

// return [

//     'default' => env('MAIL_MAILER', 'smtp'),

//     'mailers' => [
//     'smtp' => [
//         'transport' => 'smtp',
//         'host' => env('MAIL_HOST', 'smtp.office365.com'),
//         'port' => env('MAIL_PORT', 465),
//         'encryption' => env('MAIL_ENCRYPTION', 'ssl'),
//         'username' => env('MAIL_USERNAME', 'info@kemetic.app'),
//         'password' => env('MAIL_PASSWORD', '@KemeticApp12345!'),
//         'timeout' => null,
//         'local_domain' => null,

//         // Optional: Handle SSL verification issues
//         'stream' => [
//             'ssl' => [
//                 'verify_peer' => true,
//                 'verify_peer_name' => true,
//                 'allow_self_signed' => true,
//             ],
//         ],
//     ],


//         'ses' => [
//             'transport' => 'ses',
//         ],

//         'mailgun' => [
//             'transport' => 'mailgun',
//         ],

//         'postmark' => [
//             'transport' => 'postmark',
//         ],

//         'sendmail' => [
//             'transport' => 'sendmail',
//             'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
//         ],

//         'log' => [
//             'transport' => 'log',
//             'channel' => env('MAIL_LOG_CHANNEL'),
//         ],

//         'array' => [
//             'transport' => 'array',
//         ],

//         'failover' => [
//             'transport' => 'failover',
//             'mailers' => [
//                 'smtp',
//                 'log',
//             ],
//         ],
//     ],

//     'from' => [
//         'address' => env('MAIL_FROM_ADDRESS', 'info@kemetic.app'),
//         'name' => env('MAIL_FROM_NAME', 'Kemetic App'),
//     ],

//     'markdown' => [
//         'theme' => 'default',
//         'paths' => [
//             resource_path('views/vendor/mail'),
//         ],
//     ],

// ];