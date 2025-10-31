<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunctions_upload.php';
require_once __DIR__ . '/../../controller/documento/DocumentoUploadController.php';

use Residencia\Controller\Documento\DocumentoUploadController;

if (!function_exists('documentoUploadHandler')) {
    /**
     * @return array{
     *     formData: array<string, string>,
     *     empresas: array<int, array<string, mixed>>,
     *     tiposGlobales: array<int, array<string, mixed>>,
     *     tiposPersonalizados: array<int, array<string, mixed>>,
     *     statusOptions: array<string, string>,
     *     errors: array<int, string>,
     *     successMessage: ?string,
     *     controllerError: ?string,
     *     savedDocument: ?array<string, mixed>
     * }
     */
    function documentoUploadHandler(): array
    {
        $viewData = documentoUploadDefaults($_GET ?? []);

        try {
            $controller = new DocumentoUploadController();
        } catch (\Throwable $exception) {
            $viewData['controllerError'] = documentoUploadControllerErrorMessage($exception);

            return $viewData;
        }

        $viewData['empresas'] = $controller->getEmpresas();
        $viewData['tiposGlobales'] = $controller->getTiposGlobales();
        $viewData['statusOptions'] = $controller->getStatusOptions();

        $selectedEmpresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        if ($selectedEmpresaId !== null) {
            $viewData['tiposPersonalizados'] = $controller->getTiposPersonalizados($selectedEmpresaId);
        }

        if (!documentoUploadIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = documentoUploadSanitizeInput($_POST);
        $selectedEmpresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        $viewData['tiposPersonalizados'] = $selectedEmpresaId !== null
            ? $controller->getTiposPersonalizados($selectedEmpresaId)
            : [];

        $fileInfo = documentoUploadNormalizeFile($_FILES['archivo'] ?? null);

        $errors = documentoUploadValidateData(
            $viewData['formData'],
            $fileInfo,
            $viewData['statusOptions'],
            static fn (int $empresaId): bool => $controller->empresaExists($empresaId),
            static fn (int $tipoId): bool => $controller->tipoGlobalExists($tipoId),
            static fn (int $tipoPersonalizadoId, int $empresaId): bool => $controller->tipoPersonalizadoBelongsToEmpresa($tipoPersonalizadoId, $empresaId)
        );

        if ($errors !== []) {
            $viewData['errors'] = $errors;

            return $viewData;
        }

        $empresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        $tipoOrigen = documentoUploadNormalizeOrigen($viewData['formData']['tipo_origen'] ?? null) ?? 'global';
        $tipoGlobalId = documentoNormalizePositiveInt($viewData['formData']['tipo_global_id'] ?? null);
        $tipoPersonalizadoId = documentoNormalizePositiveInt($viewData['formData']['tipo_personalizado_id'] ?? null);
        $estatus = documentoNormalizeStatus($viewData['formData']['estatus'] ?? null) ?? 'pendiente';

        $observacion = $viewData['formData']['observacion'] !== ''
            ? $viewData['formData']['observacion']
            : null;

        $payload = [
            'empresa_id' => $empresaId,
            'tipo_origen' => $tipoOrigen,
            'tipo_global_id' => $tipoOrigen === 'global' ? $tipoGlobalId : null,
            'tipo_personalizado_id' => $tipoOrigen === 'personalizado' ? $tipoPersonalizadoId : null,
            'estatus' => $estatus,
            'observacion' => $observacion,
        ];

        if (!is_array($fileInfo)) {
            $viewData['errors'][] = 'No se recibió el archivo a cargar.';

            return $viewData;
        }

        try {
            $result = $controller->upload($payload, $fileInfo);
            $viewData['successMessage'] = documentoUploadSuccessMessage($result['id']);
            $viewData['savedDocument'] = [
                'id' => $result['id'],
                'ruta' => $result['ruta'],
                'filename' => $result['filename'],
                'originalName' => (string) ($fileInfo['name'] ?? $result['filename']),
            ];

            $viewData['formData'] = documentoUploadFormDefaults();
            if ($empresaId !== null) {
                $viewData['formData']['empresa_id'] = (string) $empresaId;
                $viewData['tiposPersonalizados'] = $controller->getTiposPersonalizados($empresaId);
            }
        } catch (\Throwable $exception) {
            $viewData['errors'][] = documentoUploadPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return documentoUploadHandler();

