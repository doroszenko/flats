<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => $_ENV['APP_NAME'] ?? 'Flats Utility Bills Manager',
        'env' => $_ENV['APP_ENV'] ?? 'development',
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    ],
    'gladius' => [
        'db_path' => $_ENV['GLADIUS_DB_PATH'] ?? __DIR__ . '/../db',
    ],
    'session' => [
        'name' => $_ENV['SESSION_NAME'] ?? 'flats_session',
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 3600),
    ],
    'admin' => [
        'username' => $_ENV['ADMIN_USERNAME'] ?? '...',
        'password' => $_ENV['ADMIN_PASSWORD'] ?? '...',
    ],
    'logger' => [
        'level' => $_ENV['LOG_LEVEL'] ?? 'debug',
        'path' => $_ENV['LOG_PATH'] ?? '/../logs',
    ],
    'twig' => [
        'cache' => false, // Disable cache for serverless compatibility
        'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    ],
    'utility_rates' => [
        'electricity' => (float)($_ENV['ELECTRICITY_RATE'] ?? 0.65), // zł/kWh
        'gas' => (float)($_ENV['GAS_RATE'] ?? 2.45), // zł/m³
        'cold_water' => (float)($_ENV['COLD_WATER_RATE'] ?? 4.20), // zł/m³
        'hot_water' => (float)($_ENV['HOT_WATER_RATE'] ?? 18.50), // zł/m³
    ],
];
