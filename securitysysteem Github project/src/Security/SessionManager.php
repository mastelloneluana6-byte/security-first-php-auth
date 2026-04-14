<?php

declare(strict_types=1);

namespace App\Security;

final class SessionManager
{
    public static function start(array $appConfig): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        session_name($appConfig['session_name']);
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $appConfig['cookie_secure'],
            'httponly' => true,
            'samesite' => $appConfig['cookie_samesite'],
        ]);

        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_httponly', '1');

        session_start();
    }

    public static function regenerateOnLogin(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                [
                    'expires' => time() - 42000,
                    'path' => $params['path'],
                    'domain' => $params['domain'],
                    'secure' => (bool) $params['secure'],
                    'httponly' => (bool) $params['httponly'],
                    'samesite' => $params['samesite'] ?? 'Strict',
                ]
            );
        }

        session_destroy();
    }
}
