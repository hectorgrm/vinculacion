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

        try {
            $controller = new EmpresaDocumentoTipoListController();
            $result = $controller->handle($_GET);
        } catch (\Throwable $exception) {
            $defaults['controllerError'] = empresaDocumentoTipoListControllerErrorMessage($exception);

            return $defaults;
        }

        if (!is_array($result)) {
            return $defaults;
        }

        return array_merge($defaults, $result);
    }
}

return empresaDocumentoTipoListHandler();
