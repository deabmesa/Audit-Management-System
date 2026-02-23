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
            'tns' => '',
            'host' => env('ORACLE_DB_HOST', '127.0.0.1'),
            'port' => env('ORACLE_DB_PORT', '1521'),
            'database' => env('ORACLE_DB_DATABASE', ''),
            'service_name' => env('ORACLE_DB_SERVICE_NAME', 'orcl'),
            'username' => env('ORACLE_DB_USERNAME', ''),
            'password' => env('ORACLE_DB_PASSWORD', ''),
            'charset' => 'AL32UTF8',
            'prefix' => '',
            'prefix_schema' => '',
            'edition' => 'ora$base',
            'server_version' => '19c',
            'load_balance' => 'yes',
            'max_name_len' => 30,
            'dynamic' => [],
        ],
    ],

    'migrations' => 'migrations',
];
