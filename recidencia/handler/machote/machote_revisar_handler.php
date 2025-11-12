<?php
declare(strict_types=1);

use Residencia\Controller\Machote\MachoteRevisarController;

require_once __DIR__ . '/../../controller/machote/MachoteRevisarController.php';

try {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    if ($id <= 0) {
        throw new Exception("ID de machote inválido o no especificado.");
    }

    $controller = new MachoteRevisarController();
    $data = $controller->handle($id);

    // Desestructuramos el resultado
    $machote      = $data['machote'] ?? [];
    $comentarios  = $data['comentarios'] ?? [];
    $empresa      = $data['empresa'] ?? [];
    $convenio     = $data['convenio'] ?? [];
    $progreso     = $data['progreso'] ?? 0;
    $estado       = $data['estado'] ?? 'En revisión';
    $errorMessage = $data['error'] ?? null;

    include __DIR__ . '/../../view/machote/machote_revisar.php';
} catch (Throwable $e) {
    $errorMessage = $e->getMessage();
    include __DIR__ . '/../../view/errors/error_generic.php';
}
