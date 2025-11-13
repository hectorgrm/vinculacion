<?php
declare(strict_types=1);

use PortalEmpresa\Controller\Machote\MachoteComentarioController;

require_once __DIR__ . '/../../common/auth.php';
require_once __DIR__ . '/../../../portalempresa/controller/MachoteComentarioController.php';

$machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
$respuestaA = filter_input(INPUT_POST, 'respuesta_a', FILTER_VALIDATE_INT);
$comentario = trim((string) ($_POST['comentario'] ?? ''));
$usuarioId = isset($_SESSION['usuario']['id']) ? (int) $_SESSION['usuario']['id'] : null;

$redirectBase = '../../view/machote/machote_revisar.php?id=' . ($machoteId !== false && $machoteId !== null ? (int) $machoteId : 0);

if ($machoteId === false || $machoteId === null || $respuestaA === false || $respuestaA === null || $comentario === '') {
    header('Location: ' . $redirectBase . '&comentario_error=invalid');
    exit;
}

$controller = new MachoteComentarioController();
$data = [
    'machote_id' => (int) $machoteId,
    'respuesta_a' => (int) $respuestaA,
    'usuario_id' => $usuarioId,
    'autor_rol' => 'admin',
    'comentario' => $comentario,
    'archivo' => $_FILES['archivo'] ?? null,
];

if ($controller->responderComentario($data)) {
    header('Location: ' . $redirectBase . '&comentario_status=added');
    exit;
}

header('Location: ' . $redirectBase . '&comentario_error=internal');
exit;
