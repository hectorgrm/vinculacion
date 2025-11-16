<?php

declare(strict_types=1);

require_once __DIR__ . '/auditoriafunctions.php';
require_once __DIR__ . '/../convenio/conveniofunctions_auditoria.php';

if (!function_exists('empresaAuditoriaDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     items: array<int, array<string, mixed>>,
     *     controllerError: ?string,
     *     inputError: ?string
     * }
     */
    function empresaAuditoriaDefaults(): array
    {
        return [
            'empresaId' => null,
            'items' => [],
            'controllerError' => null,
            'inputError' => null,
        ];
    }
}

if (!function_exists('empresaAuditoriaNormalizeId')) {
    function empresaAuditoriaNormalizeId(mixed $value): ?int
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

if (!function_exists('empresaAuditoriaInputErrorMessage')) {
    function empresaAuditoriaInputErrorMessage(): string
    {
        return 'No se proporcionó un identificador de empresa válido para el historial.';
    }
}

if (!function_exists('empresaAuditoriaControllerErrorMessage')) {
    function empresaAuditoriaControllerErrorMessage(\Throwable $exception): string
    {
        $message = trim((string) $exception->getMessage());

        return $message !== ''
            ? $message
            : 'No se pudo obtener el historial de auditoría de la empresa.';
    }
}

if (!function_exists('empresaAuditoriaDecorateRegistros')) {
    /**
     * @param array<int, array<string, mixed>> $records
     * @return array<int, array<string, mixed>>
     */
    function empresaAuditoriaDecorateRegistros(array $records): array
    {
        $items = [];

        if (!function_exists('convenioAuditoriaBuildEvento')) {
            return $items;
        }

        $auditoriaIds = [];

        foreach ($records as $record) {
            if (!is_array($record) || !array_key_exists('id', $record)) {
                continue;
            }

            $id = auditoriaNormalizePositiveInt($record['id']);
            if ($id !== null) {
                $auditoriaIds[] = $id;
            }
        }

        $detallesMap = $auditoriaIds !== []
            ? auditoriaFetchDetallesByAuditoriaIds($auditoriaIds)
            : [];

        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $evento = convenioAuditoriaBuildEvento($record);
            $ip = isset($record['ip']) ? trim((string) $record['ip']) : '';
            $actorTipo = isset($record['actor_tipo']) ? (string) $record['actor_tipo'] : null;
            $actorId = $record['actor_id'] ?? null;
            $actorNombre = isset($record['actor_nombre']) ? trim((string) $record['actor_nombre']) : '';
            $auditoriaId = auditoriaNormalizePositiveInt($record['id'] ?? null);
            $detalles = $auditoriaId !== null && isset($detallesMap[$auditoriaId])
                ? $detallesMap[$auditoriaId]
                : [];

            if (function_exists('convenioAuditoriaNormalizePositiveInt')) {
                $actorId = convenioAuditoriaNormalizePositiveInt($actorId);
            } elseif (!is_int($actorId)) {
                $actorId = is_numeric($actorId) ? (int) $actorId : null;
            }

            $actorLabel = null;
            if (function_exists('convenioAuditoriaFormatActor')) {
                $actorLabel = convenioAuditoriaFormatActor($actorTipo, $actorId, $actorNombre);
            } elseif ($actorNombre !== '') {
                $actorLabel = $actorNombre;
            }

            $items[] = [
                'fecha' => $evento['fecha'],
                'mensaje' => $evento['descripcion'],
                'ip' => $ip,
                'actor_tipo' => $actorTipo,
                'actor_id' => $actorId,
                'actor_nombre' => $actorNombre !== '' ? $actorNombre : null,
                'actor_label' => $actorLabel,
                'detalles' => $detalles,
            ];
        }

        return $items;
    }
}
