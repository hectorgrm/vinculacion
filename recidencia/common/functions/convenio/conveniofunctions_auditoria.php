<?php

declare(strict_types=1);

require_once __DIR__ . '/../conveniofunction.php';
require_once __DIR__ . '/../auditoria/auditoriafunctions.php';

if (!function_exists('convenioAuditoriaDefaults')) {
    /**
     * @return array{historial: array<int, array<string, string>>, error: ?string}
     */
    function convenioAuditoriaDefaults(): array
    {
        return [
            'historial' => [],
            'error' => null,
        ];
    }
}

if (!function_exists('convenioCurrentAuditContext')) {
    /**
     * @return array<string, mixed>
     */
    function convenioCurrentAuditContext(): array
    {
        $context = [];

        if (isset($GLOBALS['residenciaAuthUser']) && is_array($GLOBALS['residenciaAuthUser'])) {
            $context['actor_tipo'] = 'usuario';
            $actorId = convenioAuditoriaNormalizePositiveInt($GLOBALS['residenciaAuthUser']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        } elseif (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            $context['actor_tipo'] = 'usuario';
            $actorId = convenioAuditoriaNormalizePositiveInt($_SESSION['user']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        } elseif (isset($_SESSION['empresa']) && is_array($_SESSION['empresa'])) {
            $context['actor_tipo'] = 'empresa';
            $actorId = convenioAuditoriaNormalizePositiveInt($_SESSION['empresa']['id'] ?? null);

            if ($actorId !== null) {
                $context['actor_id'] = $actorId;
            }
        }

        if (!isset($context['actor_tipo'])) {
            $context['actor_tipo'] = 'sistema';
        }

        $ip = auditoriaObtenerIP();
        if ($ip !== '') {
            $context['ip'] = $ip;
        }

        return $context;
    }
}

if (!function_exists('convenioRegisterAuditEvent')) {
    /**
     * @param array<string, mixed> $context
     * @param array<int, array<string, mixed>> $detalles
     */
    function convenioRegisterAuditEvent(string $accion, int $convenioId, array $context = [], array $detalles = []): bool
    {
        $accion = trim($accion);

        if ($accion === '' || $convenioId <= 0) {
            return false;
        }

        $payload = [
            'accion' => $accion,
            'entidad' => 'rp_convenio',
            'entidad_id' => $convenioId,
        ];

        if (isset($context['actor_tipo'])) {
            $payload['actor_tipo'] = $context['actor_tipo'];
        }

        if (isset($context['actor_id'])) {
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

if (!function_exists('convenioAuditoriaControllerErrorMessage')) {
    function convenioAuditoriaControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim($exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo obtener el historial de auditoría del convenio.';
    }
}

if (!function_exists('convenioAuditoriaNormalizeLimit')) {
    function convenioAuditoriaNormalizeLimit(int $limit): int
    {
        if ($limit <= 0) {
            return 30;
        }

        return min($limit, 100);
    }
}

if (!function_exists('convenioAuditoriaDecorateHistorial')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, string>>
     */
    function convenioAuditoriaDecorateHistorial(array $records): array
    {
        $historial = [];

        foreach ($records as $record) {
            $historial[] = convenioAuditoriaBuildEvento($record);
        }

        return $historial;
    }
}

if (!function_exists('convenioAuditoriaActionForStatusChange')) {
    function convenioAuditoriaActionForStatusChange(string $previousStatus, string $currentStatus): string
    {
        $previousStatus = convenioNormalizeStatus($previousStatus);
        $currentStatus = convenioNormalizeStatus($currentStatus);

        if ($currentStatus === 'Activa' && $previousStatus !== 'Activa') {
            return 'aprobar';
        }

        if ($currentStatus === 'En revisión' && $previousStatus !== 'En revisión') {
            return 'reabrir';
        }

        if ($currentStatus === 'Inactiva' && $previousStatus !== 'Inactiva') {
            return 'rechazar';
        }

        return 'actualizar_estatus';
    }
}

if (!function_exists('convenioAuditoriaBuildEvento')) {
    /**
     * @param array<string, mixed> $record
     * @return array<string, string>
     */
    function convenioAuditoriaBuildEvento(array $record): array
    {
        $fecha = convenioFormatDateTime($record['ts'] ?? null, 'Fecha desconocida');
        $actor = convenioAuditoriaFormatActor(
            $record['actor_tipo'] ?? null,
            $record['actor_id'] ?? null,
            $record['actor_nombre'] ?? null
        );

        $accion = convenioAuditoriaNormalizeAccion($record['accion'] ?? null);
        $descripcion = trim($actor . ' ' . convenioAuditoriaDescribeAccion($accion, $record));

        $estatusSegment = convenioAuditoriaFormatStatusSegment($record);
        if ($estatusSegment !== null) {
            $descripcion .= ' · ' . $estatusSegment;
        }

        $descripcion = trim($descripcion);
        if ($descripcion !== '' && substr($descripcion, -1) !== '.') {
            $descripcion .= '.';
        }

        return [
            'fecha' => $fecha,
            'descripcion' => $descripcion,
        ];
    }
}

if (!function_exists('convenioAuditoriaNormalizeAccion')) {
    function convenioAuditoriaNormalizeAccion(mixed $accion): string
    {
        $accion = trim((string) $accion);

        if ($accion === '') {
            return '';
        }

        if (function_exists('mb_strtolower')) {
            $accion = mb_strtolower($accion, 'UTF-8');
        } else {
            $accion = strtolower($accion);
        }

        return str_replace([' ', '-'], '_', $accion);
    }
}

if (!function_exists('convenioAuditoriaDescribeAccion')) {
    /**
     * @param array<string, mixed> $record
     */
    function convenioAuditoriaDescribeAccion(string $accion, array $record): string
    {
        $subject = convenioAuditoriaDescribeSubject($record);
        $accionOriginal = isset($record['accion']) ? trim((string) $record['accion']) : '';

        return match ($accion) {
            'subir' => 'subió ' . $subject,
            'subir_nueva_version' => 'subió una nueva versi�n de ' . $subject,
            'aprobar' => 'aprobó ' . $subject,
            'reabrir' => 'reabrió ' . $subject,
            'rechazar' => 'rechazó ' . $subject,
            'actualizar_estatus' => 'actualizó el estatus de ' . $subject,
            'actualizar' => 'actualizó ' . $subject,
            'eliminar' => 'eliminó ' . $subject,
            'crear' => 'creó ' . $subject,
            default => 'realizó la acción "' . ($accionOriginal !== '' ? $accionOriginal : $accion) . '" sobre ' . $subject,
        };
    }
}

if (!function_exists('convenioAuditoriaDescribeSubject')) {
    /**
     * @param array<string, mixed> $record
     */
    function convenioAuditoriaDescribeSubject(array $record): string
    {
        $entidad = isset($record['entidad']) ? trim((string) $record['entidad']) : '';
        if ($entidad !== '' && function_exists('mb_strtolower')) {
            $entidad = mb_strtolower($entidad, 'UTF-8');
        } else {
            $entidad = strtolower($entidad);
        }

        $entidadId = convenioAuditoriaNormalizePositiveInt($record['entidad_id'] ?? null);

        if ($entidad === 'rp_convenio') {
            $etiqueta = 'el convenio';
            $folio = isset($record['convenio_folio']) ? trim((string) $record['convenio_folio']) : '';
            $responsable = isset($record['convenio_responsable']) ? trim((string) $record['convenio_responsable']) : '';
            $detalles = [];

            if ($folio !== '') {
                $detalles[] = 'folio ' . $folio;
            }

            if ($responsable !== '') {
                $detalles[] = 'responsable ' . $responsable;
            }

            if ($detalles !== []) {
                $etiqueta .= ' (' . implode(', ', $detalles) . ')';
            } elseif ($entidadId !== null) {
                $etiqueta .= ' #' . $entidadId;
            }

            return $etiqueta;
        }

        if ($entidad === 'rp_empresa_doc') {
            $etiqueta = 'el documento asociado';
            $nombreDocumento = isset($record['documento_tipo_nombre'])
                ? trim((string) $record['documento_tipo_nombre'])
                : '';
            $documentoId = convenioAuditoriaNormalizePositiveInt($record['documento_id'] ?? $entidadId);
            $detalles = [];

            if ($nombreDocumento !== '') {
                $detalles[] = $nombreDocumento;
            }

            if ($documentoId !== null) {
                $detalles[] = '#' . $documentoId;
            }

            if ($detalles !== []) {
                $etiqueta .= ' (' . implode(' · ', $detalles) . ')';
            }

            return $etiqueta;
        }

        if ($entidad === '') {
            return 'el registro';
        }

        return $entidadId !== null
            ? 'la ' . $entidad . ' #' . $entidadId
            : 'la ' . $entidad;
    }
}

if (!function_exists('convenioAuditoriaFormatStatusSegment')) {
    /**
     * @param array<string, mixed> $record
     */
    function convenioAuditoriaFormatStatusSegment(array $record): ?string
    {
        $entidad = isset($record['entidad']) ? trim((string) $record['entidad']) : '';
        if ($entidad !== '' && function_exists('mb_strtolower')) {
            $entidad = mb_strtolower($entidad, 'UTF-8');
        } else {
            $entidad = strtolower($entidad);
        }

        if ($entidad === 'rp_convenio') {
            $estatus = isset($record['convenio_estatus']) ? trim((string) $record['convenio_estatus']) : '';
            if ($estatus !== '') {
                return 'Estatus actual: ' . $estatus;
            }

            return null;
        }

        if ($entidad === 'rp_empresa_doc') {
            $estatus = isset($record['documento_estatus']) ? trim((string) $record['documento_estatus']) : '';
            if ($estatus === '') {
                return null;
            }

            if (function_exists('mb_convert_case')) {
                $estatus = mb_convert_case($estatus, MB_CASE_TITLE, 'UTF-8');
            } else {
                $estatus = ucfirst($estatus);
            }

            return 'Estatus del documento: ' . $estatus;
        }

        return null;
    }
}

if (!function_exists('convenioAuditoriaFormatActor')) {
    function convenioAuditoriaFormatActor(?string $actorTipo, mixed $actorId, ?string $actorNombre): string
    {
        $actorNombre = is_string($actorNombre) ? trim($actorNombre) : '';
        if ($actorNombre !== '') {
            return $actorNombre;
        }

        $actorTipo = is_string($actorTipo) ? trim($actorTipo) : '';
        if ($actorTipo !== '' && function_exists('mb_strtolower')) {
            $actorTipo = mb_strtolower($actorTipo, 'UTF-8');
        } else {
            $actorTipo = strtolower($actorTipo);
        }

        $actorId = convenioAuditoriaNormalizePositiveInt($actorId);

        $tipoLabel = match ($actorTipo) {
            'usuario' => 'Usuario',
            'empresa' => 'Empresa',
            'sistema' => 'Sistema',
            default => ($actorTipo !== '' ? ucfirst($actorTipo) : 'Actor'),
        };

        if ($tipoLabel === 'Sistema' || $actorId === null) {
            return $tipoLabel;
        }

        return $tipoLabel . ' #' . $actorId;
    }
}

if (!function_exists('convenioAuditoriaFormatIp')) {
    function convenioAuditoriaFormatIp(mixed $ip): ?string
    {
        $ip = is_string($ip) ? trim($ip) : '';

        return $ip !== '' ? $ip : null;
    }
}

if (!function_exists('convenioAuditoriaNormalizePositiveInt')) {
    function convenioAuditoriaNormalizePositiveInt(mixed $value): ?int
    {
        if (is_int($value)) {
            return $value > 0 ? $value : null;
        }

        if (is_string($value)) {
            $value = trim($value);
            if ($value !== '' && preg_match('/^[0-9]+$/', $value) === 1) {
                $intValue = (int) $value;

                return $intValue > 0 ? $intValue : null;
            }
        }

        return null;
    }
}

if (!function_exists('convenioAuditoriaFieldLabels')) {
    /**
     * @return array<string, string>
     */
    function convenioAuditoriaFieldLabels(): array
    {
        return [
            'empresa_id' => 'Empresa',
            'folio' => 'Folio',
            'tipo_convenio' => 'Tipo de convenio',
            'responsable_academico' => 'Responsable académico',
            'fecha_inicio' => 'Fecha de inicio',
            'fecha_fin' => 'Fecha de término',
            'observaciones' => 'Observaciones',
            'borrador_path' => 'Archivo de borrador',
            'firmado_path' => 'Archivo firmado',
            'estatus' => 'Estatus',
        ];
    }
}

if (!function_exists('convenioAuditoriaFormatDetalleValor')) {
    function convenioAuditoriaFormatDetalleValor(string $field, mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            $value = trim($value);
        } elseif (is_scalar($value)) {
            $value = trim((string) $value);
        } else {
            return null;
        }

        if ($value === '') {
            return null;
        }

        return match ($field) {
            'fecha_inicio', 'fecha_fin' => convenioFormatDate($value, ''),
            'estatus' => convenioNormalizeStatus($value),
            'empresa_id' => (string) ((int) $value),
            'borrador_path', 'firmado_path' => basename($value),
            default => $value,
        };
    }
}

if (!function_exists('convenioAuditoriaDetectCambios')) {
    /**
     * @param array<string, mixed> $previous
     * @param array<string, mixed> $current
     * @return array{
     *     estatusAnterior: string,
     *     estatusNuevo: string,
     *     estatusCambio: bool,
     *     otrosCambios: bool,
     *     detallesEstatus: array<int, array<string, mixed>>,
     *     detallesCampos: array<int, array<string, mixed>>
     * }
     */
    function convenioAuditoriaDetectCambios(array $previous, array $current): array
    {
        $normalizeString = static function (mixed $value): string {
            if ($value === null) {
                return '';
            }

            if (is_string($value)) {
                return trim($value);
            }

            if (is_scalar($value)) {
                return trim((string) $value);
            }

            return '';
        };

        $estatusAnterior = array_key_exists('estatus', $previous)
            ? convenioNormalizeStatus($normalizeString($previous['estatus']))
            : 'En revisión';
        $estatusNuevo = array_key_exists('estatus', $current)
            ? convenioNormalizeStatus($normalizeString($current['estatus']))
            : 'En revisión';

        $statusChanged = $estatusAnterior !== $estatusNuevo;
        $estatusDetalles = [];

        if ($statusChanged) {
            $estatusDetalles[] = [
                'campo' => 'estatus',
                'campo_label' => convenioAuditoriaFieldLabels()['estatus'],
                'valor_anterior' => auditoriaNormalizeDetalleValue($estatusAnterior),
                'valor_nuevo' => auditoriaNormalizeDetalleValue($estatusNuevo),
            ];
        }

        $fieldsToCompare = [
            'empresa_id',
            'folio',
            'tipo_convenio',
            'responsable_academico',
            'fecha_inicio',
            'fecha_fin',
            'observaciones',
            'borrador_path',
            'firmado_path',
        ];

        $labels = convenioAuditoriaFieldLabels();
        $otherChanges = false;
        $detallesCampos = [];

        foreach ($fieldsToCompare as $field) {
            $previousValue = $previous[$field] ?? null;
            $currentValue = $current[$field] ?? null;

            if ($field === 'empresa_id') {
                $previousValue = convenioAuditoriaNormalizePositiveInt($previousValue);
                $currentValue = convenioAuditoriaNormalizePositiveInt($currentValue);

                if ($previousValue === $currentValue) {
                    continue;
                }

                $otherChanges = true;
                $detallesCampos[] = [
                    'campo' => $field,
                    'campo_label' => $labels[$field] ?? auditoriaNormalizeDetalleLabel(null, $field),
                    'valor_anterior' => auditoriaNormalizeDetalleValue($previousValue),
                    'valor_nuevo' => auditoriaNormalizeDetalleValue($currentValue),
                ];

                continue;
            }

            $normalizedPrevious = $normalizeString($previousValue);
            $normalizedCurrent = $normalizeString($currentValue);

            if ($normalizedPrevious === $normalizedCurrent) {
                continue;
            }

            $otherChanges = true;
            $detallesCampos[] = [
                'campo' => $field,
                'campo_label' => $labels[$field] ?? auditoriaNormalizeDetalleLabel(null, $field),
                'valor_anterior' => auditoriaNormalizeDetalleValue(
                    convenioAuditoriaFormatDetalleValor($field, $previousValue)
                ),
                'valor_nuevo' => auditoriaNormalizeDetalleValue(
                    convenioAuditoriaFormatDetalleValor($field, $currentValue)
                ),
            ];
        }

        return [
            'estatusAnterior' => $estatusAnterior,
            'estatusNuevo' => $estatusNuevo,
            'estatusCambio' => $statusChanged,
            'otrosCambios' => $otherChanges,
            'detallesEstatus' => $estatusDetalles,
            'detallesCampos' => $detallesCampos,
        ];
    }
}

