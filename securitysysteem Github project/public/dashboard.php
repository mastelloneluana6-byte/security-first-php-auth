<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php', true, 302);
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Authenticated dashboard.">
    <title>Dashboard | Security-First PHP Auth</title>
    <link rel="stylesheet" href="/assets/styles.css">
</head>
<body>
<main class="card">
    <h1>Authenticated Session</h1>
    <p class="message ok">
        Welcome, <?= htmlspecialchars((string) $_SESSION['user_email'], ENT_QUOTES, 'UTF-8') ?>.
    </p>
    <div class="row">
        <a href="/logout.php">Logout</a>
    </div>
</main>
</body>
</html>
