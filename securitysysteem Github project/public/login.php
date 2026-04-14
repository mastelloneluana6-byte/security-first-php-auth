<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

use App\Security\Csrf;

if (isset($_SESSION['user_id'])) {
    header('Location: /dashboard.php', true, 302);
    exit;
}

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? null;
    if (!Csrf::validate(is_string($csrfToken) ? $csrfToken : null)) {
        $message = 'Invalid request token.';
        $status = 'danger';
    } else {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        [$ok, $text] = $authService->login(
            (string) ($_POST['email'] ?? ''),
            (string) ($_POST['password'] ?? ''),
            $ipAddress
        );

        if ($ok) {
            header('Location: /dashboard.php', true, 302);
            exit;
        }

        $message = $text;
        $status = 'danger';
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Secure login with brute-force protection and hardened sessions.">
    <title>Login | Security-First PHP Auth</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<main class="card">
    <h1>Login</h1>
    <form method="post" action="/login.php" autocomplete="off" novalidate>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" required maxlength="190">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required maxlength="255">

        <button type="submit">Login</button>
    </form>

    <?php if ($message): ?>
        <p class="message <?= $status === 'ok' ? 'ok' : 'danger' ?>"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <div class="row">
        <a href="/register.php">Create Account</a>
    </div>
</main>
</body>
</html>
