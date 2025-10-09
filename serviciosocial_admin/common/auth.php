<?php
// ✅ Validación global de sesión y rol

require_once __DIR__ . '/../../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../common/login.php?module=serviciosocial_admin&error=unauthorized');
    exit;
}

$userName = htmlspecialchars((string)($user['name'] ?? 'Coordinador'), ENT_QUOTES, 'UTF-8');
