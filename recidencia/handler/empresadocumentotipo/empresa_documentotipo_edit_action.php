<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_edit.php';
require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_list.php';
require_once __DIR__ . '/../../controller/empresadocumentotipo/EmpresaDocumentoTipoEditController.php';

use Residencia\Controller\Empresadocumentotipo\EmpresaDocumentoTipoEditController;

if (!function_exists('empresaDocumentoTipoEditAction')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     documentoId: ?int,
     *     empresa: ?array<string, mixed>,
     *     documento: ?array<string, mixed>,
     *     formData: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string,
     *     documentoNotFoundMessage: ?string,
     *     isActivo: bool,
     *     supportsTipoEmpresa: bool,
     *     supportsActivo: bool
     * }
     */
    function empresaDocumentoTipoEditAction(): array
    {
        $viewData = empresaDocumentoTipoEditDefaults();
        $methodIsPost = empresaDocumentoTipoEditIsPostRequest();

        $empresaId = empresaDocumentoTipoAddNormalizeEmpresaId(
            $_GET['id_empresa'] ?? $_GET['empresa_id'] ?? ($_POST['empresa_id'] ?? null)
        );
        $documentoId = empresaDocumentoTipoEditNormalizeId(
            $_GET['id_documento']
                ?? $_GET['documento_id']
                ?? $_GET['id']
                ?? ($_POST['documento_id'] ?? ($_POST['id'] ?? null))
        );

        $viewData['empresaId'] = $empresaId;
        $viewData['documentoId'] = $documentoId;
        $viewData['formData'] = empresaDocumentoTipoEditFormDefaults($empresaId, $documentoId);

        if ($empresaId === null || $documentoId === null) {
            $inputError = empresaDocumentoTipoEditInputErrorMessage();
            $viewData['inputError'] = $inputError;

            if ($methodIsPost) {
                $viewData['errors'][] = $inputError;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        try {
            $controller = new EmpresaDocumentoTipoEditController();
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if ($methodIsPost) {
                $viewData['errors'][] = $controllerError;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        try {
            $empresa = $controller->getEmpresa($empresaId);
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if ($methodIsPost) {
                $viewData['errors'][] = $controllerError;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        if ($empresa === null) {
            $message = empresaDocumentoTipoEditEmpresaNotFoundMessage($empresaId);
            $viewData['notFoundMessage'] = $message;

            if ($methodIsPost) {
                $viewData['errors'][] = $message;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        $empresaDecorated = empresaDocumentoTipoListDecorateEmpresa($empresa);
        $viewData['empresa'] = $empresaDecorated;

        try {
            $documento = $controller->getDocumento($documentoId, $empresaId);
        } catch (\Throwable $exception) {
            $controllerError = empresaDocumentoTipoEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if ($methodIsPost) {
                $viewData['errors'][] = $controllerError;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        if ($documento === null) {
            $message = empresaDocumentoTipoEditDocumentoNotFoundMessage($documentoId);
            $viewData['documentoNotFoundMessage'] = $message;

            if ($methodIsPost) {
                $viewData['errors'][] = $message;
                $viewData['formData'] = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);
            }

            return $viewData;
        }

        $viewData['documento'] = $documento;
        $viewData['supportsTipoEmpresa'] = empresaDocumentoTipoEditRecordSupportsTipoEmpresa($documento);
        $viewData['supportsActivo'] = empresaDocumentoTipoEditRecordSupportsActivo($documento);
        $viewData['formData'] = empresaDocumentoTipoEditHydrateForm(
            empresaDocumentoTipoEditFormDefaults($empresaId, $documentoId),
            $documento
        );
        $viewData['isActivo'] = $viewData['supportsActivo']
            ? empresaDocumentoTipoEditRecordIsActive($documento)
            : true;

        if (!$methodIsPost) {
            return $viewData;
        }

        $sanitized = empresaDocumentoTipoEditSanitizeInput($_POST, $empresaId, $documentoId);

        if (!$viewData['supportsTipoEmpresa']) {
            $sanitized['tipo_empresa'] = 'ambas';
        }

        $viewData['formData'] = $sanitized;
        $viewData['errors'] = empresaDocumentoTipoEditValidateData(
            $sanitized,
            $empresaId,
            $documentoId,
            $viewData['supportsTipoEmpresa']
        );

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        if ($viewData['supportsActivo'] && $viewData['isActivo'] === false) {
            $inactiveMessage = empresaDocumentoTipoEditInactiveUpdateErrorMessage();
            $viewData['errors'][] = $inactiveMessage;

            return $viewData;
        }

        $payload = empresaDocumentoTipoEditPrepareForPersistence($sanitized);

        if (!$viewData['supportsTipoEmpresa']) {
            $payload['tipo_empresa'] = 'ambas';
        }

        try {
            $controller->updateDocumento($payload);
        } catch (\Throwable $exception) {
            $pdoException = null;

            if ($exception instanceof \PDOException) {
                $pdoException = $exception;
            } elseif ($exception instanceof \RuntimeException) {
                $previous = $exception->getPrevious();

                if ($previous instanceof \PDOException) {
                    $pdoException = $previous;
                }
            }

            if ($pdoException instanceof \PDOException) {
                $duplicateErrors = empresaDocumentoTipoEditDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = empresaDocumentoTipoEditPersistenceErrorMessage($exception);

            return $viewData;
        }

        $viewData['successMessage'] = empresaDocumentoTipoEditSuccessMessage($payload['nombre']);

        try {
            $documentoActualizado = $controller->getDocumento($documentoId, $empresaId);

            if ($documentoActualizado !== null) {
                $viewData['documento'] = $documentoActualizado;
                $viewData['supportsTipoEmpresa'] = empresaDocumentoTipoEditRecordSupportsTipoEmpresa($documentoActualizado);
                $viewData['supportsActivo'] = empresaDocumentoTipoEditRecordSupportsActivo($documentoActualizado);
                $viewData['formData'] = empresaDocumentoTipoEditHydrateForm(
                    empresaDocumentoTipoEditFormDefaults($empresaId, $documentoId),
                    $documentoActualizado
                );
                $viewData['isActivo'] = $viewData['supportsActivo']
                    ? empresaDocumentoTipoEditRecordIsActive($documentoActualizado)
                    : true;
            }
        } catch (\Throwable) {
            // Si no se puede recargar, mantenemos los datos anteriores ya actualizados.
        }

        return $viewData;
    }
}

return empresaDocumentoTipoEditAction();
