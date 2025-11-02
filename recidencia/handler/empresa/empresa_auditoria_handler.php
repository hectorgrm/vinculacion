<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/auditoria/auditoria_functions.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaAuditoriaController.php';

use Residencia\Controller\Empresa\EmpresaAuditoriaController;

if (!function_exists('empresaAuditoriaHandler')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     items: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     inputError: ?string
     * }
     */
    function empresaAuditoriaHandler(): array
    {
        $defaults = empresaAuditoriaDefaults();

        try {
            $controller = new EmpresaAuditoriaController();
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaAuditoriaControllerErrorMessage($exception);

            return $defaults;
        }

        try {
            $result = $controller->handle($_GET);
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaAuditoriaControllerErrorMessage($exception);

            return $defaults;
        }

        if (!is_array($result)) {
            return $defaults;
        }

        return array_merge($defaults, $result);
    }
}

return empresaAuditoriaHandler();
