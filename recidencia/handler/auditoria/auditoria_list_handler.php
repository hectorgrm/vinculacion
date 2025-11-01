<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../controller/auditoria/AuditoriaListController.php';

use Residencia\Controller\Auditoria\AuditoriaListController;

$defaults = auditoriaListDefaults();

try {
    $controller = new AuditoriaListController();
    $viewData = $controller->handle($_GET);

    return array_merge($defaults, $viewData);
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $defaults['errorMessage'] = $message !== ''
        ? $message
        : 'No se pudo obtener el historial de auditoría. Intenta nuevamente más tarde.';

    return $defaults;
}
