<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_list.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaListController.php';

use Residencia\Controller\Empresa\EmpresaListController;

$defaults = empresaListDefaults();

try {
    $controller = new EmpresaListController();
    $viewData = $controller->handle($_GET);

    return array_merge($defaults, $viewData);
} catch (Throwable $exception) {
    $defaults['errorMessage'] = empresaListErrorMessage($exception->getMessage());

    return $defaults;
}
