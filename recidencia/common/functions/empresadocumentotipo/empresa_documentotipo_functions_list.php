<?php

declare(strict_types=1);

if (!function_exists('empresaDocumentoTipoListDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     globalDocuments: array<int, array<string, mixed>>,
     *     customDocuments: array<int, array<string, mixed>>,
     *     stats: array<string, int>,
     *     controllerError: ?string,
     *     inputError: ?string,
     *     notFoundMessage: ?string
     * }
     */
    function empresaDocumentoTipoListDefaults(): array
    {
        return [
            'empresaId' => null,
            'empresa' => null,
            'globalDocuments' => [],
            'customDocuments' => [],
            'stats' => [
                'total' => 0,
                'subidos' => 0,
                'aprobados' => 0,
                'porcentaje' => 0,
            ],
            'controllerError' => null,
            'inputError' => null,
            'notFoundMessage' => null,
        ];
    }
}

if (!function_exists('empresaDocumentoTipoListNormalizeId')) {
    function empresaDocumentoTipoListNormalizeId(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value) && preg_match('/^\d+$/', $value) === 1) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('empresaDocumentoTipoListControllerErrorMessage')) {
    function empresaDocumentoTipoListControllerErrorMessage(\Throwable $throwable): string
    {
        $message = trim((string) $throwable->getMessage());

        return $message !== '' ? $message : 'No se pudo obtener la informacion de los documentos de la empresa.';
    }
}

if (!function_exists('empresaDocumentoTipoListInputErrorMessage')) {
    function empresaDocumentoTipoListInputErrorMessage(): string
    {
        return 'No se proporciono un identificador de empresa valido.';
    }
}

if (!function_exists('empresaDocumentoTipoListNotFoundMessage')) {
    function empresaDocumentoTipoListNotFoundMessage(int $empresaId): string
    {
        return 'No se encontro la empresa solicitada (#' . $empresaId . ').';
    }
}

if (!function_exists('empresaDocumentoTipoListFormatDate')) {
    function empresaDocumentoTipoListFormatDate(?string $value, string $fallback = '-'): string
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

if (!function_exists('empresaDocumentoTipoListValueOrDefault')) {
    function empresaDocumentoTipoListValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $trimmed = trim($value);

            return $trimmed !== '' ? $trimmed : $fallback;
        }

        if (is_scalar($value)) {
            $stringValue = trim((string) $value);

            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('empresaDocumentoTipoListCastBool')) {
    function empresaDocumentoTipoListCastBool(mixed $value): bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if ($value === null) {
            return false;
        }

        if (is_int($value)) {
            return $value === 1;
        }

        if (is_numeric($value)) {
            return (int) $value === 1;
        }

        if (is_string($value)) {
            $normalized = strtolower(trim($value));

            return in_array($normalized, ['1', 'true', 'si', 'yes', 'on'], true);
        }

        return false;
    }
}

if (!function_exists('empresaDocumentoTipoListObligatorioLabel')) {
    function empresaDocumentoTipoListObligatorioLabel(mixed $value): string
    {
        return empresaDocumentoTipoListCastBool($value) ? 'Si' : 'No';
    }
}

if (!function_exists('empresaDocumentoTipoListObligatorioClass')) {
    function empresaDocumentoTipoListObligatorioClass(mixed $value): string
    {
        return empresaDocumentoTipoListCastBool($value) ? 'badge ok' : 'badge pendiente';
    }
}

if (!function_exists('empresaDocumentoTipoListEstadoLabel')) {
    function empresaDocumentoTipoListEstadoLabel(?string $estado): string
    {
        $estado = strtolower(trim((string) $estado));

        return match ($estado) {
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            default => 'Pendiente',
        };
    }
}

if (!function_exists('empresaDocumentoTipoListEstadoClass')) {
    function empresaDocumentoTipoListEstadoClass(?string $estado): string
    {
        $estado = strtolower(trim((string) $estado));

        return match ($estado) {
            'aprobado' => 'badge ok',
            'rechazado' => 'badge rechazado',
            default => 'badge pendiente',
        };
    }
}

if (!function_exists('empresaDocumentoTipoListDecorateGlobalDocument')) {
    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    function empresaDocumentoTipoListDecorateGlobalDocument(array $row): array
    {
        $obligatorio = empresaDocumentoTipoListCastBool($row['tipo_obligatorio'] ?? null);
        $documentoId = isset($row['documento_id']) ? (int) $row['documento_id'] : null;
        $estadoRaw = $documentoId !== null ? (string) ($row['documento_estatus'] ?? '') : '';
        $ruta = $documentoId !== null ? empresaDocumentoTipoListValueOrDefault($row['documento_ruta'] ?? null, '') : '';

        $archivoNombre = $ruta !== '' ? basename($ruta) : null;

        return [
            'id' => isset($row['tipo_id']) ? (int) $row['tipo_id'] : null,
            'nombre' => empresaDocumentoTipoListValueOrDefault($row['tipo_nombre'] ?? null, 'Sin nombre'),
            'descripcion' => empresaDocumentoTipoListValueOrDefault($row['tipo_descripcion'] ?? null, ''),
            'obligatorio' => $obligatorio,
            'obligatorio_label' => empresaDocumentoTipoListObligatorioLabel($obligatorio),
            'obligatorio_badge_class' => empresaDocumentoTipoListObligatorioClass($obligatorio),
            'estado' => $estadoRaw !== '' ? $estadoRaw : 'pendiente',
            'estado_label' => empresaDocumentoTipoListEstadoLabel($estadoRaw),
            'estado_badge_class' => empresaDocumentoTipoListEstadoClass($estadoRaw),
            'archivo_nombre' => $archivoNombre,
            'archivo_ruta' => $ruta !== '' ? $ruta : null,
            'documento_id' => $documentoId,
            'observacion' => empresaDocumentoTipoListValueOrDefault($row['documento_observacion'] ?? null, ''),
            'ultima_actualizacion' => $row['documento_creado_en'] ?? null,
            'ultima_actualizacion_label' => empresaDocumentoTipoListFormatDate($row['documento_creado_en'] ?? null),
        ];
    }
}

