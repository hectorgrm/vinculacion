<?php

declare(strict_types=1);

require_once __DIR__ . '/../../functions/auditoria/auditoriafunctions.php';

if (!function_exists('auditoriaDetectarCambios')) {
    /**
     * @param array<string, mixed> $previo
     * @param array<string, mixed> $nuevo
     * @param array<string, string> $labels
     * @return array<int, array<string, mixed>>
     */
    function auditoriaDetectarCambios(array $previo, array $nuevo, array $labels = []): array
    {
        $detalles = [];
        $allKeys = array_unique(array_merge(array_keys($previo), array_keys($nuevo)));

        foreach ($allKeys as $campo) {
            $valorAnterior = $previo[$campo] ?? null;
            $valorNuevo = $nuevo[$campo] ?? null;

            if ($valorAnterior === $valorNuevo) {
                continue;
            }

            $etiqueta = $labels[$campo] ?? null;
            $detalles[] = [
                'campo' => (string) $campo,
                'campo_label' => auditoriaNormalizeDetalleLabel($etiqueta, (string) $campo),
                'valor_anterior' => auditoriaStringifyValor($valorAnterior),
                'valor_nuevo' => auditoriaStringifyValor($valorNuevo),
            ];
        }

        return $detalles;
    }
}

if (!function_exists('auditoriaStringifyValor')) {
    function auditoriaStringifyValor(mixed $valor): ?string
    {
        if ($valor === null) {
            return null;
        }

        if (is_bool($valor)) {
            return $valor ? 'true' : 'false';
        }

        if (is_scalar($valor)) {
            $stringValue = trim((string) $valor);

            return $stringValue === '' ? null : $stringValue;
        }

        if (is_array($valor)) {
            $json = json_encode($valor);

            return $json !== false ? $json : null;
        }

        return null;
    }
}
