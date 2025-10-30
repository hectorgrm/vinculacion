<?php

declare(strict_types=1);

require_once __DIR__ . '/../empresafunction.php';

if (!function_exists('empresaViewDefaults')) {
    /**
     * @return array{
     *     empresaId: ?int,
     *     empresa: ?array<string, mixed>,
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

        return $empresa;
    }
}
