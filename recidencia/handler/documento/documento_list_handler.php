<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunctions_list.php';
require_once __DIR__ . '/../../controller/documento/DocumentoListController.php';

use Residencia\Controller\Documento\DocumentoListController;

$defaults = documentoListDefaults();

try {
    $controller = new DocumentoListController();
    $viewData = $controller->handle($_GET);

    return array_merge($defaults, $viewData);
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $defaults['errorMessage'] = $message !== ''
        ? $message
        : 'No se pudo obtener la lista de documentos. Intenta nuevamente mas tarde.';

    return $defaults;
}
