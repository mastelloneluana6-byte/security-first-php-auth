<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepository;
use App\Security\RateLimiter;
use App\Security\SessionManager;

final class AuthService
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly RateLimiter $rateLimiter,
        private readonly array $securityConfig
    ) {
    }

    public function register(string $email, string $password): array
    {
        $email = strtolower(trim($email));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [false, 'Use a valid email address.'];
        }

        if (strlen($password) < 12) {
            return [false, 'Use a stronger password (12+ characters).'];
        }

        if ($this->users->findByEmail($email)) {
            return [false, 'Account already exists.'];
        }

        $hash = password_hash(
            $password,
            $this->securityConfig['password_algo'],
            $this->securityConfig['password_options']
        );

        if (!$hash) {
            return [false, 'Failed to secure password hash.'];
        }

        $created = $this->users->create($email, $hash);

        return $created
            ? [true, 'Registration successful. Please sign in.']
            : [false, 'Registration failed.'];
    }

    public function login(string $email, string $password, string $ipAddress): array
    {
        if ($this->rateLimiter->isBlocked($ipAddress)) {
            return [false, 'Too many failed attempts. Try again later.'];
        }

        $email = strtolower(trim($email));
        $user = $this->users->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $this->rateLimiter->registerFailure($ipAddress);
            return [false, 'Invalid credentials.'];
        }

        if (password_needs_rehash(
            $user['password_hash'],
            $this->securityConfig['password_algo'],
            $this->securityConfig['password_options']
        )) {
            $rehash = password_hash(
                $password,
                $this->securityConfig['password_algo'],
                $this->securityConfig['password_options']
            );

            if ($rehash) {
                $this->users->updatePasswordHash((int) $user['id'], $rehash);
            }
        }

        $this->rateLimiter->clearFailures($ipAddress);
        SessionManager::regenerateOnLogin();

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['authenticated_at'] = time();

        return [true, 'Login successful.'];
    }

    public function logout(): void
    {
        SessionManager::destroy();
    }
}
