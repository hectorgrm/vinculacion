<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_deactivate_helper.php';
require_once __DIR__ . '/../../controller/estudiante/EstudianteDeactivateController.php';
require_once __DIR__ . '/../../model/estudiante/EstudianteDeactivateModel.php';

use Residencia\Controller\Estudiante\EstudianteDeactivateController;
use Residencia\Model\Estudiante\EstudianteDeactivateModel;

if (!function_exists('estudianteDeactivateHandler')) {
    /**
     * @return array{
     *     estudianteId: ?int,
     *     estudiante: ?array<string, mixed>,
     *     empresa: ?array<string, mixed>,
     *     convenio: ?array<string, mixed>,
     *     errors: array<int, string>,
     *     success: bool,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     loadError: ?string
     * }
     */
    function estudianteDeactivateHandler(): array
    {
        $viewData = estudianteDeactivateDefaults();

        $resolved = estudianteDeactivateResolveId($_GET, $_POST);
        $viewData['estudianteId'] = $resolved['estudianteId'];

        if ($resolved['error'] !== null) {
            $viewData['loadError'] = $resolved['error'];
            $viewData['errors'][] = $resolved['error'];

            return $viewData;
        }

        try {
            $controller = new EstudianteDeactivateController(new EstudianteDeactivateModel());
        } catch (\Throwable $exception) {
            $message = estudianteDeactivateControllerErrorMessage($exception);
            $viewData['controllerError'] = $message;
            $viewData['errors'][] = $message;

            return $viewData;
        }

        if ($viewData['estudianteId'] === null) {
            $message = 'No se pudo determinar el estudiante que deseas desactivar.';
            $viewData['loadError'] = $message;
            $viewData['errors'][] = $message;

            return $viewData;
        }

        try {
            $detalle = $controller->obtenerDetalle($viewData['estudianteId']);
        } catch (\Throwable $exception) {
            $message = estudianteDeactivateLoadErrorMessage($exception);
            $viewData['loadError'] = $message;
            $viewData['errors'][] = $message;

            return $viewData;
        }

        if ($detalle === null) {
            $message = estudianteDeactivateRecordNotFoundMessage($viewData['estudianteId']);
            $viewData['loadError'] = $message;
            $viewData['errors'][] = $message;

            return $viewData;
        }

        $viewData['estudiante'] = $detalle['estudiante'];
        $viewData['empresa'] = $detalle['empresa'];
        $viewData['convenio'] = $detalle['convenio'];

        if (!estudianteDeactivateIsPostRequest()) {
            return $viewData;
        }

        if (!estudianteDeactivateIsConfirmed($_POST)) {
            $message = estudianteDeactivateConfirmationErrorMessage();
            $viewData['errors'][] = $message;

            return $viewData;
        }

        if (isset($viewData['estudiante']['estatus'])
            && strtolower((string) $viewData['estudiante']['estatus']) === 'inactivo'
        ) {
            $message = estudianteDeactivateAlreadyInactiveMessage();
            $viewData['errors'][] = $message;

            return $viewData;
        }

        try {
            $controller->desactivar($viewData['estudianteId']);
            $viewData['success'] = true;
            $viewData['successMessage'] = estudianteDeactivateSuccessMessage();
        } catch (\Throwable $exception) {
            $message = estudianteDeactivatePersistenceErrorMessage($exception);
            $viewData['errors'][] = $message;

            return $viewData;
        }

        try {
            $actualizado = $controller->obtenerDetalle($viewData['estudianteId']);

            if ($actualizado !== null) {
                $viewData['estudiante'] = $actualizado['estudiante'];
                $viewData['empresa'] = $actualizado['empresa'];
                $viewData['convenio'] = $actualizado['convenio'];
            }
        } catch (\Throwable $exception) {
            $viewData['loadError'] = estudianteDeactivateLoadErrorMessage($exception);
        }

        return $viewData;
    }
}

return estudianteDeactivateHandler();
