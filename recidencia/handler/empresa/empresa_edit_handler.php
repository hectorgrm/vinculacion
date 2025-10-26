<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_Edit.php';
require_once __DIR__ . '/../../controller/empresa/EmpresaEditController.php';

use Residencia\Controller\Empresa\EmpresaEditController;

if (!function_exists('empresaEditHandler')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     formData: array<string, string>,
     *     estatusOptions: array<int, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function empresaEditHandler(): array
    {
        $viewData = empresaEditDefaults();

        $resolvedId = empresaEditResolveId($_GET, $_POST);
        $viewData['empresaId'] = $resolvedId['empresaId'];

        if ($resolvedId['error'] !== null) {
            $viewData['loadError'] = $resolvedId['error'];

            return $viewData;
        }

        try {
            $controller = new EmpresaEditController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = empresaEditControllerErrorMessage($exception);

            if (empresaEditIsPostRequest()) {
                $viewData['errors'][] = $viewData['controllerError'];
            }

            return $viewData;
        }

        try {
            $empresa = $controller->getEmpresaById($viewData['empresaId']);
            $viewData['formData'] = empresaHydrateForm(empresaFormDefaults(), $empresa);
        } catch (\RuntimeException $exception) {
            $viewData['loadError'] = $exception->getMessage();

            return $viewData;
        }

        if (!empresaEditIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = empresaEditSanitizeInput($_POST);
        $viewData['errors'] = empresaEditValidateData($viewData['formData']);

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $controller->updateEmpresa($viewData['empresaId'], $viewData['formData']);
            $viewData['successMessage'] = empresaEditSuccessMessage();

            $empresaActualizada = $controller->getEmpresaById($viewData['empresaId']);
            $viewData['formData'] = empresaHydrateForm(empresaFormDefaults(), $empresaActualizada);
        } catch (\RuntimeException $exception) {
            $previous = $exception->getPrevious();

            if ($previous instanceof \PDOException) {
                $duplicateErrors = empresaEditDuplicateErrors($previous);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = empresaEditPersistenceErrorMessage($exception);
        } catch (\Throwable $throwable) {
            $viewData['errors'][] = empresaEditPersistenceErrorMessage($throwable);
        }

        return $viewData;
    }
}

return empresaEditHandler();
