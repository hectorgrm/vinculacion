<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_funtions_list.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoListController.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoListController;

$defaults = documentoTipoListDefaults();
$statusCode = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
$nombreParam = isset($_GET['nombre']) ? trim((string) $_GET['nombre']) : '';
$statusMessage = documentoTipoListStatusMessage(
    $statusCode,
    $nombreParam !== '' ? $nombreParam : null
);

try {
    $controller = new DocumentoTipoListController();
    $viewData = $controller->handle($_GET);

    $merged = array_merge($defaults, $viewData);
    $merged['statusMessage'] = $statusMessage;

    return $merged;
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $defaults['errorMessage'] = $message !== ''
        ? $message
        : 'No se pudo obtener la lista de tipos de documentos. Intenta nuevamente mas tarde.';
    $defaults['statusMessage'] = $statusMessage;

    return $defaults;
}
