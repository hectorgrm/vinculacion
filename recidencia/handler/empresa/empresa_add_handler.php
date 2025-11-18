<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_add.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaAddController.php';
use Residencia\Controller\Empresa\EmpresaAddController;

if (!function_exists('empresaAddHandler')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     estatusOptions: array<int, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string
     * }
     */
    function empresaAddHandler(): array
    {
        $viewData = empresaAddDefaults();

        try {
            $controller = new EmpresaAddController();
        } catch (\Throwable $exception) {
            $controllerError = empresaAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (empresaAddIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        if (!empresaAddIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = empresaAddSanitizeInput($_POST);
        $viewData['errors'] = empresaAddValidateData($viewData['formData']);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        $duplicateErrors = $controller->duplicateFieldErrors($viewData['formData']);

        if ($duplicateErrors !== []) {
            $viewData['errors'] = $duplicateErrors;

            return $viewData;
        }

        try {
            $empresaId = $controller->createEmpresa($viewData['formData']);
            $viewData['successMessage'] = empresaAddSuccessMessage($empresaId);
            $viewData['formData'] = empresaFormDefaults();
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
                $duplicateErrors = empresaAddDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = empresaAddPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return empresaAddHandler();
