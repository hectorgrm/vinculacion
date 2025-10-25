<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioListController.php';

use Residencia\Controller\Convenio\ConvenioListController;

$defaults = [
    'search' => '',
    'selectedStatus' => '',
    'statusOptions' => convenioStatusOptions(),
    'convenios' => [],
    'errorMessage' => null,
];

try {
    $controller = new ConvenioListController();
    $viewData = $controller->handle($_GET);

    return array_merge($defaults, $viewData);
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $defaults['errorMessage'] = $message !== ''
        ? $message
        : 'No se pudo obtener la lista de convenios. Intenta nuevamente más tarde.';

    return $defaults;
}

