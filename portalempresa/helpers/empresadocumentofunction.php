<?php

declare(strict_types=1);

if (!function_exists('empresaDocumentoNormalizeSearch')) {
    function empresaDocumentoNormalizeSearch(?string $search): string
    {
        return trim((string) $search);
    }
}

if (!function_exists('empresaDocumentoStatusOptions')) {
    /**
     * @return array<string, string>
     */
    function empresaDocumentoStatusOptions(): array
    {
        return [
            'aprobado' => 'Aprobado',
            'pendiente' => 'Pendiente',
            'rechazado' => 'Rechazado',
        ];
    }
}

if (!function_exists('empresaDocumentoNormalizeStatus')) {
    function empresaDocumentoNormalizeStatus(?string $status): ?string
    {
        $status = trim((string) $status);

        if ($status === '') {
            return null;
        }

        $status = function_exists('mb_strtolower')
            ? mb_strtolower($status, 'UTF-8')
            : strtolower($status);

        $valid = empresaDocumentoStatusOptions();

        return array_key_exists($status, $valid) ? $status : null;
    }
}

if (!function_exists('empresaDocumentoStrContains')) {
    function empresaDocumentoStrContains(string $haystack, string $needle): bool
    {
        if ($needle === '') {
            return false;
        }

        if (function_exists('mb_stripos')) {
            return mb_stripos($haystack, $needle, 0, 'UTF-8') !== false;
        }

        return stripos($haystack, $needle) !== false;
    }
}

if (!function_exists('empresaDocumentoNormalizeFilters')) {
    /**
     * @param array<string, mixed> $source
     * @return array{q: string, estatus: string}
     */
    function empresaDocumentoNormalizeFilters(array $source): array
    {
        $search = empresaDocumentoNormalizeSearch(isset($source['q']) ? (string) $source['q'] : '');
        $status = empresaDocumentoNormalizeStatus(isset($source['estado']) ? (string) $source['estado'] : null);

        return [
            'q' => $search,
            'estatus' => $status ?? '',
        ];
    }
}

if (!function_exists('empresaDocumentoInferTipoEmpresa')) {
    function empresaDocumentoInferTipoEmpresa(?string $regimenFiscal): ?string
    {
        $regimenFiscal = trim((string) $regimenFiscal);

        if ($regimenFiscal === '') {
            return null;
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($regimenFiscal, 'UTF-8')
            : strtolower($regimenFiscal);

        if (empresaDocumentoStrContains($normalized, 'física') || empresaDocumentoStrContains($normalized, 'fisica')) {
            return 'fisica';
        }

        if (empresaDocumentoStrContains($normalized, 'moral')) {
            return 'moral';
        }

        return null;
    }
}

if (!function_exists('empresaDocumentoBadgeClass')) {
    function empresaDocumentoBadgeClass(?string $status): string
    {
        $status = empresaDocumentoNormalizeStatus($status);

        return match ($status) {
            'aprobado' => 'badge ok',
            'rechazado' => 'badge danger',
            'pendiente' => 'badge warn',
            default => 'badge secondary',
        };
    }
}

if (!function_exists('empresaDocumentoBadgeLabel')) {
    function empresaDocumentoBadgeLabel(?string $status): string
    {
        $status = empresaDocumentoNormalizeStatus($status);

        if ($status === null) {
            return 'Sin estatus';
        }

        $options = empresaDocumentoStatusOptions();

        return $options[$status] ?? ucfirst($status);
    }
}

if (!function_exists('empresaDocumentoHydrateRecords')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function empresaDocumentoHydrateRecords(array $records): array
    {
        $hydrated = [];

        foreach ($records as $record) {
            $status = empresaDocumentoNormalizeStatus(isset($record['documento_estatus']) ? (string) $record['documento_estatus'] : null) ?? 'pendiente';

            $hydrated[] = [
                'id' => isset($record['documento_id']) ? (int) $record['documento_id'] : null,
                'tipo' => ($record['scope'] ?? 'global') === 'custom' ? 'Personalizado' : 'Global',
                'nombre_documento' => (string) ($record['tipo_nombre'] ?? ''),
                'descripcion' => $record['tipo_descripcion'] ?? null,
                'obligatorio' => (int) ($record['tipo_obligatorio'] ?? 0) === 1,
                'estatus' => $status,
                'estatus_label' => empresaDocumentoBadgeLabel($status),
                'badge_class' => empresaDocumentoBadgeClass($status),
                'observaciones' => $record['documento_observacion'] ?? null,
                'archivo_path' => $record['documento_ruta'] ?? null,
                'actualizado_en' => $record['documento_actualizado_en'] ?? $record['documento_creado_en'] ?? null,
                'creado_en' => $record['documento_creado_en'] ?? null,
            ];
        }

        return $hydrated;
    }
}

if (!function_exists('empresaDocumentoApplyFilters')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function empresaDocumentoApplyFilters(array $records, string $search, ?string $status): array
    {
        $search = empresaDocumentoNormalizeSearch($search);
        $status = empresaDocumentoNormalizeStatus($status);

        if ($search === '' && $status === null) {
            return $records;
        }

        $filtered = [];
        $searchLower = function_exists('mb_strtolower')
            ? mb_strtolower($search, 'UTF-8')
            : strtolower($search);

        foreach ($records as $record) {
            if ($status !== null && ($record['estatus'] ?? null) !== $status) {
                continue;
            }

            if ($searchLower === '') {
                $filtered[] = $record;
                continue;
            }

            $haystack = [
                $record['nombre_documento'] ?? '',
                $record['tipo'] ?? '',
                $record['observaciones'] ?? '',
            ];

            $match = false;

            foreach ($haystack as $value) {
                $valueLower = function_exists('mb_strtolower')
                    ? mb_strtolower((string) $value, 'UTF-8')
                    : strtolower((string) $value);

                if ($valueLower !== '' && empresaDocumentoStrContains($valueLower, $searchLower)) {
                    $match = true;
                    break;
                }
            }

            if ($match) {
                $filtered[] = $record;
            }
        }

        return $filtered;
    }
}

if (!function_exists('empresaDocumentoComputeKpis')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array{aprobado: int, pendiente: int, rechazado: int}
     */
    function empresaDocumentoComputeKpis(array $records): array
    {
        $kpis = [
            'aprobado' => 0,
            'pendiente' => 0,
            'rechazado' => 0,
        ];

        foreach ($records as $record) {
            $status = empresaDocumentoNormalizeStatus($record['estatus'] ?? null) ?? 'pendiente';

            if (!array_key_exists($status, $kpis)) {
                $status = 'pendiente';
            }

            $kpis[$status]++;
        }

        return $kpis;
    }
}
