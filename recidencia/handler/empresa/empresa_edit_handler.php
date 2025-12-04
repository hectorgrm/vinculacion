<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/auth.php';
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
            $formOriginal = empresaHydrateForm(empresaFormDefaults(), $empresa);
            $viewData['formData'] = $formOriginal;
            $estatusOriginal = empresaNormalizeStatus($formOriginal['estatus'] ?? null);
        } catch (\RuntimeException $exception) {
            $viewData['loadError'] = $exception->getMessage();

            return $viewData;
        }

        if (!empresaEditIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = empresaEditSanitizeInput($_POST);
        $lockFields = isset($_POST['lock_fields']) && (string) $_POST['lock_fields'] === '1';

        if (
            $lockFields &&
            isset($formOriginal) &&
            isset($estatusOriginal) &&
            in_array($estatusOriginal, ['Completada', 'Inactiva'], true)
        ) {
            foreach ($formOriginal as $field => $value) {
                if ($field === 'estatus') {
                    continue;
                }

                if (!array_key_exists($field, $viewData['formData']) || $viewData['formData'][$field] === '') {
                    $viewData['formData'][$field] = is_string($value) ? $value : (string) $value;
                }
            }
        }
        $viewData['errors'] = empresaEditValidateData($viewData['formData']);

        $estatusNuevo = empresaNormalizeStatus($viewData['formData']['estatus'] ?? null);

        if (
            ($estatusOriginal ?? null) === 'Inactiva' &&
            $estatusNuevo === 'Completada'
        ) {
            $viewData['errors'][] = 'Empresa inactiva no puede completarse.';
        }

        if (
            $viewData['errors'] === [] &&
            empresaStatusRequiresConvenioActivo($viewData['formData']['estatus'] ?? null)
        ) {
            try {
                $tieneConvenioActivo = $controller->empresaHasConvenioActivo((int) $viewData['empresaId']);
            } catch (\RuntimeException $exception) {
                $viewData['errors'][] = $exception->getMessage();
                $tieneConvenioActivo = null;
            }

            if ($tieneConvenioActivo === false && $viewData['errors'] === []) {
                $viewData['errors'][] = 'Para marcar la empresa como Completada debe existir al menos un convenio en estatus Activa.';
            }
        }

        if (
            $viewData['errors'] === [] &&
            empresaStatusRequiresMachoteAprobado($viewData['formData']['estatus'] ?? null)
        ) {
            try {
                $machoteStatus = $controller->getLatestMachoteStatus((int) $viewData['empresaId']);
            } catch (\RuntimeException $exception) {
                $viewData['errors'][] = $exception->getMessage();
                $machoteStatus = null;
            }

            if ($viewData['errors'] === [] && $machoteStatus !== null) {
                $estatusMachote = $machoteStatus['estatus'] ?? null;
                if (!empresaMachoteIsAprobado($estatusMachote)) {
                    $viewData['errors'][] = 'Para marcar la empresa como Completada el machote debe estar en estatus Aprobado.';
                }
            }
        }

        if (
            $viewData['errors'] === [] &&
            empresaStatusRequiresConvenioActivo($viewData['formData']['estatus'] ?? null)
        ) {
            $tipoEmpresa = empresaNormalizeRegimenFiscal($viewData['formData']['regimen_fiscal'] ?? '');

            if ($tipoEmpresa === '' && isset($formOriginal['regimen_fiscal'])) {
                $tipoEmpresa = empresaNormalizeRegimenFiscal($formOriginal['regimen_fiscal']);
            }

            $tipoEmpresa = $tipoEmpresa !== '' ? $tipoEmpresa : null;

            try {
                $documentosStats = $controller->getDocumentosStats((int) $viewData['empresaId'], $tipoEmpresa);
            } catch (\RuntimeException $exception) {
                $viewData['errors'][] = $exception->getMessage();
                $documentosStats = null;
            }

            if (
                $viewData['errors'] === [] &&
                ($documentosStats === null ||
                 $documentosStats['total'] === 0 ||
                 $documentosStats['porcentaje'] < 100)
            ) {
                $viewData['errors'][] = 'Para marcar la empresa como Completada todos los documentos asignados deben estar aprobados (progreso 100%).';
            }
        }

        $detallesAuditoria = isset($formOriginal)
            ? auditoriaBuildCambios($formOriginal, $viewData['formData'], empresaEditAuditFieldLabels())
            : [];

        if ($viewData['errors'] !== []) {
            return $viewData;
        }

        try {
            $controller->updateEmpresa($viewData['empresaId'], $viewData['formData']);
            $viewData['successMessage'] = empresaEditSuccessMessage();

            if ($detallesAuditoria !== []) {
                empresaRegistrarEventoActualizacion($viewData['empresaId'], $detallesAuditoria, empresaCurrentAuditContext());
            }

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
