<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_add_helper.php';
require_once __DIR__ . '/../../controller/estudiante/EstudianteAddController.php';
require_once __DIR__ . '/../../common/functions/auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../common/functions/empresa/empresafunctions_auditoria.php';
require_once __DIR__ . '/../../common/auth.php';

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
            $viewData['formData'] = estudianteAddApplyPrefill(
                $viewData['formData'],
                $_GET,
                $viewData['convenios']
            );

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
            $empresaIdRedirect = isset($viewData['formData']['empresa_id']) && ctype_digit($viewData['formData']['empresa_id'])
                ? (int) $viewData['formData']['empresa_id']
                : null;
            $estudianteId = $controller->createEstudiante($viewData['formData']);
            registrarAuditoriaEstudianteCreado($estudianteId, $viewData['formData']);
            $viewData['success'] = true;
            $viewData['successMessage'] = estudianteAddSuccessMessage($estudianteId);
            $viewData['formData'] = estudianteAddFormDefaults();

            if ($empresaIdRedirect !== null && $empresaIdRedirect > 0) {
                $target = '../empresa/empresa_view.php?id=' . rawurlencode((string) $empresaIdRedirect);

                if (is_string($viewData['successMessage']) && $viewData['successMessage'] !== '') {
                    $target .= '&success_message=' . rawurlencode($viewData['successMessage']);
                }

                header('Location: ' . $target);
                exit;
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

/**
 * @param array<string, string> $formData
 */
function registrarAuditoriaEstudianteCreado(int $estudianteId, array $formData): void
{
    if ($estudianteId <= 0 || !function_exists('auditoriaRegistrarEvento')) {
        return;
    }

    $empresaId = isset($formData['empresa_id']) && ctype_digit($formData['empresa_id'])
        ? (int) $formData['empresa_id']
        : null;

    $detalles = [];

    $nombreCompleto = trim(
        implode(' ', array_filter([
            $formData['nombre'] ?? '',
            $formData['apellido_paterno'] ?? '',
            $formData['apellido_materno'] ?? '',
        ]))
    );

    $nombreValor = auditoriaNormalizeDetalleValue($nombreCompleto !== '' ? $nombreCompleto : ($formData['nombre'] ?? ''));
    if ($nombreValor !== null) {
        $detalles[] = [
            'campo' => 'nombre',
            'campo_label' => 'Nombre del estudiante',
            'valor_anterior' => null,
            'valor_nuevo' => $nombreValor,
        ];
    }

    $matriculaValor = auditoriaNormalizeDetalleValue($formData['matricula'] ?? null);
    if ($matriculaValor !== null) {
        $detalles[] = [
            'campo' => 'matricula',
            'campo_label' => 'Matricula',
            'valor_anterior' => null,
            'valor_nuevo' => $matriculaValor,
        ];
    }

    if ($empresaId !== null) {
        $detalles[] = [
            'campo' => 'empresa_id',
            'campo_label' => 'Empresa vinculada',
            'valor_anterior' => null,
            'valor_nuevo' => (string) $empresaId,
        ];
    }

    $convenioId = isset($formData['convenio_id']) && ctype_digit($formData['convenio_id'])
        ? (int) $formData['convenio_id']
        : null;

    if ($convenioId !== null) {
        $detalles[] = [
            'campo' => 'convenio_id',
            'campo_label' => 'Convenio',
            'valor_anterior' => null,
            'valor_nuevo' => (string) $convenioId,
        ];
    }

    $context = empresaCurrentAuditContext();
    $payload = [
        'accion' => 'crear_estudiante',
        'entidad' => 'rp_estudiante',
        'entidad_id' => $estudianteId,
        'detalles' => $detalles,
    ];

    if (isset($context['actor_tipo'])) {
        $payload['actor_tipo'] = $context['actor_tipo'];
    }

    if (isset($context['actor_id'])) {
        $payload['actor_id'] = $context['actor_id'];
    }

    if (isset($context['ip'])) {
        $payload['ip'] = $context['ip'];
    }

    auditoriaRegistrarEvento($payload);
}

return estudianteAddHandler();
