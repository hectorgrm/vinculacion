<?php

declare(strict_types=1);

if (!function_exists('estudianteNormalizeString')) {
    function estudianteNormalizeString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        return trim($value);
    }
}

if (!function_exists('estudianteNormalizeStatus')) {
    function estudianteNormalizeStatus(?string $status): string
    {
        $status = estudianteNormalizeString($status);

        if ($status === '') {
            return 'Activo';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($status, 'UTF-8')
            : strtolower($status);

        return match ($normalized) {
            'finalizado' => 'Finalizado',
            'inactivo' => 'Inactivo',
            default => 'Activo',
        };
    }
}

if (!function_exists('estudianteBadgeClass')) {
    function estudianteBadgeClass(?string $status): string
    {
        $normalized = estudianteNormalizeStatus($status);

        return match ($normalized) {
            'Finalizado' => 'ok',
            'Inactivo' => 'warn',
            default => 'info',
        };
    }
}

if (!function_exists('estudianteBadgeLabel')) {
    function estudianteBadgeLabel(?string $status): string
    {
        return estudianteNormalizeStatus($status);
    }
}

if (!function_exists('estudianteHydrateRecord')) {
    /**
     * @param array<string, mixed> $record
     *
     * @return array<string, mixed>
     */
    function estudianteHydrateRecord(array $record): array
    {
        return [
            'id' => isset($record['id']) ? (int) $record['id'] : 0,
            'nombre' => estudianteNormalizeString($record['nombre'] ?? ''),
            'apellido_paterno' => estudianteNormalizeString($record['apellido_paterno'] ?? ''),
            'apellido_materno' => estudianteNormalizeString($record['apellido_materno'] ?? ''),
            'matricula' => estudianteNormalizeString($record['matricula'] ?? ''),
            'carrera' => estudianteNormalizeString($record['carrera'] ?? ''),
            'correo_institucional' => estudianteNormalizeString($record['correo_institucional'] ?? ''),
            'telefono' => estudianteNormalizeString($record['telefono'] ?? ''),
            'empresa_id' => isset($record['empresa_id']) ? (int) $record['empresa_id'] : 0,
            'convenio_id' => isset($record['convenio_id']) && $record['convenio_id'] !== null
                ? (int) $record['convenio_id']
                : null,
            'estatus' => estudianteNormalizeStatus($record['estatus'] ?? null),
            'creado_en' => isset($record['creado_en']) ? (string) $record['creado_en'] : '',
        ];
    }
}

if (!function_exists('estudianteEmptyRecord')) {
    /**
     * @return array<string, mixed>
     */
    function estudianteEmptyRecord(): array
    {
        return [
            'id' => 0,
            'nombre' => '',
            'apellido_paterno' => '',
            'apellido_materno' => '',
            'matricula' => '',
            'carrera' => '',
            'correo_institucional' => '',
            'telefono' => '',
            'empresa_id' => 0,
            'convenio_id' => null,
            'estatus' => 'Activo',
            'creado_en' => '',
        ];
    }
}

if (!function_exists('estudianteNombreCompleto')) {
    /**
     * @param array<string, mixed> $record
     */
    function estudianteNombreCompleto(array $record): string
    {
        $nombre = estudianteNormalizeString($record['nombre'] ?? '');
        $apellidoPaterno = estudianteNormalizeString($record['apellido_paterno'] ?? '');
        $apellidoMaterno = estudianteNormalizeString($record['apellido_materno'] ?? '');

        return trim($nombre . ' ' . $apellidoPaterno . ' ' . $apellidoMaterno);
    }
}

if (!function_exists('estudianteSplitPorEstatus')) {
    /**
     * @param array<int, array<string, mixed>> $registros
     *
     * @return array{
     *     activos: array<int, array<string, mixed>>,
     *     historico: array<int, array<string, mixed>>,
     *     finalizados: int
     * }
     */
    function estudianteSplitPorEstatus(array $registros): array
    {
        $activos = [];
        $historico = [];
        $finalizados = 0;

        foreach ($registros as $registro) {
            $estatus = estudianteNormalizeStatus($registro['estatus'] ?? null);
            $registro['estatus'] = $estatus;

            if ($estatus === 'Activo') {
                $activos[] = $registro;
            } else {
                if ($estatus === 'Finalizado') {
                    $finalizados++;
                }

                $historico[] = $registro;
            }
        }

        return [
            'activos' => $activos,
            'historico' => $historico,
            'finalizados' => $finalizados,
        ];
    }
}
