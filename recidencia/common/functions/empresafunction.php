<?php

declare(strict_types=1);

if (!function_exists('renderBadgeClass')) {
    function renderBadgeClass(?string $estatus): string
    {
        $value = trim((string) $estatus);

        if ($value !== '' && function_exists('mb_strtolower')) {
            $value = mb_strtolower($value, 'UTF-8');
        } else {
            $value = strtolower($value);
        }

        switch ($value) {
            case 'activa':
                return 'badge ok';
            case 'en revisión':
            case 'en revision':
                return 'badge secondary';
            case 'inactiva':
                return 'badge warn';
            case 'suspendida':
                return 'badge err';
            default:
                return 'badge secondary';
        }
    }
}

if (!function_exists('renderBadgeLabel')) {
    function renderBadgeLabel(?string $estatus): string
    {
        $estatus = trim((string) $estatus);

        return $estatus !== '' ? $estatus : 'Sin especificar';
    }
}
