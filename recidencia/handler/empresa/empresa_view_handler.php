<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresa/empresa_functions_view.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaViewController.php';

use Residencia\Controller\Empresa\EmpresaViewController;

if (!function_exists('empresaViewHandler')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     conveniosActivos: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string,
     *     machoteData: ?array<string, mixed>,
     *     successMessage: ?string
     * }
     */
    function empresaViewHandler(): array
    {
        $defaults = empresaViewDefaults();

        try {
            $controller = new EmpresaViewController();
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaViewControllerErrorMessage($exception);

            return $defaults;
        }

        try {
            $result = $controller->handle($_GET);
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaViewControllerErrorMessage($exception);

            return $defaults;
        }

        $successMessage = null;

        if (
            isset($_GET['success_message']) &&
            is_string($_GET['success_message']) &&
            $_GET['success_message'] !== ''
        ) {
            $successMessage = $_GET['success_message'];
        }

        if (!is_array($result)) {
            return $defaults;
        }

        $merged = array_merge($defaults, $result);

        if ($successMessage !== null) {
            $merged['successMessage'] = $successMessage;
        }

        return $merged;
    }
}

return empresaViewHandler();
