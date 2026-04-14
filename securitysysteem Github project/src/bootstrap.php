<?php

declare(strict_types=1);

use App\Database;
use App\Repositories\UserRepository;
use App\Security\RateLimiter;
use App\Security\SessionManager;
use App\Services\AuthService;
use App\Support\Env;

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    $baseDir = __DIR__ . DIRECTORY_SEPARATOR;

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});

Env::load(__DIR__ . '/../.env');
$config = require __DIR__ . '/../config/config.php';
SessionManager::start($config['app']);

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

$pdo = Database::connection($config['database']);
$userRepository = new UserRepository($pdo);
$rateLimiter = new RateLimiter($pdo, $config['security']['rate_limit']);
$authService = new AuthService($userRepository, $rateLimiter, $config['security']);
