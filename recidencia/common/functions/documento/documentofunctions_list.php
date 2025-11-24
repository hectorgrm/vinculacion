<?php

declare(strict_types=1);

require_once __DIR__ . '/../auditoria/auditoriafunctions.php';
require_once __DIR__ . '/../../../../common/model/db.php';

use Common\Model\Database;

if (!function_exists('documentoListDefaults')) {
    /**
     * @return array{
     *     q: string,
     *     empresa: string,
     *     tipo: string,
     *     estatus: string,
     *     documentos: array<int, array<string, mixed>>,
     *     empresas: array<int, array<string, mixed>>,
     *     tipos: array<int, array<string, mixed>>,
     *     statusOptions: array<string, string>,
     *     errorMessage: ?string
     * }
     */
    function documentoListDefaults(): array
    {
        return [
            'q' => '',
            'empresa' => '',
            'tipo' => '',
            'estatus' => '',
            'documentos' => [],
            'empresas' => [],
            'tipos' => [],
            'statusOptions' => documentoStatusOptions(),
            'errorMessage' => null,
        ];
    }
}

if (!function_exists('documentoNormalizeSearch')) {
    function documentoNormalizeSearch(?string $search): string
    {
        return trim((string) $search);
    }
}

if (!function_exists('documentoNormalizePositiveInt')) {
    function documentoNormalizePositiveInt(mixed $value): ?int
    {
        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_numeric($value)) {
            $intValue = (int) $value;

            return $intValue > 0 ? $intValue : null;
        }

        return null;
    }
}

if (!function_exists('documentoStatusOptions')) {
    /**
     * @return array<string, string>
     */
    function documentoStatusOptions(): array
    {
        return [
            'aprobado' => 'Aprobado',
            'pendiente' => 'Pendiente',
            'revision' => 'En revisión',
            'rechazado' => 'Rechazado',
        ];
    }
}

if (!function_exists('documentoCurrentAuditContext')) {
    /**
     * @return array<string, mixed>
     */
    function documentoCurrentAuditContext(): array
    {
        $context = [];

        if (isset($GLOBALS['residenciaAuthUser']) && is_array($GLOBALS['residenciaAuthUser'])) {
            $context['actor_tipo'] = 'usuario';
            $context['actor_id'] = documentoNormalizePositiveInt($GLOBALS['residenciaAuthUser']['id'] ?? null);
        } elseif (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            $context['actor_tipo'] = 'usuario';
            $context['actor_id'] = documentoNormalizePositiveInt($_SESSION['user']['id'] ?? null);
        }

        if (!isset($context['actor_tipo']) && !isset($context['actor_id'])) {
            $context['actor_tipo'] = 'sistema';
        }

        $ip = auditoriaObtenerIP();
        if ($ip !== '') {
            $context['ip'] = $ip;
        }

        return $context;
    }
}

if (!function_exists('documentoRegisterAuditEvent')) {
    /**
     * @param array<string, mixed> $context
     * @param array<int, array<string, mixed>> $detalles
     */
    function documentoRegisterAuditEvent(string $accion, int $documentId, array $context = [], array $detalles = []): bool
    {
        $accion = trim($accion);

        if ($accion === '' || $documentId <= 0) {
            return false;
        }

        $payload = [
            'accion' => $accion,
            'entidad' => 'rp_empresa_doc',
            'entidad_id' => $documentId,
        ];

        if (isset($context['actor_tipo'])) {
            $payload['actor_tipo'] = $context['actor_tipo'];
        }

        if (array_key_exists('actor_id', $context)) {
            $payload['actor_id'] = $context['actor_id'];
        }

        if (isset($context['ip'])) {
            $payload['ip'] = $context['ip'];
        }

        if ($detalles !== []) {
            $payload['detalles'] = $detalles;
        }

        if (!isset($payload['actor_tipo']) && isset($payload['actor_id'])) {
            $payload['actor_tipo'] = 'usuario';
        }

        return auditoriaRegistrarEvento($payload);
    }
}

if (!function_exists('documentoAuditFetchSnapshot')) {
    /**
     * @return array<string, mixed>|null
     */
    function documentoAuditFetchSnapshot(int $documentId): ?array
    {
        try {
            $pdo = Database::getConnection();

            $sql = <<<'SQL'
                SELECT d.id,
                       d.empresa_id,
                       e.nombre AS empresa_nombre,
                       d.tipo_global_id,
                       d.tipo_personalizado_id,
                       tg.nombre AS tipo_global_nombre,
                       tp.nombre AS tipo_personalizado_nombre,
                       d.ruta,
                       d.estatus,
                       d.observacion
                  FROM rp_empresa_doc AS d
                  JOIN rp_empresa AS e ON e.id = d.empresa_id
                  LEFT JOIN rp_documento_tipo AS tg ON tg.id = d.tipo_global_id
                  LEFT JOIN rp_documento_tipo_empresa AS tp ON tp.id = d.tipo_personalizado_id
                 WHERE d.id = :id
                 LIMIT 1
            SQL;

            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
            $statement->execute();

            $row = $statement->fetch(PDO::FETCH_ASSOC);

            return $row !== false ? $row : null;
        } catch (\Throwable) {
            return null;
        }
    }
}

