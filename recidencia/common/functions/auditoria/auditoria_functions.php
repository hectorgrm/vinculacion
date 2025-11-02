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

        foreach ($records as $record) {
            if (!is_array($record)) {
                continue;
            }

            $evento = convenioAuditoriaBuildEvento($record);
            $ip = isset($record['ip']) ? trim((string) $record['ip']) : '';

            $items[] = [
                'fecha' => $evento['fecha'],
                'mensaje' => $evento['descripcion'],
                'ip' => $ip,
            ];
        }

        return $items;
    }
}
