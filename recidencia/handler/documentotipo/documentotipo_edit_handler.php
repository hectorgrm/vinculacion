<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_edit.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoeditcontroller.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoEditController;

if (!function_exists('documentoTipoEditHandler')) {
    /**
     * @return array{
     *     documentoTipoId: ?int,
     *     formData: array<string, string>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function documentoTipoEditHandler(): array
    {
        $viewData = documentoTipoEditDefaults();

        $resolved = documentoTipoEditResolveId($_GET, $_POST);
        $viewData['documentoTipoId'] = $resolved['documentoTipoId'];

        if ($resolved['error'] !== null) {
            $viewData['loadError'] = $resolved['error'];

            return $viewData;
        }

        try {
            $controller = new DocumentoTipoEditController();
        } catch (\Throwable $exception) {
            $controllerError = documentoTipoEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (documentoTipoEditIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        $documentoTipoId = $viewData['documentoTipoId'];

        if ($documentoTipoId === null) {
            $viewData['loadError'] = 'No se pudo determinar el tipo de documento a editar.';

            return $viewData;
        }

        try {
            $documentoTipo = $controller->getDocumentoTipoById($documentoTipoId);
            $viewData['formData'] = documentoTipoEditHydrateForm(
                documentoTipoEditFormDefaults(),
                $documentoTipo
            );
        } catch (\RuntimeException $exception) {
            $message = trim($exception->getMessage());
            $viewData['loadError'] = $message !== ''
                ? $message
                : documentoTipoEditNotFoundMessage($documentoTipoId);

            return $viewData;
        }

        if (!documentoTipoEditIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = documentoTipoEditSanitizeInput($_POST);
        $viewData['errors'] = documentoTipoEditValidateData($viewData['formData']);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $controller->updateDocumentoTipo($documentoTipoId, $viewData['formData']);
            $viewData['successMessage'] = documentoTipoEditSuccessMessage();

            $actualizado = $controller->getDocumentoTipoById($documentoTipoId);
            $viewData['formData'] = documentoTipoEditHydrateForm(
                documentoTipoEditFormDefaults(),
                $actualizado
            );
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
                $duplicateErrors = documentoTipoEditDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = documentoTipoEditPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return documentoTipoEditHandler();

