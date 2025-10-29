<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_delete.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoDeleteController.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoDeleteController;

if (!function_exists('documentoTipoDeleteHandler')) {
    /**
     * @return array{
     *     documentoTipoId: ?int,
     *     documentoTipo: ?array<string, mixed>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string,
     *     statusMessage: ?string
     * }
     */
    function documentoTipoDeleteHandler(): array
    {
        $viewData = documentoTipoDeleteDefaults();

        $documentoTipoId = documentoTipoDeleteNormalizeId($_GET['id'] ?? null);
        if ($documentoTipoId === null) {
            $viewData['notFoundMessage'] = 'No se proporciono un identificador valido.';

            return $viewData;
        }

        $viewData['documentoTipoId'] = $documentoTipoId;

        $errorCode = isset($_GET['error']) ? trim((string) $_GET['error']) : '';
        if ($errorCode !== '') {
            $viewData['errorMessage'] = documentoTipoDeleteErrorMessageFromCode($errorCode);
        }

        $statusCode = isset($_GET['status']) ? trim((string) $_GET['status']) : '';

        try {
            $controller = new DocumentoTipoDeleteController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        try {
            $documentoTipo = $controller->getDocumentoTipoById($documentoTipoId);
        } catch (\RuntimeException $exception) {
            if ($exception->getCode() === 404) {
                $viewData['notFoundMessage'] = documentoTipoDeleteNotFoundMessage($documentoTipoId);
                if ($statusCode !== '') {
                    $viewData['statusMessage'] = documentoTipoDeleteStatusMessage($statusCode, null);
                }

                return $viewData;
            }

            $viewData['controllerError'] = documentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        $viewData['documentoTipo'] = documentoTipoDeleteDecorate($documentoTipo);

        if ($statusCode !== '') {
            $viewData['statusMessage'] = documentoTipoDeleteStatusMessage($statusCode, $documentoTipo);
        }

        return $viewData;
    }
}

return documentoTipoDeleteHandler();
