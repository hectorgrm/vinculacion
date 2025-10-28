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
     *     convenios: array<int, array<string, mixed>>,
     *     tipos: array<int, array<string, mixed>>,
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
        $viewData['tipos'] = $controller->getTipos();
        $viewData['statusOptions'] = $controller->getStatusOptions();

        $selectedEmpresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        if ($selectedEmpresaId !== null) {
            $viewData['convenios'] = $controller->getConvenios($selectedEmpresaId);
        }

        if (!documentoUploadIsPostRequest()) {
            return $viewData;
        }

        $viewData['formData'] = documentoUploadSanitizeInput($_POST);
        $selectedEmpresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        $viewData['convenios'] = $selectedEmpresaId !== null ? $controller->getConvenios($selectedEmpresaId) : [];

        $fileInfo = documentoUploadNormalizeFile($_FILES['archivo'] ?? null);

        $errors = documentoUploadValidateData(
            $viewData['formData'],
            $fileInfo,
            $viewData['statusOptions'],
            static fn (int $empresaId): bool => $controller->empresaExists($empresaId),
            static fn (int $tipoId): bool => $controller->tipoExists($tipoId),
            static fn (int $convenioId, int $empresaId): bool => $controller->convenioBelongsToEmpresa($convenioId, $empresaId)
        );

        if ($errors !== []) {
            $viewData['errors'] = $errors;

            return $viewData;
        }

        $empresaId = documentoNormalizePositiveInt($viewData['formData']['empresa_id'] ?? null);
        $tipoId = documentoNormalizePositiveInt($viewData['formData']['tipo_id'] ?? null);
        $convenioId = documentoNormalizePositiveInt($viewData['formData']['convenio_id'] ?? null);
        $estatus = documentoNormalizeStatus($viewData['formData']['estatus'] ?? null) ?? 'pendiente';

        $observacion = $viewData['formData']['observacion'] !== ''
            ? $viewData['formData']['observacion']
            : null;

        $payload = [
            'empresa_id' => $empresaId,
            'tipo_id' => $tipoId,
            'convenio_id' => $convenioId,
            'estatus' => $estatus,
            'observacion' => $observacion,
        ];

        if (!is_array($fileInfo)) {
            $viewData['errors'][] = 'No se recibio el archivo a cargar.';

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
                $viewData['convenios'] = $controller->getConvenios($empresaId);
            }
        } catch (\Throwable $exception) {
            $viewData['errors'][] = documentoUploadPersistenceErrorMessage($exception);
        }

        return $viewData;
    }
}

return documentoUploadHandler();
