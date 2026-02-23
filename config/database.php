<?php

return [
    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'audit_management'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'oracle' => [
            'driver' => 'oracle',
            'tns' => env('DB_ORACLE_TNS', ''),
            'host' => env('DB_ORACLE_HOST', '127.0.0.1'),
            'port' => env('DB_ORACLE_PORT', '1521'),
            'database' => env('DB_ORACLE_DATABASE', 'XE'),
            'service_name' => env('DB_ORACLE_SERVICE_NAME', 'XE'),
            'username' => env('DB_ORACLE_USERNAME', 'readonly_user'),
            'password' => env('DB_ORACLE_PASSWORD', ''),
            'charset' => 'AL32UTF8',
            'prefix' => '',
        ],
    ],

    'migrations' => 'migrations',
];
