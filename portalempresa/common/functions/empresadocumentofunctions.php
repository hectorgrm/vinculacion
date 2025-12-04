<?php

declare(strict_types=1);

require_once __DIR__ . '/../../helpers/empresadocumentofunction.php';

if (!function_exists('empresaDocumentoUploadAllowedExtensions')) {
    /**
     * @return array<int, string>
     */
    function empresaDocumentoUploadAllowedExtensions(): array
    {
        return ['pdf', 'jpg', 'jpeg', 'png'];
    }
}

if (!function_exists('empresaDocumentoUploadAllowedMimeTypes')) {
    /**
     * @return array<int, string>
     */
    function empresaDocumentoUploadAllowedMimeTypes(): array
    {
        return ['application/pdf', 'image/jpeg', 'image/png'];
    }
}

if (!function_exists('empresaDocumentoUploadMaxFileSize')) {
    function empresaDocumentoUploadMaxFileSize(): int
    {
        return 10 * 1024 * 1024; // 10 MB
    }
}

if (!function_exists('empresaDocumentoUploadRelativeDirectory')) {
    function empresaDocumentoUploadRelativeDirectory(): string
    {
        return 'uploads/convenios';
    }
}

if (!function_exists('empresaDocumentoUploadAbsoluteDirectory')) {
    function empresaDocumentoUploadAbsoluteDirectory(): string
    {
        $projectRoot = dirname(__DIR__, 3);

        return $projectRoot . DIRECTORY_SEPARATOR . 'recidencia' . DIRECTORY_SEPARATOR . empresaDocumentoUploadRelativeDirectory();
    }
}

if (!function_exists('empresaDocumentoUploadEnsureDirectory')) {
    function empresaDocumentoUploadEnsureDirectory(string $directory): void
    {
        if (is_dir($directory)) {
            return;
        }

        if (!mkdir($directory, 0775, true) && !is_dir($directory)) {
            throw new RuntimeException('No se pudo preparar el directorio para subir documentos.');
        }
    }
}

if (!function_exists('empresaDocumentoUploadGenerateFilename')) {
    function empresaDocumentoUploadGenerateFilename(int $empresaId, string $scope, int $tipoId, string $extension): string
    {
        $extension = strtolower($extension);

        try {
            $random = bin2hex(random_bytes(5));
        } catch (Throwable $exception) {
            throw new RuntimeException('No se pudo generar un nombre unico para el archivo a subir.', 0, $exception);
        }

        $timestamp = date('Ymd_His');
        $scope = $scope === 'custom' ? 'custom' : 'global';

        return sprintf('empresa_%d_%s_%d_%s.%s', $empresaId, $scope, $tipoId, $timestamp . '_' . $random, $extension);
    }
}

if (!function_exists('empresaDocumentoUploadFormatOptionValue')) {
    function empresaDocumentoUploadFormatOptionValue(string $scope, int $tipoId): string
    {
        $scope = $scope === 'custom' ? 'custom' : 'global';

        return $scope . ':' . $tipoId;
    }
}

if (!function_exists('empresaDocumentoUploadParseOptionValue')) {
    /**
     * @return array{scope: string, id: int}|null
     */
    function empresaDocumentoUploadParseOptionValue(?string $value): ?array
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        $parts = explode(':', $value);

        if (count($parts) !== 2) {
            return null;
        }

        $scope = $parts[0] === 'custom' ? 'custom' : ($parts[0] === 'global' ? 'global' : null);
        $id = (int) $parts[1];

        if ($scope === null || $id <= 0) {
            return null;
        }

        return ['scope' => $scope, 'id' => $id];
    }
}

if (!function_exists('empresaDocumentoUploadScopeLabel')) {
    function empresaDocumentoUploadScopeLabel(string $scope): string
    {
        return $scope === 'custom' ? 'Personalizado' : 'Global';
    }
}

