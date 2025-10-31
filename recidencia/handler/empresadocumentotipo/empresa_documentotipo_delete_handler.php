<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_delete.php';
require_once __DIR__ . '/../../controller/empresadocumentotipo/EmpresaDocumentoTipoDeleteController.php';

use Residencia\Controller\Empresadocumentotipo\EmpresaDocumentoTipoDeleteController;

if (!function_exists('empresaDocumentoTipoDeleteHandler')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     documentoId: ?int,
     *     empresa: ?array<string, mixed>,
     *     documento: ?array<string, mixed>,
     *     usageCount: int,
     *     supportsActivo: bool,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string,
     *     errorMessage: ?string,
     *     statusMessage: ?string
     * }
     */
    function empresaDocumentoTipoDeleteHandler(): array
    {
        $viewData = empresaDocumentoTipoDeleteDefaults();

        $empresaId = empresaDocumentoTipoDeleteNormalizeEmpresaId(
            $_GET['id_empresa'] ?? ($_GET['empresa_id'] ?? null)
        );
        $documentoId = empresaDocumentoTipoDeleteNormalizeId(
            $_GET['id'] ?? ($_GET['documento_id'] ?? null)
        );

        $viewData['empresaId'] = $empresaId;
        $viewData['documentoId'] = $documentoId;

        if ($documentoId === null) {
            $viewData['inputError'] = empresaDocumentoTipoDeleteErrorMessageFromCode('invalid_id');

            return $viewData;
        }

        $errorCode = isset($_GET['error']) ? trim((string) $_GET['error']) : '';
        $statusCode = isset($_GET['status']) ? trim((string) $_GET['status']) : '';
        $nombreParam = isset($_GET['nombre']) ? trim((string) $_GET['nombre']) : '';

        if ($errorCode !== '') {
            $viewData['errorMessage'] = empresaDocumentoTipoDeleteErrorMessageFromCode($errorCode);
        }

        try {
            $controller = new EmpresaDocumentoTipoDeleteController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = empresaDocumentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        try {
            $result = $controller->getDocumento($documentoId, $empresaId);
        } catch (RuntimeException $exception) {
            if ($exception->getCode() === 404) {
                $viewData['notFoundMessage'] = empresaDocumentoTipoDeleteNotFoundMessage($documentoId);

                if ($statusCode !== '') {
                    $viewData['statusMessage'] = empresaDocumentoTipoDeleteStatusMessage($statusCode, null, null);
                }

                return $viewData;
            }

            $viewData['controllerError'] = empresaDocumentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = empresaDocumentoTipoDeleteControllerErrorMessage($exception);

            return $viewData;
        }

        $documentoDecorated = empresaDocumentoTipoDeleteDecorateDocumento($result['documento']);
        $empresaDecorated = empresaDocumentoTipoDeleteDecorateEmpresa($result['empresa']);

        if ($empresaId === null && isset($documentoDecorated['empresa_id']) && is_int($documentoDecorated['empresa_id'])) {
            $viewData['empresaId'] = $documentoDecorated['empresa_id'];
        }

        $viewData['documento'] = $documentoDecorated;
        $viewData['empresa'] = $empresaDecorated;
        $viewData['usageCount'] = $result['usageCount'];
        $viewData['supportsActivo'] = $result['supportsActivo'];

        if ($statusCode !== '') {
            $documentoForStatus = $documentoDecorated;

            if ($nombreParam !== '') {
                $documentoForStatus['nombre'] = $nombreParam;
            }

            $viewData['statusMessage'] = empresaDocumentoTipoDeleteStatusMessage(
                $statusCode,
                $documentoForStatus,
                $result['usageCount']
            );
        }

        return $viewData;
    }
}

return empresaDocumentoTipoDeleteHandler();
