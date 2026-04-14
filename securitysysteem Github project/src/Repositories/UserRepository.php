<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

final class UserRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $statement = $this->pdo->prepare('SELECT id, email, password_hash FROM users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $user = $statement->fetch();

        return $user ?: null;
    }

    public function create(string $email, string $passwordHash): bool
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO users (email, password_hash, created_at)
             VALUES (:email, :password_hash, NOW())'
        );

        return $statement->execute([
            'email' => $email,
            'password_hash' => $passwordHash,
        ]);
    }

    public function updatePasswordHash(int $userId, string $passwordHash): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE users SET password_hash = :password_hash WHERE id = :id'
        );

        return $statement->execute([
            'id' => $userId,
            'password_hash' => $passwordHash,
        ]);
    }
}
