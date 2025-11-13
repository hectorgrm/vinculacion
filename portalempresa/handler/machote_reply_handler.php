<?php
use Residencia\Controller\Machote\MachoteComentarioController;

require_once __DIR__ . '/../../controller/machote/MachoteComentarioController.php';

$controller = new MachoteComentarioController();

$data = [
    'machote_id' => (int)$_POST['machote_id'],
    'respuesta_a' => (int)$_POST['respuesta_a'],
    'usuario_id' => $_SESSION['usuario_id'] ?? null,
    'autor_rol' => 'admin',
    'comentario' => trim($_POST['comentario']),
    'archivo' => $_FILES['archivo'] ?? null,
];

if ($controller->responderComentario($data)) {
    header("Location: ../../view/machote/machote_revisar.php?id=" . $data['machote_id'] . "&comentario_status=added");
} else {
    header("Location: ../../view/machote/machote_revisar.php?id=" . $data['machote_id'] . "&comentario_status=error");
}
exit;
