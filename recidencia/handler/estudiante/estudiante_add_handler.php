<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_add_helper.php';
require_once __DIR__ . '/../../controller/estudiante/EstudianteAddController.php';

use Residencia\Controller\Estudiante\EstudianteAddController;

if (!function_exists('estudianteAddHandler')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, string>>,
     *     convenios: array<int, array<string, string>>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     estatusOptions: array<int, string>
     * }
     */
    function estudianteAddHandler(): array
    {
        $viewData = estudianteAddDefaults();

        try {
            $controller = new EstudianteAddController();
        } catch (\Throwable $exception) {
            $controllerError = estudianteAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (estudianteAddIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        try {
            $viewData['empresas'] = $controller->fetchEmpresas();
            $viewData['convenios'] = $controller->fetchConvenios();
        } catch (\Throwable $exception) {
            $controllerError = estudianteAddControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (estudianteAddIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        if (!estudianteAddIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = estudianteAddSanitizeInput($_POST);
        $viewData['errors'] = estudianteAddValidateData(
            $viewData['formData'],
            $viewData['empresas'],
            $viewData['convenios']
        );

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $estudianteId = $controller->createEstudiante($viewData['formData']);
            $viewData['success'] = true;
            $viewData['successMessage'] = estudianteAddSuccessMessage($estudianteId);
            $viewData['formData'] = estudianteAddFormDefaults();
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
                $duplicateErrors = estudianteAddDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = estudianteAddPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return estudianteAddHandler();