if (!function_exists('empresaDocumentoUploadBuildOptions')) {
    /**
     * @param array<int, array<string, mixed>> $documents
     * @return array<int, array<string, mixed>>
     */
    function empresaDocumentoUploadBuildOptions(array $documents): array
    {
        $options = [];

        foreach ($documents as $document) {
            $tipoId = isset($document['tipo_documento_id']) ? (int) $document['tipo_documento_id'] : 0;
            $scope = ($document['tipo_scope'] ?? 'global') === 'custom' ? 'custom' : 'global';

            if ($tipoId <= 0) {
                continue;
            }

            $nombre = trim((string) ($document['nombre_documento'] ?? 'Documento'));
            $status = empresaDocumentoNormalizeStatus($document['estatus'] ?? null);
            $statusLabel = $status !== null ? empresaDocumentoBadgeLabel($status) : 'Sin estatus';

            $labelParts = [$nombre, empresaDocumentoUploadScopeLabel($scope)];

            if ($statusLabel !== '') {
                $labelParts[] = 'Estado: ' . $statusLabel;
            }

            $options[] = [
                'value' => empresaDocumentoUploadFormatOptionValue($scope, $tipoId),
                'label' => implode(' - ', array_filter($labelParts, static fn($part) => $part !== '')),
                'scope' => $scope,
                'tipo_id' => $tipoId,
                'status' => $status,
                'status_label' => $statusLabel,
                'disabled' => $status === 'aprobado',
            ];
        }

        return $options;
    }
}

if (!function_exists('empresaDocumentoUploadStatusMessage')) {
    /**
     * @return array{type: string, message: string}|null
     */
    function empresaDocumentoUploadStatusMessage(?string $statusCode, ?string $documentName = null, ?string $reason = null): ?array
    {
        $statusCode = trim((string) $statusCode);

        if ($statusCode === '') {
            return null;
        }

        $documentLabel = trim((string) $documentName) !== ''
            ? '"' . trim((string) $documentName) . '"'
            : 'el documento seleccionado';

        return match ($statusCode) {
            'upload_success' => [
                'type' => 'success',
                'message' => sprintf('Se envio %s para revision. El estatus se actualizo a "En revision".', $documentLabel),
            ],
            'upload_not_allowed' => [
                'type' => 'error',
                'message' => sprintf('No es posible reemplazar %s porque ya esta aprobado.', $documentLabel),
            ],
            'upload_not_assigned' => [
                'type' => 'error',
                'message' => 'El documento seleccionado no pertenece a tu empresa.',
            ],
            'upload_invalid_tipo' => [
                'type' => 'error',
                'message' => 'Selecciona un tipo de documento valido para continuar.',
            ],
            'upload_readonly' => [
                'type' => 'error',
                'message' => 'El portal esta en modo solo lectura (empresa inactiva o completada); no se pueden subir archivos.',
            ],
            'upload_file_error' => [
                'type' => 'error',
                'message' => match ($reason) {
                    'no_file' => 'Debes adjuntar un archivo para continuar.',
                    'invalid_upload' => 'El archivo no se recibio correctamente. Intenta nuevamente.',
                    'too_large' => 'El archivo excede el tamano maximo permitido (10 MB).',
                    'invalid_type' => 'Solo se permiten archivos en formato PDF, JPG o PNG.',
                    'move_failed' => 'No se pudo guardar el archivo en el servidor. Intenta nuevamente.',
                    default => 'Ocurrio un problema al procesar el archivo. Intenta nuevamente.',
                },
            ],
            'upload_server_error' => [
                'type' => 'error',
                'message' => 'Ocurrio un error inesperado. Intenta nuevamente mas tarde.',
            ],
            default => null,
        };
    }
}

if (!function_exists('empresaDocumentoUploadRemoveFile')) {
    function empresaDocumentoUploadRemoveFile(?string $relativePath): void
    {
        if ($relativePath === null) {
            return;
        }

        $relativePath = trim($relativePath);

        if ($relativePath === '') {
            return;
        }

        $normalized = ltrim(str_replace(['\\', '\\'], '/', $relativePath), '/');
        $projectRoot = dirname(__DIR__, 3);
        $absolute = $projectRoot . DIRECTORY_SEPARATOR . 'recidencia' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalized);

        if (is_file($absolute)) {
            @unlink($absolute);
        }
    }
}
