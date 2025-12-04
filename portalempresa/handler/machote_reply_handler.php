<?php
declare(strict_types=1);

use PortalEmpresa\Controller\Machote\MachoteComentarioController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/MachoteComentarioController.php';

$portalSession = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($portalSession['empresa_id'] ?? 0);
$portalReadOnly = portalEmpresaIsReadOnly($portalSession);

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
$respuestaA = filter_input(INPUT_POST, 'respuesta_a', FILTER_VALIDATE_INT);
$comentario = trim((string) ($_POST['comentario'] ?? ''));

$redirectBase = '../view/machote_view.php?id=' . ($machoteId !== false && $machoteId !== null ? (int) $machoteId : 0);

if ($portalReadOnly) {
    header('Location: ' . $redirectBase . '&comentario_error=readonly');
    exit;
}

if ($empresaId <= 0 || $machoteId === false || $machoteId === null || $respuestaA === false || $respuestaA === null || $comentario === '') {
    header('Location: ' . $redirectBase . '&comentario_error=invalid');
    exit;
}

$controller = new MachoteComentarioController();
$data = [
    'machote_id' => (int) $machoteId,
    'respuesta_a' => (int) $respuestaA,
    'usuario_id' => null,
    'autor_rol' => 'empresa',
    'comentario' => $comentario,
    'archivo' => $_FILES['archivo'] ?? null,
];

if ($controller->responderComentario($data)) {
    header('Location: ' . $redirectBase . '&comentario_status=added');
    exit;
}

header('Location: ' . $redirectBase . '&comentario_error=internal');
exit;
