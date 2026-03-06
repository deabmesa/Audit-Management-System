<?php

return [
    'default' => env('DB_CONNECTION', 'pgsql'),

    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'external_pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_EXTERNAL_PG_HOST', '127.0.0.1'),
            'port' => env('DB_EXTERNAL_PG_PORT', '5432'),
            'database' => env('DB_EXTERNAL_PG_DATABASE', 'forge'),
            'username' => env('DB_EXTERNAL_PG_USERNAME', 'forge'),
            'password' => env('DB_EXTERNAL_PG_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
            'read_only' => true,
        ],

        'external_oracle' => [
            'driver' => 'oracle',
            'tns' => '',
            'host' => env('DB_EXTERNAL_ORACLE_HOST', '127.0.0.1'),
            'port' => env('DB_EXTERNAL_ORACLE_PORT', '1521'),
            'database' => env('DB_EXTERNAL_ORACLE_DATABASE', 'XE'),
            'service_name' => env('DB_EXTERNAL_ORACLE_SERVICE', 'ORCLPDB1'),
            'username' => env('DB_EXTERNAL_ORACLE_USERNAME', 'forge'),
            'password' => env('DB_EXTERNAL_ORACLE_PASSWORD', ''),
            'charset' => 'AL32UTF8',
            'prefix' => '',
            'prefix_schema' => '',
            'server_version' => '19c',
            'read_only' => true,
        ],
    ],
];
