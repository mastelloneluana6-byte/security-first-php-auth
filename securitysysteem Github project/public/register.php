<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use App\Security\Csrf;

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;
    if (!Csrf::validate(is_string($csrfToken) ? $csrfToken : null)) {
        $message = 'Invalid request token.';
        $status = 'danger';
    } else {
        [$ok, $text] = $authService->register(
            (string) ($_POST['email'] ?? ''),
            (string) ($_POST['password'] ?? '')
        );
        $message = $text;
        $status = $ok ? 'ok' : 'danger';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Secure registration with Argon2id and PDO prepared statements.">
    <title>Register | Security-First PHP Auth</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<main class="card">
    <h1>Create Account</h1>
    <form method="post" action="/register.php" autocomplete="off" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required maxlength="190">

        <label for="password">Password (12+ chars)</label>
        <input id="password" name="password" type="password" required minlength="12" maxlength="255">

        <button type="submit">Register</button>
    </form>

    <?php if ($message): ?>
        <p class="message <?= $status === 'ok' ? 'ok' : 'danger' ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <div class="row">
        <a href="/login.php">Go to Login</a>
    </div>
</main>
</body>
</html>
