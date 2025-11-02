<?php
declare(strict_types=1);

if (!function_exists('convenioDocumentosNormalizeStatus')) {
    function convenioDocumentosNormalizeStatus(?string $status): string
    {
        $status = trim((string) $status);

        if ($status === '' || $status === '0') {
            return 'pendiente';
        }

        if (function_exists('mb_strtolower')) {
            $status = mb_strtolower($status, 'UTF-8');
        } else {
            $status = strtolower($status);
        }

        return match ($status) {
            'aprobado', 'aprobada' => 'aprobado',
            'rechazado', 'rechazada' => 'rechazado',
            default => 'pendiente',
        };
    }
}

if (!function_exists('convenioDocumentosStatusLabel')) {
    function convenioDocumentosStatusLabel(string $status): string
    {
        return match ($status) {
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            default => 'Pendiente',
        };
    }
}

if (!function_exists('convenioDocumentosBadgeClass')) {
    function convenioDocumentosBadgeClass(string $status): string
    {
        return match ($status) {
            'aprobado' => 'badge ok',
            'rechazado' => 'badge err',
            default => 'badge warn',
        };
    }
}

if (!function_exists('convenioDocumentosFormatDateTime')) {
    function convenioDocumentosFormatDateTime(?string $value, string $fallback = 'N/D'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00' || $value === '0000-00-00 00:00:00') {
            return $fallback;
        }

        try {
            $date = new \DateTimeImmutable($value);
        } catch (\Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y H:i');
    }
}

if (!function_exists('convenioDocumentosBuildArchivoUrl')) {
    function convenioDocumentosBuildArchivoUrl(?string $ruta): ?string
    {
        if ($ruta === null) {
            return null;
        }

        $ruta = trim($ruta);

        if ($ruta === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $ruta) === 1) {
            return $ruta;
        }

        $sanitized = str_replace('\\', '/', $ruta);

        return '../../' . ltrim($sanitized, '/');
    }
}

if (!function_exists('convenioDocumentosBuildUploadUrl')) {
    function convenioDocumentosBuildUploadUrl(int $empresaId, string $origen, ?int $tipoId): string
    {
        $query = [
            'empresa_id' => (string) $empresaId,
            'tipo_origen' => $origen,
        ];

        if ($origen === 'global' && $tipoId !== null && $tipoId > 0) {
            $query['tipo_global_id'] = (string) $tipoId;
        } elseif ($origen === 'personalizado' && $tipoId !== null && $tipoId > 0) {
            $query['tipo_personalizado_id'] = (string) $tipoId;
        }

        return '../documentos/documento_upload.php?' . http_build_query($query);
    }
}

if (!function_exists('convenioDocumentosDecorateRecord')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function convenioDocumentosDecorateRecord(array $record, string $origen, int $empresaId): array
    {
        $nombreKey = $origen === 'global' ? 'tipo_nombre' : 'nombre';
        $nombre = isset($record[$nombreKey]) ? trim((string) $record[$nombreKey]) : '';

        if ($nombre === '') {
            $fallbackId = $origen === 'global'
                ? ($record['tipo_id'] ?? null)
                : ($record['id'] ?? null);
            $nombre = 'Documento #' . (is_scalar($fallbackId) ? (string) $fallbackId : '?');
        }

        $descripcionKey = $origen === 'global' ? 'tipo_descripcion' : 'descripcion';
        $descripcion = isset($record[$descripcionKey]) ? trim((string) $record[$descripcionKey]) : '';

        $obligatorioKey = $origen === 'global' ? 'tipo_obligatorio' : 'obligatorio';
        $obligatorioValue = $record[$obligatorioKey] ?? null;
        $obligatorio = false;

        if (is_bool($obligatorioValue)) {
            $obligatorio = $obligatorioValue;
        } elseif (is_numeric($obligatorioValue)) {
            $obligatorio = (int) $obligatorioValue === 1;
        } elseif (is_string($obligatorioValue)) {
            $normalized = strtolower(trim($obligatorioValue));
            $obligatorio = in_array($normalized, ['1', 'true', 'si', 'sí', 'yes', 'on'], true);
        }

        $documentoId = isset($record['documento_id']) && $record['documento_id'] !== null
            ? (int) $record['documento_id']
            : null;

        $status = $documentoId !== null
            ? convenioDocumentosNormalizeStatus($record['documento_estatus'] ?? null)
            : 'pendiente';

        $ruta = $documentoId !== null
            ? convenioDocumentosBuildArchivoUrl($record['documento_ruta'] ?? null)
            : null;

        $fechaBase = $record['documento_actualizado_en'] ?? $record['documento_creado_en'] ?? null;
        $fecha = convenioDocumentosFormatDateTime($fechaBase);

        $typeId = $origen === 'global'
            ? (isset($record['tipo_id']) ? (int) $record['tipo_id'] : null)
            : (isset($record['id']) ? (int) $record['id'] : null);

        $uploadUrl = $documentoId === null
            ? convenioDocumentosBuildUploadUrl($empresaId, $origen === 'global' ? 'global' : 'personalizado', $typeId)
            : null;

        return [
            'titulo' => $nombre,
            'descripcion' => $descripcion,
            'obligatorio' => $obligatorio,
            'estatus' => $status,
            'badge_class' => convenioDocumentosBadgeClass($status),
            'badge_label' => convenioDocumentosStatusLabel($status),
            'fecha' => $fecha,
            'url' => $ruta,
            'upload_url' => $uploadUrl,
            'origen' => $origen,
            'documento_id' => $documentoId,
            'tipo_id' => $typeId,
        ];
    }
}

if (!function_exists('convenioDocumentosDecorateList')) {
    /**
     * @param array<int, array<string, mixed>> $globalDocs
     * @param array<int, array<string, mixed>> $customDocs
     * @return array<int, array<string, mixed>>
     */
    function convenioDocumentosDecorateList(array $globalDocs, array $customDocs, int $empresaId): array
    {
        $items = [];

        foreach ($globalDocs as $record) {
            if (!is_array($record)) {
                continue;
            }

            $items[] = convenioDocumentosDecorateRecord($record, 'global', $empresaId);
        }

        foreach ($customDocs as $record) {
            if (!is_array($record)) {
                continue;
            }

            $items[] = convenioDocumentosDecorateRecord($record, 'personalizado', $empresaId);
        }

        return $items;
    }
}

