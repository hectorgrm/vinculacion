<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_funtions_list.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoListController.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoListController;

$defaults = documentoTipoListDefaults();

try {
    $controller = new DocumentoTipoListController();
    $viewData = $controller->handle($_GET);

    return array_merge($defaults, $viewData);
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $defaults['errorMessage'] = $message !== ''
        ? $message
        : 'No se pudo obtener la lista de tipos de documentos. Intenta nuevamente mas tarde.';

    return $defaults;
}
