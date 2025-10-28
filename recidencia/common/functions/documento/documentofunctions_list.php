<?php

declare(strict_types=1);

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
            'rechazado' => 'Rechazado',
        ];
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
