<?php
declare(strict_types=1);

use PortalEmpresa\Controller\PortalEmpresaLoginController;
require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../controller/PortalEmpresaLoginController.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../view/login.php');
    exit;
}

$token = isset($_POST['token']) ? trim((string) $_POST['token']) : '';
$nip = isset($_POST['nip']) ? trim((string) $_POST['nip']) : '';

if ($token === '' || $nip === '') {
    header('Location: ../view/login.php?error=missing');
    exit;
}

$controller = new PortalEmpresaLoginController();

try {
    $result = $controller->authenticate($token, $nip);
} catch (\Throwable $exception) {
    header('Location: ../view/login.php?error=server');
    exit;
}

if ($result['success'] ?? false) {
    session_regenerate_id(true);
    $_SESSION['portal_empresa'] = $result['session'] ?? [];
    header('Location: ../view/index.php');
    exit;
}

$reason = isset($result['reason']) ? (string) $result['reason'] : 'invalid_credentials';

switch ($reason) {
    case 'access_inactive':
    case 'access_expired':
        $errorCode = 'expired';
        break;
    case 'missing_fields':
        $errorCode = 'missing';
        break;
    case 'invalid_credentials':
    default:
        $errorCode = 'invalid';
        break;
}

header('Location: ../view/login.php?error=' . $errorCode);
exit;
