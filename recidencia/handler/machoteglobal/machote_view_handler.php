<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';

$controller = new MachoteGlobalController();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

$viewerError = null;
$machote = null;

if ($id <= 0) {
    $viewerError = 'ID de machote no válido.';
} else {
    $machote = $controller->obtenerMachote($id);
    if ($machote === null) {
        $viewerError = 'Machote no encontrado.';
    }
}

include __DIR__ . '/../../view/machoteglobal/machote_view.php';