if (!function_exists('documentoAuditTipoLabel')) {
    function documentoAuditTipoLabel(array $documento): string
    {
        $tipoNombre = '';

        if (isset($documento['tipo_personalizado_nombre']) && trim((string) $documento['tipo_personalizado_nombre']) !== '') {
            $tipoNombre = (string) $documento['tipo_personalizado_nombre'];
        } elseif (isset($documento['tipo_global_nombre']) && trim((string) $documento['tipo_global_nombre']) !== '') {
            $tipoNombre = (string) $documento['tipo_global_nombre'];
        }

        if ($tipoNombre !== '') {
            return $tipoNombre;
        }

        $tipoPersonalizadoId = auditoriaNormalizePositiveInt($documento['tipo_personalizado_id'] ?? null);
        $tipoGlobalId = auditoriaNormalizePositiveInt($documento['tipo_global_id'] ?? null);

        if ($tipoPersonalizadoId !== null) {
            return 'Tipo personalizado #' . $tipoPersonalizadoId;
        }

        if ($tipoGlobalId !== null) {
            return 'Tipo global #' . $tipoGlobalId;
        }

        return 'Documento';
    }
}

if (!function_exists('documentoAuditBuildDetalles')) {
    /**
     * @param array<string, mixed> $documento
     * @param array<string, mixed>|null $previous
     * @return array<int, array<string, mixed>>
     */
    function documentoAuditBuildDetalles(array $documento, ?array $previous = null): array
    {
        $detalles = [];

        $tipoLabel = auditoriaNormalizeDetalleValue(documentoAuditTipoLabel($documento));
        $prevTipoLabel = $previous !== null ? auditoriaNormalizeDetalleValue(documentoAuditTipoLabel($previous)) : null;

        if ($tipoLabel !== null) {
            $detalles[] = [
                'campo' => 'tipo_documento',
                'campo_label' => 'Tipo de documento',
                'valor_anterior' => $prevTipoLabel,
                'valor_nuevo' => $tipoLabel,
            ];
        }

        $estatusActual = auditoriaNormalizeDetalleValue($documento['estatus'] ?? null);
        $estatusPrevio = $previous !== null ? auditoriaNormalizeDetalleValue($previous['estatus'] ?? null) : null;

        if ($estatusActual !== null || $estatusPrevio !== null) {
            $detalles[] = [
                'campo' => 'estatus',
                'campo_label' => 'Estatus',
                'valor_anterior' => $estatusPrevio,
                'valor_nuevo' => $estatusActual,
            ];
        }

        $archivoActual = auditoriaNormalizeDetalleValue(
            isset($documento['ruta']) ? basename((string) $documento['ruta']) : null
        );
        $archivoPrevio = null;

        if ($previous !== null && isset($previous['ruta'])) {
            $archivoPrevio = auditoriaNormalizeDetalleValue(basename((string) $previous['ruta']));
        }

        if ($archivoActual !== null || $archivoPrevio !== null) {
            $detalles[] = [
                'campo' => 'archivo',
                'campo_label' => 'Archivo',
                'valor_anterior' => $archivoPrevio,
                'valor_nuevo' => $archivoActual,
            ];
        }

        $observacionActual = auditoriaNormalizeDetalleValue($documento['observacion'] ?? null);
        $observacionPrev = $previous !== null ? auditoriaNormalizeDetalleValue($previous['observacion'] ?? null) : null;

        if ($observacionActual !== null || $observacionPrev !== null) {
            $detalles[] = [
                'campo' => 'observacion',
                'campo_label' => 'Observacion',
                'valor_anterior' => $observacionPrev,
                'valor_nuevo' => $observacionActual,
            ];
        }

        return $detalles;
    }
}

if (!function_exists('documentoNormalizeStatus')) {
    function documentoNormalizeStatus(?string $estatus): ?string
    {
        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return null;
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        foreach (documentoStatusOptions() as $value => $_label) {
            if ($normalized === $value) {
                return $value;
            }
        }

        return null;
    }
}

if (!function_exists('documentoRenderBadgeClass')) {
    function documentoRenderBadgeClass(?string $estatus): string
    {
        $estatus = trim((string) $estatus);
        $estatus = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        return match ($estatus) {
            'aprobado' => 'badge ok',
            'rechazado' => 'badge err',
            'revision' => 'badge en_revision',
            'pendiente' => 'badge warn',
            default => 'badge secondary',
        };
    }
}

if (!function_exists('documentoRenderBadgeLabel')) {
    function documentoRenderBadgeLabel(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return 'Sin estatus';
        }

        $estatus = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        return documentoStatusOptions()[$estatus] ?? ucfirst($estatus);
    }
}

if (!function_exists('documentoValueOrDefault')) {
    function documentoValueOrDefault(mixed $value, string $fallback = 'N/A'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $value = (string) $value;

            return $value !== '' ? $value : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('documentoFormatDateTime')) {
    function documentoFormatDateTime(?string $value, string $fallback = 'N/A'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00 00:00:00') {
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
