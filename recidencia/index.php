<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin'];

if (!is_array($user) || !in_array(strtolower((string) ($user['role'] ?? '')), $allowedRoles, true)) {
  header('Location: ../common/login.php?module=residencias&error=unauthorized');
  exit;
}

// Redirige al dashboard principal para evitar anidar HTML completo dentro de index.php.
$dashboardUrl = 'view/dashboard/dashboard.php';
header('Location: ' . $dashboardUrl);
exit;
