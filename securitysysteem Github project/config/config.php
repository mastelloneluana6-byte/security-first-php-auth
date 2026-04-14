<?php

declare(strict_types=1);

return [
    'app' => [
        'name' => 'Security-First PHP Authentication',
        'base_url' => getenv('APP_BASE_URL') ?: 'http://localhost',
        'session_name' => getenv('SESSION_NAME') ?: 'secure_auth_session',
        'cookie_secure' => filter_var(getenv('COOKIE_SECURE') ?: 'false', FILTER_VALIDATE_BOOL),
        'cookie_samesite' => getenv('COOKIE_SAMESITE') ?: 'Strict',
    ],
    'database' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: '3306',
        'name' => getenv('DB_NAME') ?: 'secure_auth',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
        'charset' => 'utf8mb4',
    ],
    'security' => [
        'password_algo' => PASSWORD_ARGON2ID,
        'password_options' => [
            'memory_cost' => 1 << 17,
            'time_cost' => 4,
            'threads' => 2,
        ],
        'rate_limit' => [
            'max_attempts' => 5,
            'window_seconds' => 900,
            'block_seconds' => 900,
        ],
    ],
];
