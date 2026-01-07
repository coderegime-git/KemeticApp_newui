<?php

return [
    'credentials' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
    ],
    'region' => env('AWS_IVS_REGION', 'us-east-1'),
    'version' => 'latest',
    
    // Certificate configuration
    'cert_path' => env('AWS_CERT_PATH', null), // e.g., 'certs/cacert.pem'
    
    'default_channel_config' => [
        'type' => 'STANDARD',
        'authorized' => false,
        'latencyMode' => 'NORMAL',
        'recordingConfigurationArn' => env('AWS_IVS_RECORDING_CONFIG_ARN', null),
        'tags' => [
            'environment' => env('APP_ENV', 'production'),
            'created_by' => 'laravel-system'
        ]
    ]
];