<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';

if (!function_exists('empresaViewDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
     *     conveniosActivos: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     notFoundMessage: ?string,
     *     inputError: ?string
     * }
     */
    function empresaViewDefaults(): array
    {
        return [
            'empresaId' => null,
            'empresa' => null,
            'conveniosActivos' => [],
            'documentos' => [],
            'documentosStats' => [
                'total' => 0,
                'subidos' => 0,
                'aprobados' => 0,
                'porcentaje' => 0,
            ],
            'documentosGestionUrl' => null,
            'controllerError' => null,
            'notFoundMessage' => null,
            'inputError' => null,
        ];
    }
}

if (!function_exists('empresaViewNormalizeId')) {
    function empresaViewNormalizeId(mixed $value): ?int
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

if (!function_exists('empresaViewControllerErrorMessage')) {
    function empresaViewControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== '' ? $message : 'No se pudo obtener la informacion de la empresa.';
    }
}

if (!function_exists('empresaViewNotFoundMessage')) {
    function empresaViewNotFoundMessage(int $empresaId): string
    {
        return 'No se encontro la empresa solicitada (#' . $empresaId . ').';
    }
}

if (!function_exists('empresaViewInputErrorMessage')) {
    function empresaViewInputErrorMessage(): string
    {
        return 'No se proporciono un identificador de empresa valido.';
    }
}

if (!function_exists('empresaViewFormatDate')) {
    function empresaViewFormatDate(?string $value, string $fallback = 'N/A'): string
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

        return $date->format('d/m/Y');
    }
}

if (!function_exists('empresaViewFormatDateTime')) {
    function empresaViewFormatDateTime(?string $value, string $fallback = '—'): string
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

if (!function_exists('empresaViewValueOrDefault')) {
    function empresaViewValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $stringValue = trim((string) $value);

            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('empresaViewDecorate')) {
    /**
     * @param array<string, mixed> $empresa
     * @return array<string, mixed>
     */
    function empresaViewDecorate(array $empresa): array
    {
        $empresa['estatus_badge_class'] = renderBadgeClass($empresa['estatus'] ?? null);
        $empresa['estatus_badge_label'] = renderBadgeLabel($empresa['estatus'] ?? null);
        $empresa['creado_en_label'] = empresaViewFormatDate($empresa['creado_en'] ?? null);
        $empresa['actualizado_en_label'] = empresaViewFormatDate($empresa['actualizado_en'] ?? null, 'Sin actualizar');

        $empresa['nombre_label'] = empresaViewValueOrDefault($empresa['nombre'] ?? null, 'Sin nombre');
        $empresa['rfc_label'] = empresaViewValueOrDefault($empresa['rfc'] ?? null);
        $empresa['representante_label'] = empresaViewValueOrDefault($empresa['representante'] ?? null, 'No especificado');
        $empresa['telefono_label'] = empresaViewValueOrDefault($empresa['telefono'] ?? null, 'No registrado');
        $empresa['correo_label'] = empresaViewValueOrDefault($empresa['contacto_email'] ?? null, 'No registrado');
        $empresa['tipo_empresa_inferido'] = empresaViewInferTipoEmpresa($empresa['regimen_fiscal'] ?? null);
        $empresa['logo_path'] = empresaViewNormalizeLogoPath($empresa['logo_path'] ?? null);
        $empresa['logo_url'] = empresaViewBuildLogoUrl($empresa['logo_path']);

        return $empresa;
    }
}

if (!function_exists('empresaViewInferTipoEmpresa')) {
    function empresaViewInferTipoEmpresa(mixed $regimenFiscal): ?string
    {
        if ($regimenFiscal === null) {
            return null;
        }

        $normalized = (string) $regimenFiscal;

        if (function_exists('mb_strtolower')) {
            $normalized = mb_strtolower($normalized, 'UTF-8');
        } else {
            $normalized = strtolower($normalized);
        }

        $normalized = trim($normalized);

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

if (!function_exists('empresaViewNormalizeLogoPath')) {
    function empresaViewNormalizeLogoPath(mixed $value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $value) === 1) {
            return $value;
        }

        $value = str_replace('\\', '/', $value);
        $value = preg_replace('#/{2,}#', '/', $value);

        if (!is_string($value)) {
            $value = (string) $value;
        }

        $value = ltrim($value, '/');

        if ($value === '' || str_contains($value, '..')) {
            return null;
        }

        return $value;
    }
}

if (!function_exists('empresaViewBuildLogoUrl')) {
    function empresaViewBuildLogoUrl(mixed $logoPath): ?string
    {
        $normalized = empresaViewNormalizeLogoPath($logoPath);

        if ($normalized === null) {
            return null;
        }

        if (preg_match('/^https?:\/\//i', $normalized) === 1) {
            return $normalized;
        }

        return '../../' . $normalized;
    }
}

if (!function_exists('empresaViewCastBool')) {
    function empresaViewCastBool(mixed $value): bool
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

            return in_array($normalized, ['1', 'true', 'si', 'sí', 'yes', 'on'], true);
        }

        return false;
    }
}

