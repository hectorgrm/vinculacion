<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin', 'ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../common/login.php?module=recidencia&error=unauthorized');
    exit;
}

$residenciaAuthUser = [
    'id' => (int) ($user['id'] ?? 0),
    'name' => (string) ($user['name'] ?? ''),
    'role' => (string) ($user['role'] ?? ''),
];

$user = $residenciaAuthUser;

