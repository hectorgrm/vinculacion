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

        try {
            $empresaId = $controller->create($viewData['formData']);
            $viewData['successMessage'] = empresaAddSuccessMessage($empresaId);
            $viewData['formData'] = empresaFormDefaults();
        } catch (\Throwable $exception) {
            if ($exception instanceof \PDOException) {
                $duplicateErrors = empresaAddDuplicateErrors($exception);

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