if (!function_exists('empresaViewBuildArchivoUrl')) {
    function empresaViewBuildArchivoUrl(?string $ruta): ?string
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

if (!function_exists('empresaViewNormalizeDocumentStatus')) {
    function empresaViewNormalizeDocumentStatus(?string $status): string
    {
        $status = trim((string) $status);

        if ($status === '') {
            return 'pendiente';
        }

        if (function_exists('mb_strtolower')) {
            $status = mb_strtolower($status, 'UTF-8');
        } else {
            $status = strtolower($status);
        }

        return match ($status) {
            'aprobado' => 'aprobado',
            'rechazado' => 'rechazado',
            'revision', 'en revisión', 'en revision', 'revisión' => 'revision',
            'pendiente', 'reabierto', 'reabierto (pendiente)' => 'pendiente',
            default => 'pendiente',
        };
    }
}

if (!function_exists('empresaViewDocumentStatusLabel')) {
    function empresaViewDocumentStatusLabel(string $status): string
    {
        return match ($status) {
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'revision' => 'En revisión',
            default => 'Pendiente',
        };
    }
}

if (!function_exists('empresaViewDocumentStatusBadgeClass')) {
    function empresaViewDocumentStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'aprobado' => 'badge ok',
            'rechazado' => 'badge rechazado',
            'revision' => 'badge en_revision',
            default => 'badge pendiente',
        };
    }
}

if (!function_exists('empresaViewDecorateDocumentoRegistro')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, mixed>
     */
    function empresaViewDecorateDocumentoRegistro(
        array $record,
        string $origen,
        string $gestionUrl,
        ?int $empresaId = null
    ): array {
        $nombreKey = $origen === 'global' ? 'tipo_nombre' : 'nombre';
        $descripcionKey = $origen === 'global' ? 'tipo_descripcion' : 'descripcion';
        $obligatorioKey = $origen === 'global' ? 'tipo_obligatorio' : 'obligatorio';

        $nombre = empresaViewValueOrDefault($record[$nombreKey] ?? null, 'Documento');
        $descripcion = empresaViewValueOrDefault($record[$descripcionKey] ?? null, '');
        $obligatorio = empresaViewCastBool($record[$obligatorioKey] ?? null);

        $documentoId = isset($record['documento_id']) ? (int) $record['documento_id'] : null;
        $rutaOriginal = empresaViewValueOrDefault($record['documento_ruta'] ?? null, '');
        $archivoUrl = $rutaOriginal !== '' ? empresaViewBuildArchivoUrl($rutaOriginal) : null;

        $status = $documentoId !== null
            ? empresaViewNormalizeDocumentStatus($record['documento_estatus'] ?? null)
            : 'pendiente';

        $ultimaActualizacion = $record['documento_actualizado_en']
            ?? $record['documento_creado_en']
            ?? null;

        $resolvedEmpresaId = $empresaId ?? (isset($record['empresa_id']) ? (int) $record['empresa_id'] : null);
        $tipoGlobalId = $origen === 'global' && isset($record['tipo_id']) ? (int) $record['tipo_id'] : null;
        $tipoPersonalizadoId = $origen === 'personalizado' && isset($record['id']) ? (int) $record['id'] : null;

        $accionVariant = 'upload';
        $accionLabel = 'Subir';
        $accionUrl = empresaViewBuildDocumentoUploadUrl(
            $resolvedEmpresaId,
            $origen,
            $tipoGlobalId,
            $tipoPersonalizadoId,
            $status
        );

        if ($archivoUrl !== null) {
            $accionVariant = 'view';
            $accionLabel = 'Ver';
            $accionUrl = $archivoUrl;
        }

        $detailUrl = null;
        $reviewUrl = null;

        if ($documentoId !== null) {
            $detailUrl = '../documentos/documento_view.php?id=' . urlencode((string) $documentoId);
            $reviewUrl = '../documentos/documento_review.php?id=' . urlencode((string) $documentoId);
        }

        return [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'obligatorio' => $obligatorio,
            'obligatorio_label' => $obligatorio ? 'Obligatorio' : 'Opcional',
            'estatus' => $status,
            'estatus_label' => empresaViewDocumentStatusLabel($status),
            'estatus_badge_class' => empresaViewDocumentStatusBadgeClass($status),
            'ultima_actualizacion' => $ultimaActualizacion,
            'ultima_actualizacion_label' => empresaViewFormatDateTime($ultimaActualizacion, '—'),
            'accion_label' => $accionLabel,
            'accion_url' => $accionUrl,
            'accion_variant' => $accionVariant,
            'archivo_url' => $archivoUrl,
            'tiene_archivo' => $archivoUrl !== null,
            'detail_url' => $detailUrl,
            'review_url' => $reviewUrl,
            'documento_id' => $documentoId,
            'origen' => $origen,
            'upload_url' => empresaViewBuildDocumentoUploadUrl(
                $resolvedEmpresaId,
                $origen,
                $tipoGlobalId,
                $tipoPersonalizadoId,
                $status
            ),
        ];
    }
}

