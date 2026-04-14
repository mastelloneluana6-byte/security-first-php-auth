<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/bootstrap.php';

$authService->logout();

header('Location: /login.php', true, 302);
exit;
