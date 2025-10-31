<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_list.php';
require_once __DIR__ . '/../../controller/empresadocumentotipo/EmpresaDocumentoTipoListController.php';

use Residencia\Controller\Empresadocumentotipo\EmpresaDocumentoTipoListController;

if (!function_exists('empresaDocumentoTipoListHandler')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     globalDocuments: array<int, array<string, mixed>>,
     *     customDocuments: array<int, array<string, mixed>>,
     *     stats: array<string, int>,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function empresaDocumentoTipoListHandler(): array
    {
        $defaults = empresaDocumentoTipoListDefaults();
        $statusCode = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
        $nombreParam = isset($_GET['nombre']) ? trim((string) $_GET['nombre']) : '';
        $usageParam = $_GET['usage'] ?? null;
        $usageCount = null;
        if ($usageParam !== null && is_scalar($usageParam) && is_numeric($usageParam)) {
            $usageCount = (int) $usageParam;
        }

        $statusMessage = empresaDocumentoTipoListStatusMessage(
            $statusCode,
            $nombreParam !== '' ? $nombreParam : null,
            $usageCount
        );
        $defaults['statusMessage'] = $statusMessage;

        try {
            $controller = new EmpresaDocumentoTipoListController();
            $result = $controller->handle($_GET);
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaDocumentoTipoListControllerErrorMessage($exception);
            $defaults['statusMessage'] = $statusMessage;

            return $defaults;
        }

        if (!is_array($result)) {
            $defaults['statusMessage'] = $statusMessage;

            return $defaults;
        }

        $merged = array_merge($defaults, $result);
        $merged['statusMessage'] = $statusMessage;

        return $merged;
    }
}

return empresaDocumentoTipoListHandler();
