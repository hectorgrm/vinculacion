<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/convenio/ConvenioArchivarController.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_archivar_helper.php';

use Residencia\Controller\Convenio\ConvenioArchivarController;
use function Residencia\Common\Helpers\Convenio\sanitizePositiveInt;

$archivoId = sanitizePositiveInt($_GET['id'] ?? null);
$errors = [];
$archivo = null;
$controllerError = null;

try {
    $controller = new ConvenioArchivarController();
    if ($archivoId > 0) {
        $archivo = $controller->obtenerArchivoPorId($archivoId);
    }
    if ($archivo === null) {
        $errors[] = 'No se encontró el archivo solicitado.';
    }
} catch (\Throwable $throwable) {
    $controllerError = $throwable->getMessage();
    $errors[] = $controllerError !== '' ? $controllerError : 'No se pudo obtener el convenio archivado.';
}

return [
    'errors' => $errors,
    'controllerError' => $controllerError,
    'archivo' => $archivo,
];
