<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

require_once __DIR__ . '/../model/EmpresaDocumentoUploadModel.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../common/functions/empresadocumentofunctions.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../../recidencia/common/functions/documento/documentofunctions_list.php';

use PortalEmpresa\Model\EmpresaDocumentoUploadModel;
use function auditoriaObtenerIP;
use function auditoriaRegistrarEvento;
use function documentoAuditBuildDetalles;
use function documentoAuditFetchSnapshot;

class EmpresaDocumentoUploadController
{
    private EmpresaDocumentoUploadModel $model;

    public function __construct(?EmpresaDocumentoUploadModel $model = null)
    {
        $this->model = $model ?? new EmpresaDocumentoUploadModel();
    }

    /**
     * @param array<string, mixed> $fileData
     * @return array{success: bool, status_code: string, document_name?: string, reason?: string, replaced_path?: ?string, new_path?: string}
     */
    public function processUpload(int $empresaId, string $scope, int $tipoId, array $fileData, ?string $comentario = null): array
    {
        if ($empresaId <= 0 || $tipoId <= 0) {
            return ['success' => false, 'status_code' => 'upload_invalid_tipo'];
        }

        $empresa = $this->model->findEmpresaById($empresaId);

        if ($empresa === null) {
            return ['success' => false, 'status_code' => 'upload_invalid_tipo'];
        }

        $empresaStatus = portalEmpresaNormalizeStatus($empresa['estatus'] ?? '');
        $tipoEmpresa = empresaDocumentoInferTipoEmpresa($empresa['regimen_fiscal'] ?? null);

        $documentSlot = $this->model->findDocumentSlot($empresaId, $scope, $tipoId, $tipoEmpresa);

        if ($documentSlot === null) {
            return ['success' => false, 'status_code' => 'upload_not_assigned'];
        }

        $documentName = (string) ($documentSlot['tipo_nombre'] ?? 'Documento');

        if (portalEmpresaIsReadOnlyStatus($empresaStatus)) {
            return [
                'success' => false,
                'status_code' => 'upload_readonly',
                'document_name' => $documentName,
            ];
        }

        $previousSnapshot = null;
        if (isset($documentSlot['documento_id']) && (int) $documentSlot['documento_id'] > 0) {
            $previousSnapshot = documentoAuditFetchSnapshot((int) $documentSlot['documento_id']);
        }

        $currentStatus = empresaDocumentoNormalizeStatus($documentSlot['documento_estatus'] ?? null);

        if ($currentStatus === 'aprobado') {
            return [
                'success' => false,
                'status_code' => 'upload_not_allowed',
                'document_name' => $documentName,
            ];
        }

        $validation = $this->validateUploadedFile($fileData);

        if (!$validation['valid']) {
            return [
                'success' => false,
                'status_code' => 'upload_file_error',
                'reason' => $validation['reason'] ?? 'invalid_upload',
                'document_name' => $documentName,
            ];
        }

        $extension = $validation['extension'];
        $tmpName = $validation['tmp_name'];

        $absoluteDir = empresaDocumentoUploadAbsoluteDirectory();
        try {
            empresaDocumentoUploadEnsureDirectory($absoluteDir);
        } catch (\Throwable $exception) {
            return [
                'success' => false,
                'status_code' => 'upload_server_error',
                'document_name' => $documentName,
            ];
        }

        $filename = empresaDocumentoUploadGenerateFilename($empresaId, $scope, $tipoId, $extension);
        $absolutePath = rtrim($absoluteDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;
        $relativePath = empresaDocumentoUploadRelativeDirectory() . '/' . $filename;

        if (!move_uploaded_file($tmpName, $absolutePath)) {
            return [
                'success' => false,
                'status_code' => 'upload_file_error',
                'reason' => 'move_failed',
                'document_name' => $documentName,
            ];
        }

        $comentario = trim((string) $comentario);
        $observacion = $comentario !== '' ? $comentario : ($documentSlot['documento_observacion'] ?? null);

        try {
            $saveResult = $this->model->replaceDocumento($empresaId, $scope, $tipoId, $relativePath, $observacion);
        } catch (\Throwable $exception) {
            @unlink($absolutePath);

            return [
                'success' => false,
                'status_code' => 'upload_server_error',
                'document_name' => $documentName,
            ];
        }

        if (!empty($saveResult['replaced_path'])) {
            empresaDocumentoUploadRemoveFile(is_string($saveResult['replaced_path']) ? $saveResult['replaced_path'] : null);
        }

        $this->registrarAuditoriaUpload(
            $saveResult['id'] ?? null,
            $empresaId,
            $previousSnapshot
        );

        return [
            'success' => true,
            'status_code' => 'upload_success',
            'document_name' => $documentName,
            'new_path' => $relativePath,
            'replaced_path' => $saveResult['replaced_path'] ?? null,
        ];
    }

    /**
     * @param array<string, mixed>|null $previousSnapshot
     */
    private function registrarAuditoriaUpload(?int $documentId, int $empresaId, ?array $previousSnapshot): void
    {
        if (!function_exists('auditoriaRegistrarEvento') || $documentId === null || $documentId <= 0) {
            return;
        }

        $snapshot = documentoAuditFetchSnapshot($documentId);
        $detalles = $snapshot !== null
            ? documentoAuditBuildDetalles($snapshot, $previousSnapshot)
            : [];

        $payload = [
            'accion' => 'subir_documento_portal',
            'entidad' => 'rp_empresa_doc',
            'entidad_id' => $documentId,
            'actor_tipo' => 'empresa',
            'actor_id' => $empresaId,
            'detalles' => $detalles,
        ];

        $ip = auditoriaObtenerIP();
        if ($ip !== '') {
            $payload['ip'] = $ip;
        }

        auditoriaRegistrarEvento($payload);
    }

    /**
     * @param array<string, mixed> $fileData
     * @return array{valid: bool, extension?: string, tmp_name?: string, reason?: string}
     */
    private function validateUploadedFile(array $fileData): array
    {
        $error = isset($fileData['error']) ? (int) $fileData['error'] : UPLOAD_ERR_NO_FILE;

        if ($error !== UPLOAD_ERR_OK) {
            $reason = match ($error) {
                UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'too_large',
                UPLOAD_ERR_NO_FILE => 'no_file',
                default => 'invalid_upload',
            };

            return ['valid' => false, 'reason' => $reason];
        }

        $tmpName = (string) ($fileData['tmp_name'] ?? '');

        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['valid' => false, 'reason' => 'invalid_upload'];
        }

        $size = isset($fileData['size']) ? (int) $fileData['size'] : 0;

        if ($size <= 0) {
            return ['valid' => false, 'reason' => 'invalid_upload'];
        }

        if ($size > empresaDocumentoUploadMaxFileSize()) {
            return ['valid' => false, 'reason' => 'too_large'];
        }

        $originalName = (string) ($fileData['name'] ?? '');
        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

        if ($extension === '' || !in_array($extension, empresaDocumentoUploadAllowedExtensions(), true)) {
            return ['valid' => false, 'reason' => 'invalid_type'];
        }

        $detectedMime = null;

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            if ($finfo !== false) {
                $detectedMime = finfo_file($finfo, $tmpName) ?: null;
                finfo_close($finfo);
            }
        }

        if ($detectedMime === 'image/pjpeg') {
            $detectedMime = 'image/jpeg';
        }

        if ($detectedMime === 'image/x-png') {
            $detectedMime = 'image/png';
        }

        if ($detectedMime !== null && !in_array($detectedMime, empresaDocumentoUploadAllowedMimeTypes(), true)) {
            return ['valid' => false, 'reason' => 'invalid_type'];
        }

        return [
            'valid' => true,
            'extension' => $extension,
            'tmp_name' => $tmpName,
        ];
    }
}

