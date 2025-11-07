<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_edit_helper.php';
require_once __DIR__ . '/../../controller/estudiante/EstudianteEditController.php';

use Residencia\Controller\Estudiante\EstudianteEditController;

if (!function_exists('estudianteEditHandler')) {
    /**
     * @return array{
     *     estudianteId: ?int,
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, string>>,
     *     convenios: array<int, array<string, string>>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string,
     *     estatusOptions: array<int, string>
     * }
     */
    function estudianteEditHandler(): array
    {
        $viewData = estudianteEditDefaults();

        $resolved = estudianteEditResolveId($_GET, $_POST);
        $viewData['estudianteId'] = $resolved['estudianteId'];

        if ($resolved['error'] !== null) {
            $viewData['loadError'] = $resolved['error'];

            return $viewData;
        }

        try {
            $controller = new EstudianteEditController();
        } catch (\Throwable $exception) {
            $controllerError = estudianteEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (estudianteEditIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        try {
            $viewData['empresas'] = $controller->fetchEmpresas();
            $viewData['convenios'] = $controller->fetchConvenios();
        } catch (\Throwable $exception) {
            $controllerError = estudianteEditControllerErrorMessage($exception);
            $viewData['controllerError'] = $controllerError;

            if (estudianteEditIsPostRequest()) {
                $viewData['errors'][] = $controllerError;
            }

            return $viewData;
        }

        if ($viewData['estudianteId'] === null) {
            $viewData['loadError'] = 'No se pudo determinar el estudiante que deseas editar.';

            return $viewData;
        }

        try {
            $estudiante = $controller->fetchEstudiante($viewData['estudianteId']);
            $viewData['formData'] = estudianteEditHydrateForm(
                estudianteEditFormDefaults(),
                $estudiante
            );
        } catch (\Throwable $exception) {
            $message = estudianteEditControllerErrorMessage($exception);

            if ($viewData['estudianteId'] !== null && stripos($message, 'no existe') !== false) {
                $message = estudianteEditRecordNotFoundMessage($viewData['estudianteId']);
            }

            $viewData['loadError'] = $message;

            return $viewData;
        }

        if (!estudianteEditIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = estudianteEditSanitizeInput($_POST);
        $viewData['errors'] = estudianteEditValidateData(
            $viewData['formData'],
            $viewData['empresas'],
            $viewData['convenios']
        );

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        if ($viewData['estudianteId'] === null) {
            $viewData['errors'][] = 'No se pudo identificar el registro a actualizar.';

            return $viewData;
        }

        try {
            $controller->updateEstudiante($viewData['estudianteId'], $viewData['formData']);
            $viewData['success'] = true;
            $viewData['successMessage'] = estudianteEditSuccessMessage();

            try {
                $actualizado = $controller->fetchEstudiante($viewData['estudianteId']);
                $viewData['formData'] = estudianteEditHydrateForm(
                    estudianteEditFormDefaults(),
                    $actualizado
                );
            } catch (\Throwable $exception) {
                $message = estudianteEditControllerErrorMessage($exception);

                if ($viewData['estudianteId'] !== null && stripos($message, 'no existe') !== false) {
                    $message = estudianteEditRecordNotFoundMessage($viewData['estudianteId']);
                }

                $viewData['loadError'] = $message;
            }
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
                $duplicateErrors = estudianteEditDuplicateErrors($pdoException);

                if ($duplicateErrors !== []) {
                    $viewData['errors'] = $duplicateErrors;

                    return $viewData;
                }
            }

            $viewData['errors'][] = estudianteEditPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return estudianteEditHandler();
