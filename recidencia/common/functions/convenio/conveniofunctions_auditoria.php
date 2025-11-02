<?php

declare(strict_types=1);

require_once __DIR__ . '/../conveniofunction.php';

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

        $ip = convenioAuditoriaFormatIp($record['ip'] ?? null);
        if ($ip !== null) {
            $descripcion .= ' desde la IP ' . $ip;
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
            'subir_nueva_version' => 'subió una nueva versión de ' . $subject,
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
            $version = isset($record['convenio_version']) ? trim((string) $record['convenio_version']) : '';
            $detalles = [];

            if ($folio !== '') {
                $detalles[] = 'folio ' . $folio;
            }

            if ($version !== '') {
                $detalles[] = 'versión ' . $version;
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