if (!function_exists('empresaDocumentoTipoListDecorateGlobalDocuments')) {
    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    function empresaDocumentoTipoListDecorateGlobalDocuments(array $rows): array
    {
        $decorated = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $decorated[] = empresaDocumentoTipoListDecorateGlobalDocument($row);
        }

        return $decorated;
    }
}

if (!function_exists('empresaDocumentoTipoListDecorateCustomDocument')) {
    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    function empresaDocumentoTipoListDecorateCustomDocument(array $row): array
    {
        $obligatorio = empresaDocumentoTipoListCastBool($row['obligatorio'] ?? null);

        return [
            'id' => isset($row['id']) ? (int) $row['id'] : null,
            'nombre' => empresaDocumentoTipoListValueOrDefault($row['nombre'] ?? null, 'Sin nombre'),
            'descripcion' => empresaDocumentoTipoListValueOrDefault($row['descripcion'] ?? null, ''),
            'obligatorio' => $obligatorio,
            'obligatorio_label' => empresaDocumentoTipoListObligatorioLabel($obligatorio),
            'obligatorio_badge_class' => empresaDocumentoTipoListObligatorioClass($obligatorio),
            'estado' => 'sin_registro',
            'estado_label' => 'Sin registro',
            'estado_badge_class' => 'badge pendiente',
            'creado_en' => $row['creado_en'] ?? null,
            'creado_en_label' => empresaDocumentoTipoListFormatDate($row['creado_en'] ?? null),
        ];
    }
}

if (!function_exists('empresaDocumentoTipoListDecorateCustomDocuments')) {
    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    function empresaDocumentoTipoListDecorateCustomDocuments(array $rows): array
    {
        $decorated = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $decorated[] = empresaDocumentoTipoListDecorateCustomDocument($row);
        }

        return $decorated;
    }
}

if (!function_exists('empresaDocumentoTipoListInferTipoEmpresa')) {
    function empresaDocumentoTipoListInferTipoEmpresa(?string $regimenFiscal): ?string
    {
        if ($regimenFiscal === null) {
            return null;
        }

        $normalized = strtolower(trim($regimenFiscal));

        if ($normalized === '') {
            return null;
        }

        if (strpos($normalized, 'moral') !== false) {
            return 'moral';
        }

        if (strpos($normalized, 'fisica') !== false || strpos($normalized, 'fisico') !== false) {
            return 'fisica';
        }

        return null;
    }
}

if (!function_exists('empresaDocumentoTipoListDecorateEmpresa')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function empresaDocumentoTipoListDecorateEmpresa(array $record): array
    {
        $decorated = $record;

        $decorated['nombre_label'] = empresaDocumentoTipoListValueOrDefault($record['nombre'] ?? null, 'Sin nombre');
        $decorated['rfc_label'] = empresaDocumentoTipoListValueOrDefault($record['rfc'] ?? null, 'Sin RFC');
        $decorated['regimen_label'] = empresaDocumentoTipoListValueOrDefault($record['regimen_fiscal'] ?? null, 'Sin regimen');
        $decorated['tipo_empresa_inferido'] = empresaDocumentoTipoListInferTipoEmpresa($record['regimen_fiscal'] ?? null);

        return $decorated;
    }
}

if (!function_exists('empresaDocumentoTipoListBuildStats')) {
    /**
     * @param array<int, array<string, mixed>> $globalDocuments
     * @param array<int, array<string, mixed>> $customDocuments
     * @return array{total: int, subidos: int, aprobados: int, porcentaje: int}
     */
    function empresaDocumentoTipoListBuildStats(array $globalDocuments, array $customDocuments): array
    {
        $total = count($globalDocuments) + count($customDocuments);
        $subidos = 0;
        $aprobados = 0;

        foreach ($globalDocuments as $documento) {
            if (!is_array($documento)) {
                continue;
            }

            $documentoId = $documento['documento_id'] ?? null;
            if ($documentoId !== null) {
                $subidos++;

                $estado = strtolower(trim((string) ($documento['estado'] ?? '')));
                if ($estado === 'aprobado') {
                    $aprobados++;
                }
            }
        }

        $porcentaje = 0;
        if ($total > 0) {
            $porcentaje = (int) round(($subidos / $total) * 100);
        }

        return [
            'total' => $total,
            'subidos' => $subidos,
            'aprobados' => $aprobados,
            'porcentaje' => $porcentaje,
        ];
    }
}
