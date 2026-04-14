<?php

declare(strict_types=1);

namespace App\Security;

use PDO;

final class RateLimiter
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly array $rateLimitConfig
    ) {
    }

    public function isBlocked(string $ipAddress): bool
    {
        $statement = $this->pdo->prepare(
            'SELECT blocked_until
             FROM failed_login_attempts
             WHERE ip_address = :ip
             ORDER BY blocked_until DESC
             LIMIT 1'
        );
        $statement->execute(['ip' => $ipAddress]);

        $row = $statement->fetch();
        if (!$row || !$row['blocked_until']) {
            return false;
        }

        return strtotime($row['blocked_until']) > time();
    }

    public function registerFailure(string $ipAddress): void
    {
        $windowStart = date('Y-m-d H:i:s', time() - $this->rateLimitConfig['window_seconds']);

        $countStatement = $this->pdo->prepare(
            'SELECT COUNT(*) AS attempts
             FROM failed_login_attempts
             WHERE ip_address = :ip
             AND attempted_at >= :window_start'
        );
        $countStatement->execute([
            'ip' => $ipAddress,
            'window_start' => $windowStart,
        ]);

        $attempts = (int) $countStatement->fetch()['attempts'];
        $blockedUntil = null;

        if (($attempts + 1) >= $this->rateLimitConfig['max_attempts']) {
            $blockedUntil = date('Y-m-d H:i:s', time() + $this->rateLimitConfig['block_seconds']);
        }

        $insertStatement = $this->pdo->prepare(
            'INSERT INTO failed_login_attempts (ip_address, attempted_at, blocked_until)
             VALUES (:ip, NOW(), :blocked_until)'
        );
        $insertStatement->execute([
            'ip' => $ipAddress,
            'blocked_until' => $blockedUntil,
        ]);
    }

    public function clearFailures(string $ipAddress): void
    {
        $statement = $this->pdo->prepare('DELETE FROM failed_login_attempts WHERE ip_address = :ip');
        $statement->execute(['ip' => $ipAddress]);
    }
}
