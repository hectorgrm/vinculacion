<?php

declare(strict_types=1);

require_once __DIR__ . '/documentofunctions_list.php';

if (!function_exists('documentoViewDefaults')) {
    /**
     * @return array{
     *     documentId: ?int,
     *     document: ?array<string, mixed>,
     *     fileMeta: array{
     *         exists: bool,
     *         absolutePath: ?string,
     *         publicUrl: ?string,
     *         filename: ?string,
     *         sizeBytes: ?int,
     *         sizeLabel: ?string,
     *         extension: ?string,
     *         canPreview: bool
     *     },
     *     history: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function documentoViewDefaults(): array
    {
        return [
            'documentId' => null,
            'document' => null,
            'fileMeta' => [
                'exists' => false,
                'absolutePath' => null,
                'publicUrl' => null,
                'filename' => null,
                'sizeBytes' => null,
                'sizeLabel' => null,
                'extension' => null,
                'canPreview' => false,
            ],
            'history' => [],
            'auditHistory' => [],
            'controllerError' => null,
            'notFoundMessage' => null,
        ];
    }
}

if (!function_exists('documentoViewNormalizeId')) {
    function documentoViewNormalizeId(mixed $value): ?int
    {
        return documentoNormalizePositiveInt($value);
    }
}

if (!function_exists('documentoViewControllerErrorMessage')) {
    function documentoViewControllerErrorMessage(\Throwable $exception): string
    {
        $message = $exception->getMessage();
        $message = is_string($message) ? trim($message) : '';

        return $message !== ''
            ? $message
            : 'No se pudo obtener los datos del documento.';
    }
}

if (!function_exists('documentoViewNotFoundMessage')) {
    function documentoViewNotFoundMessage(int $documentId): string
    {
        return 'No se encontro el documento solicitado (#' . $documentId . ').';
    }
}

if (!function_exists('documentoViewBuildFileMeta')) {
    /**
     * @return array{
     *     exists: bool,
     *     absolutePath: ?string,
     *     publicUrl: ?string,
     *     filename: ?string,
     *     sizeBytes: ?int,
     *     sizeLabel: ?string,
     *     extension: ?string,
     *     canPreview: bool
     * }
     */
    function documentoViewBuildFileMeta(?string $ruta, string $projectRoot): array
    {
        $defaults = [
            'exists' => false,
            'absolutePath' => null,
            'publicUrl' => null,
            'filename' => null,
            'sizeBytes' => null,
            'sizeLabel' => null,
            'extension' => null,
            'canPreview' => false,
        ];

        $ruta = trim((string) $ruta);
        if ($ruta === '') {
            return $defaults;
        }

        $relative = ltrim(str_replace('\\', '/', $ruta), '/');
        $absolutePath = rtrim($projectRoot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR
            . str_replace('/', DIRECTORY_SEPARATOR, $relative);
        $exists = is_file($absolutePath);
        $filename = basename($relative);
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $sizeBytes = null;
        $sizeLabel = null;
        if ($exists) {
            $sizeBytes = (int) @filesize($absolutePath);
            if ($sizeBytes > 0) {
                $sizeLabel = documentoViewFormatFileSize($sizeBytes);
            }
        }

        $previewableExtensions = ['pdf', 'png', 'jpg', 'jpeg'];

        return [
            'exists' => $exists,
            'absolutePath' => $exists ? $absolutePath : null,
            'publicUrl' => '../../' . $relative,
            'filename' => $filename !== '' ? $filename : null,
            'sizeBytes' => $sizeBytes,
            'sizeLabel' => $sizeLabel,
            'extension' => $extension !== '' ? $extension : null,
            'canPreview' => $exists && in_array($extension, $previewableExtensions, true),
        ];
    }
}

if (!function_exists('documentoViewFormatFileSize')) {
    function documentoViewFormatFileSize(int $bytes): string
    {
        if ($bytes < 1024) {
            return $bytes . ' B';
        }

        $units = ['KB', 'MB', 'GB', 'TB'];
        $size = (float) $bytes;
        $unit = 'KB';

        foreach ($units as $candidate) {
            $size /= 1024;
            $unit = $candidate;
            if ($size < 1024) {
                break;
            }
        }

        $precision = $size >= 10 ? 1 : 2;

        return number_format($size, $precision, '.', '') . ' ' . $unit;
    }
}

if (!function_exists('documentoViewDecorateDocument')) {
    /**
     * @param array<string, mixed> $document
     * @return array<string, mixed>
     */
    function documentoViewDecorateDocument(array $document): array
    {
        $document['estatus_badge_class'] = documentoRenderBadgeClass($document['estatus'] ?? null);
        $document['estatus_badge_label'] = documentoRenderBadgeLabel($document['estatus'] ?? null);
        $document['creado_en_label'] = documentoFormatDateTime($document['creado_en'] ?? null);

        $empresaId = isset($document['empresa_id']) ? (int) $document['empresa_id'] : 0;
        $empresaNombre = documentoValueOrDefault($document['empresa_nombre'] ?? null, '');
        $document['empresa_label'] = $empresaNombre !== ''
            ? $empresaNombre
            : ($empresaId > 0 ? 'Empresa #' . $empresaId : 'Empresa sin nombre');

        $tipoGlobalId = documentoNormalizePositiveInt($document['tipo_global_id'] ?? null);
        $tipoPersonalizadoId = documentoNormalizePositiveInt($document['tipo_personalizado_id'] ?? null);
        $document['tipo_origen'] = $tipoPersonalizadoId !== null ? 'personalizado' : 'global';

        if ($tipoPersonalizadoId !== null) {
            $nombre = documentoValueOrDefault($document['tipo_personalizado_nombre'] ?? null, '');
            $document['tipo_label'] = $nombre !== ''
                ? $nombre
                : 'Documento personalizado #' . $tipoPersonalizadoId;
            $document['tipo_obligatorio'] = (bool) ($document['tipo_personalizado_obligatorio'] ?? false);
        } else {
            $nombre = documentoValueOrDefault($document['tipo_global_nombre'] ?? null, '');
            $document['tipo_label'] = $nombre !== ''
                ? $nombre
                : ($tipoGlobalId !== null ? 'Documento global #' . $tipoGlobalId : 'Documento global');
            $document['tipo_obligatorio'] = (bool) ($document['tipo_global_obligatorio'] ?? false);
        }

        $document['archivo_nombre'] = isset($document['ruta'])
            ? basename(str_replace('\\', '/', (string) $document['ruta']))
            : null;

        if (!isset($document['empresa_estatus']) && isset($document['empresa_status'])) {
            $document['empresa_estatus'] = (string) $document['empresa_status'];
        }

        $empresaEstatus = isset($document['empresa_estatus']) ? trim((string) $document['empresa_estatus']) : '';
        $document['empresa_estatus'] = $empresaEstatus;
        $document['empresa_es_completada'] = strcasecmp($empresaEstatus, 'Completada') === 0;

        return $document;
    }
}

if (!function_exists('documentoViewDecorateHistory')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function documentoViewDecorateHistory(array $records): array
    {
        $decorated = [];

        foreach ($records as $record) {
            $decorated[] = documentoViewBuildHistoryEntry($record);
        }

        return $decorated;
    }
}

if (!function_exists('documentoViewDecorateAuditHistory')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function documentoViewDecorateAuditHistory(array $records): array
    {
        $decorated = [];

        foreach ($records as $record) {
            $decorated[] = documentoViewBuildAuditHistoryEntry($record);
        }

        return $decorated;
    }
}

if (!function_exists('documentoViewBuildAuditHistoryEntry')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function documentoViewBuildAuditHistoryEntry(array $record): array
    {
        $normalizedAction = documentoViewNormalizeAuditAction($record['accion'] ?? null);

        $templates = [
            'aprobar' => ['icon' => '✅', 'label' => 'Aprobado'],
            'reabrir' => ['icon' => '🔄', 'label' => 'Revisión reabierta'],
            'subir_nueva_version' => ['icon' => '📤', 'label' => 'Nueva versión subida'],
            'subir' => ['icon' => '📄', 'label' => 'Documento cargado'],
            'rechazar' => ['icon' => '❌', 'label' => 'Rechazado'],
            'actualizar' => ['icon' => '🛠️', 'label' => 'Actualización'],
            'actualizar_estatus' => ['icon' => '📝', 'label' => 'Estatus actualizado'],
        ];

        $template = $templates[$normalizedAction] ?? null;

        $record['accion_icon'] = $template['icon'] ?? '•';
        $record['accion_label'] = $template['label'] ?? documentoViewHumanizeAuditAction($normalizedAction);

        $record['ts_label'] = documentoFormatDateTime($record['ts'] ?? null);

        $actorNombre = isset($record['actor_nombre']) ? trim((string) $record['actor_nombre']) : '';
        $actorTipo = isset($record['actor_tipo']) ? trim((string) $record['actor_tipo']) : '';
        $actorId = $record['actor_id'] ?? null;
        $record['actor_label'] = documentoViewFormatAuditActor($actorTipo, $actorId, $actorNombre);

        $ip = isset($record['ip']) ? trim((string) $record['ip']) : '';
        $record['ip_label'] = $ip !== '' ? $ip : null;

        return $record;
    }
}

if (!function_exists('documentoViewNormalizeAuditAction')) {
    function documentoViewNormalizeAuditAction(mixed $accion): string
    {
        $accion = trim((string) $accion);

        if ($accion === '') {
            return '';
        }

        $accion = function_exists('mb_strtolower')
            ? mb_strtolower($accion, 'UTF-8')
            : strtolower($accion);

        return str_replace([' ', '-'], '_', $accion);
    }
}

if (!function_exists('documentoViewHumanizeAuditAction')) {
    function documentoViewHumanizeAuditAction(string $accion): string
    {
        $accion = str_replace(['_', '-'], ' ', $accion);
        $accion = trim($accion);

        if ($accion === '') {
            return 'Acción';
        }

        if (function_exists('mb_convert_case')) {
            return mb_convert_case($accion, MB_CASE_TITLE, 'UTF-8');
        }

        return ucwords($accion);
    }
}

if (!function_exists('documentoViewFormatAuditActor')) {
    function documentoViewFormatAuditActor(?string $actorTipo, mixed $actorId, ?string $actorNombre): string
    {
        $actorNombre = is_string($actorNombre) ? trim($actorNombre) : '';
        if ($actorNombre !== '') {
            return $actorNombre;
        }

        $actorTipo = is_string($actorTipo) ? trim($actorTipo) : '';
        $actorId = documentoNormalizePositiveInt($actorId);

        $tipoLabel = match ($actorTipo) {
            'usuario' => 'Usuario',
            'empresa' => 'Empresa',
            'sistema' => 'Sistema',
            default => ($actorTipo !== '' ? ucfirst($actorTipo) : 'Sin actor'),
        };

        if ($tipoLabel === 'Sistema' || $actorId === null) {
            return $tipoLabel;
        }

        return $tipoLabel . ' #' . $actorId;
    }
}

if (!function_exists('documentoViewBuildHistoryEntry')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function documentoViewBuildHistoryEntry(array $record): array
    {
        $record['estatus_badge_class'] = documentoRenderBadgeClass($record['estatus'] ?? null);
        $record['estatus_badge_label'] = documentoRenderBadgeLabel($record['estatus'] ?? null);
        $record['creado_en_label'] = documentoFormatDateTime($record['creado_en'] ?? null);
        $record['archivo_nombre'] = isset($record['ruta'])
            ? basename(str_replace('\\', '/', (string) $record['ruta']))
            : null;

        return $record;
    }
}
