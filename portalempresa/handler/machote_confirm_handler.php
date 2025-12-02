<?php

declare(strict_types=1);

use Residencia\Controller\Machote\MachoteConfirmController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../../recidencia/controller/machote/MachoteConfirmController.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
$status = null;
$error = null;

if ($machoteId === null || $machoteId === false || $machoteId <= 0) {
    $error = 'invalid';
} elseif ($empresaId <= 0) {
    $error = 'session';
} else {
    try {
        $controller = new MachoteConfirmController();
        $resultado = $controller->confirmarDesdeEmpresa($machoteId, $empresaId);

        if ($resultado['status'] === 'confirmed') {
            $status = 'confirmed';
        } elseif ($resultado['status'] === 'already') {
            $status = 'already';
        } elseif ($resultado['status'] === 'pending') {
            $error = 'pending';
        } elseif ($resultado['status'] === 'locked') {
            $error = 'locked';
        } else {
            $error = 'internal';
        }
    } catch (\Throwable $exception) {
        $error = $error ?? 'internal';
    }
}

$query = http_build_query(array_filter([
    'confirm_status' => $status,
    'confirm_error' => $error,
]));

$redirect = '../view/machote_view.php?id=' . ($machoteId !== null && $machoteId !== false ? (int) $machoteId : 0);
$redirect .= $query !== '' ? '&' . $query : '';

header('Location: ' . $redirect);
exit;
