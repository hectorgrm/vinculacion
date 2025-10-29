<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_add.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoAddController.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoAddController;

if (!function_exists('documentoTipoAddHandler')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     tipoEmpresaOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function documentoTipoAddHandler(): array
    {
        $viewData = documentoTipoAddDefaults();

        try {
            $controller = new DocumentoTipoAddController();
        } catch (\Throwable $exception) {
            $controllerError = documentoTipoAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (documentoTipoAddIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        if (!documentoTipoAddIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = documentoTipoAddSanitizeInput($_POST);
        $viewData['errors'] = documentoTipoAddValidateData($viewData['formData']);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $documentoTipoId = $controller->createDocumentoTipo($viewData['formData']);
            $viewData['successMessage'] = documentoTipoAddSuccessMessage($documentoTipoId);
            $viewData['formData'] = documentoTipoAddFormDefaults();
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
                $duplicateErrors = documentoTipoAddDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = documentoTipoAddPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return documentoTipoAddHandler();

