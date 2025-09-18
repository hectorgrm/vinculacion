<?php

declare(strict_types=1);

use Common\Model\UserModel;

require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToLogin($_GET['module'] ?? '');
}

$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$module = normalizeModule((string)($_POST['module'] ?? ''));

if ($email === '' || $password === '') {
    redirectToLogin($module, 'missing_fields');
}

try {
    $pdo = require __DIR__ . '/../model/db.php';

    if (!$pdo instanceof PDO) {
        throw new PDOException('No se pudo establecer la conexión a la base de datos.');
    }

    $userModel = new UserModel($pdo);
    $user = $userModel->findActiveByEmail($email);
} catch (PDOException | Throwable $exception) {
    redirectToLogin($module, 'unexpected');
}

if ($user === null || !isPasswordValid($password, $user['password_hash'])) {
    redirectToLogin($module, 'invalid_credentials');
}

if (!isAuthorizedForModule($module, $user['rol'])) {
    redirectToLogin($module, 'unauthorized');
}

session_regenerate_id(true);

$_SESSION['user'] = [
    'id' => (int)$user['id'],
    'name' => $user['nombre'],
    'email' => $user['email'],
    'role' => $user['rol'],
    'module' => $module,
];

$redirectPath = resolveModuleRedirect($module);
if ($redirectPath === null) {
    redirectToLogin($module, 'unexpected');
}

header("Location: {$redirectPath}");
exit;

function redirectToLogin(string $module, string $errorCode = ''): void
{
    $params = [];

    if ($module !== '') {
        $params['module'] = $module;
    }

    if ($errorCode !== '') {
        $params['error'] = $errorCode;
    }

    $query = $params ? ('?' . http_build_query($params)) : '';
    header('Location: ../login.php' . $query);
    exit;
}

function normalizeModule(string $module): string
{
    return strtolower(trim($module));
}

function isAuthorizedForModule(string $module, string $role): bool
{
    $role = strtolower($role);

    $permissions = [
        'serviciosocial' => ['ss_admin', 'admin_ss'],
    ];

    if (!isset($permissions[$module])) {
        return false;
    }

    return in_array($role, $permissions[$module], true);
}

function resolveModuleRedirect(string $module): ?string
{
    switch ($module) {
        case 'serviciosocial':
            return '../../serviciosocial/index.php';
        default:
            return null;
    }
}

function isPasswordValid(string $password, string $storedHash): bool
{
    if ($storedHash === '') {
        return false;
    }

    if (password_verify($password, $storedHash)) {
        return true;
    }

    if (strpos($storedHash, '$2y$2y$') === 0) {
        $normalizedHash = substr($storedHash, 0, 4) . substr($storedHash, 7);
        if (password_verify($password, $normalizedHash)) {
            return true;
        }
    }

    if ($storedHash[0] !== '$') {
        return hash_equals($storedHash, $password);
    }

    return false;
}
