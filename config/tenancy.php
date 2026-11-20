<?php

return [
    'db_prefix' => env('TENANT_DB_PREFIX', 'doctor_customer_'),
    'db_host' => env('TENANT_DB_HOST', '127.0.0.1'),
    'db_port' => env('TENANT_DB_PORT', 3306),
    'db_username' => env('TENANT_DB_USERNAME', 'root'),
    'db_password' => env('TENANT_DB_PASSWORD', ''),
    'migrations_path' => database_path('migrations/tenant'),
    'reminder_days' => [7, 3, 1],
];
