<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_list.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaListController.php';

use Residencia\Controller\Empresa\EmpresaListController;

if (!function_exists('empresaListHandler')) {
    /**
     * @return array{
     *     search: string,
     *     empresas: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    function empresaListHandler(): array
    {
        $defaults = empresaListDefaults();

        try {
            $controller = new EmpresaListController();
            $viewData = $controller->handle($_GET);

            if (!is_array($viewData)) {
                return $defaults;
            }

            return array_merge($defaults, $viewData);
        } catch (Throwable $exception) {
            $defaults['errorMessage'] = empresaListErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$handlerResult = empresaListHandler();

$search = $handlerResult['search'];
$empresas = $handlerResult['empresas'];
$errorMessage = $handlerResult['errorMessage'];

return $handlerResult;
