<?php

declare(strict_types=1);

if (!function_exists('empresaPerfilNormalizeString')) {
    function empresaPerfilNormalizeString(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $value = trim($value);

        return $value;
    }
}

if (!function_exists('empresaPerfilNormalizeNullableString')) {
    function empresaPerfilNormalizeNullableString(?string $value): ?string
    {
        $normalized = empresaPerfilNormalizeString($value);

        return $normalized === '' ? null : $normalized;
    }
}

if (!function_exists('empresaPerfilStatusOptions')) {
    /**
     * @return array<string, string>
     */
    function empresaPerfilStatusOptions(): array
    {
        return [
            'activa' => 'Activa',
            'en revisión' => 'En revisión',
            'en revision' => 'En revisión',
            'inactiva' => 'Inactiva',
            'suspendida' => 'Suspendida',
        ];
    }
}

if (!function_exists('empresaPerfilNormalizeStatus')) {
    function empresaPerfilNormalizeStatus(?string $status): string
    {
        $status = empresaPerfilNormalizeString($status);

        if ($status === '') {
            return 'En revisión';
        }

        $normalized = function_exists('mb_strtolower')
            ? mb_strtolower($status, 'UTF-8')
            : strtolower($status);

        $options = empresaPerfilStatusOptions();

        if (array_key_exists($normalized, $options)) {
            return $options[$normalized];
        }

        return 'En revisión';
    }
}

if (!function_exists('empresaPerfilFormatWebsite')) {
    function empresaPerfilFormatWebsite(string $url): string
    {
        $url = empresaPerfilNormalizeString($url);

        if ($url === '') {
            return '';
        }

        if (!preg_match('/^https?:\/\//i', $url)) {
            $url = 'https://' . $url;
        }

        return $url;
    }
}

if (!function_exists('empresaPerfilBadgeClass')) {
    function empresaPerfilBadgeClass(?string $status): string
    {
        $normalized = empresaPerfilNormalizeStatus($status);

        return match ($normalized) {
            'Activa' => 'ok',
            'En revisión' => 'warn',
            'Inactiva', 'Suspendida' => 'danger',
            default => 'secondary',
        };
    }
}

if (!function_exists('empresaPerfilBadgeLabel')) {
    function empresaPerfilBadgeLabel(?string $status): string
    {
        return empresaPerfilNormalizeStatus($status);
    }
}
