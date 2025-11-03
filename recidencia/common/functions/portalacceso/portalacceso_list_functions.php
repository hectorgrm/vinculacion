<?php

declare(strict_types=1);

if (!function_exists('portalAccessListStatusOptions')) {
    /**
     * @return array<string, string>
     */
    function portalAccessListStatusOptions(): array
    {
        return [
            '' => 'Todos',
            'activo' => 'Activos',
            'inactivo' => 'Inactivos',
            'expirado' => 'Expirados',
        ];
    }
}

if (!function_exists('portalAccessListDefaults')) {
    /**
     * @return array{
     *     search: string,
     *     status: string,
     *     statusOptions: array<string, string>,
     *     portales: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    function portalAccessListDefaults(): array
    {
        return [
            'search' => '',
            'status' => '',
            'statusOptions' => portalAccessListStatusOptions(),
            'portales' => [],
            'errorMessage' => null,
        ];
    }
}

if (!function_exists('portalAccessListNormalizeSearch')) {
    function portalAccessListNormalizeSearch(?string $search): string
    {
        $search = trim((string) $search);

        return $search;
    }
}

if (!function_exists('portalAccessListNormalizeStatus')) {
    function portalAccessListNormalizeStatus(?string $status): string
    {
        $status = trim((string) $status);

        if ($status === '') {
            return '';
        }

        $status = strtolower($status);

        return array_key_exists($status, portalAccessListStatusOptions()) ? $status : '';
    }
}

if (!function_exists('portalAccessListErrorMessage')) {
    function portalAccessListErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== '' ? $message : 'No se pudo obtener la lista de accesos. Intenta nuevamente más tarde.';
    }
}

if (!function_exists('portalAccessResolveStatus')) {
    /**
     * @param mixed $activo
     */
    function portalAccessResolveStatus($activo, ?string $expiracion): string
    {
        $isActive = (string) $activo === '1';

        if ($expiracion !== null) {
            $expiracion = trim($expiracion);

            if ($expiracion !== '') {
                $timestamp = strtotime($expiracion);

                if ($timestamp !== false && $timestamp <= time()) {
                    return 'expirado';
                }
            }
        }

        return $isActive ? 'activo' : 'inactivo';
    }
}

if (!function_exists('portalAccessStatusLabel')) {
    function portalAccessStatusLabel(string $status): string
    {
        switch ($status) {
            case 'activo':
                return 'Activo';
            case 'inactivo':
                return 'Inactivo';
            case 'expirado':
                return 'Expirado';
            default:
                return 'Desconocido';
        }
    }
}

if (!function_exists('portalAccessStatusBadgeClass')) {
    function portalAccessStatusBadgeClass(string $status): string
    {
        switch ($status) {
            case 'activo':
                return 'badge ok';
            case 'inactivo':
                return 'badge warn';
            case 'expirado':
                return 'badge err';
            default:
                return 'badge secondary';
        }
    }
}

if (!function_exists('portalAccessFormatDateTime')) {
    function portalAccessFormatDateTime(?string $value): string
    {
        if ($value === null) {
            return '—';
        }

        $value = trim($value);

        if ($value === '') {
            return '—';
        }

        $timestamp = strtotime($value);

        if ($timestamp === false) {
            return $value;
        }

        return date('Y-m-d H:i', $timestamp);
    }
}

if (!function_exists('portalAccessFormatNip')) {
    function portalAccessFormatNip(?string $nip): string
    {
        $nip = $nip !== null ? trim($nip) : '';

        return $nip !== '' ? $nip : '—';
    }
}