if (!function_exists('empresaViewBuildDocumentoUploadUrl')) {
    function empresaViewBuildDocumentoUploadUrl(
        ?int $empresaId,
        string $origen,
        ?int $tipoGlobalId,
        ?int $tipoPersonalizadoId,
        ?string $estatus
    ): string {
        $base = '../documentos/documento_upload.php';
        $params = [];

        if ($empresaId !== null) {
            $params['empresa'] = (string) $empresaId;
        }

        $params['origen'] = $origen;

        if ($origen === 'global' && $tipoGlobalId !== null) {
            $params['tipo'] = (string) $tipoGlobalId;
        } elseif ($origen === 'personalizado' && $tipoPersonalizadoId !== null) {
            $params['personalizado'] = (string) $tipoPersonalizadoId;
        }

        if ($estatus !== null && $estatus !== '') {
            $params['estatus'] = $estatus;
        }

        $query = http_build_query($params);

        return $base . ($query !== '' ? '?' . $query : '');
    }
}

if (!function_exists('empresaViewDecorateDocumentos')) {
    /**
     * @param array<int, array<string, mixed>> $globalDocs
     * @param array<int, array<string, mixed>> $customDocs
     * @return array{items: array<int, array<string, mixed>>, stats: array{total: int, subidos: int, aprobados: int, porcentaje: int}}
     */
    function empresaViewDecorateDocumentos(
        array $globalDocs,
        array $customDocs,
        string $gestionUrl,
        ?int $empresaId = null
    ): array {
        $items = [];
        $total = 0;
        $subidos = 0;
        $aprobados = 0;

        foreach ($globalDocs as $record) {
            if (!is_array($record)) {
                continue;
            }

            $decorated = empresaViewDecorateDocumentoRegistro($record, 'global', $gestionUrl, $empresaId);
            $items[] = $decorated;
            $total++;

            if ($decorated['tiene_archivo']) {
                $subidos++;

                if ($decorated['estatus'] === 'aprobado') {
                    $aprobados++;
                }
            }
        }

        foreach ($customDocs as $record) {
            if (!is_array($record)) {
                continue;
            }

            $decorated = empresaViewDecorateDocumentoRegistro($record, 'personalizado', $gestionUrl, $empresaId);
            $items[] = $decorated;
            $total++;

            if ($decorated['tiene_archivo']) {
                $subidos++;

                if ($decorated['estatus'] === 'aprobado') {
                    $aprobados++;
                }
            }
        }

        $porcentaje = 0;

        if ($total > 0) {
            $porcentaje = (int) round(($subidos / $total) * 100);
        }

        return [
            'items' => $items,
            'stats' => [
                'total' => $total,
                'subidos' => $subidos,
                'aprobados' => $aprobados,
                'porcentaje' => $porcentaje,
            ],
        ];
    }
}

if (!function_exists('empresaViewBuildGestionDocumentosUrl')) {
    function empresaViewBuildGestionDocumentosUrl(?int $empresaId): string
    {
        $base = '../empresadocumentotipo/empresa_documentotipo_list.php';

        if ($empresaId === null) {
            return $base;
        }

        return $base . '?id_empresa=' . urlencode((string) $empresaId);
    }
}

if (!function_exists('empresaViewDecorateConvenio')) {
    /**
     * @param array<string, mixed> $convenio
     * @return array<string, mixed>
     */
    function empresaViewDecorateConvenio(array $convenio): array
    {
        $convenio['id_label'] = isset($convenio['id']) ? '#' . (string) $convenio['id'] : '#';
        $convenio['responsable_label'] = empresaViewValueOrDefault(
            $convenio['responsable_academico'] ?? $convenio['tipo_convenio'] ?? null,
            'Sin responsable'
        );
        $convenio['fecha_inicio_label'] = empresaViewFormatDate($convenio['fecha_inicio'] ?? null);
        $convenio['fecha_fin_label'] = empresaViewFormatDate($convenio['fecha_fin'] ?? null);
        $convenio['estatus_badge_class'] = renderBadgeClass($convenio['estatus'] ?? null);
        $convenio['estatus_badge_label'] = renderBadgeLabel($convenio['estatus'] ?? null);

        if (isset($convenio['id'])) {
            $id = (int) $convenio['id'];
            $convenio['view_url'] = '../convenio/convenio_view.php?id=' . urlencode((string) $id);
            $convenio['edit_url'] = '../convenio/convenio_edit.php?id=' . urlencode((string) $id);
        }

        return $convenio;
    }
}

if (!function_exists('empresaViewDecorateConvenios')) {
    /**
     * @param array<int, array<string, mixed>> $convenios
     * @return array<int, array<string, mixed>>
     */
    function empresaViewDecorateConvenios(array $convenios): array
    {
        $decorated = [];

        foreach ($convenios as $convenio) {
            if (!is_array($convenio)) {
                continue;
            }

            $decorated[] = empresaViewDecorateConvenio($convenio);
        }

        return $decorated;
    }
}
