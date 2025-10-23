<?php

declare(strict_types=1);

if (!function_exists('convenioStatusOptions')) {
    /**
     * @return array<int, string>
     */
    function convenioStatusOptions(): array
    {
        return ['Activa', 'En revisión', 'Inactiva', 'Suspendida'];
    }
}

if (!function_exists('convenioNormalizeStatus')) {
    function convenioNormalizeStatus(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return 'En revisión';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);

        foreach (convenioStatusOptions() as $option) {
            $optionNormalized = function_exists('mb_strtolower')
                ? mb_strtolower($option, 'UTF-8')
                : strtolower($option);

            if ($normalized === $optionNormalized) {
                return $option;
            }
        }

        return 'En revisión';
    }
}

if (!function_exists('convenioRenderBadgeClass')) {
    function convenioRenderBadgeClass(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        if ($estatus !== '' && function_exists('mb_strtolower')) {
            $estatus = mb_strtolower($estatus, 'UTF-8');
        } else {
            $estatus = strtolower($estatus);
        }

        return match ($estatus) {
            'activa' => 'badge ok',
            'en revisión', 'en revision' => 'badge secondary',
            'inactiva' => 'badge warn',
            'suspendida' => 'badge err',
            default => 'badge secondary',
        };
    }
}

if (!function_exists('convenioRenderBadgeLabel')) {
    function convenioRenderBadgeLabel(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        return $estatus !== '' ? $estatus : 'Sin especificar';
    }
}

if (!function_exists('convenioValueOrDefault')) {
    function convenioValueOrDefault(mixed $value, string $fallback = '—'): string
    {
        if ($value === null) {
            return $fallback;
        }

        if (is_string($value)) {
            $value = trim($value);

            return $value !== '' ? $value : $fallback;
        }

        if (is_scalar($value)) {
            $stringValue = (string) $value;

            return $stringValue !== '' ? $stringValue : $fallback;
        }

        return $fallback;
    }
}

if (!function_exists('convenioFormatDate')) {
    function convenioFormatDate(?string $value, string $fallback = '—'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00') {
            return $fallback;
        }

        try {
            $date = new DateTimeImmutable($value);
        } catch (Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y');
    }
}

if (!function_exists('convenioFormatDateTime')) {
    function convenioFormatDateTime(?string $value, string $fallback = '—'): string
    {
        $value = trim((string) $value);

        if ($value === '' || $value === '0000-00-00 00:00:00') {
            return $fallback;
        }

        try {
            $date = new DateTimeImmutable($value);
        } catch (Throwable) {
            return $fallback;
        }

        return $date->format('d/m/Y H:i');
    }
}
