<?php

declare(strict_types=1);

if (!function_exists('dashboardEmpresaDefaults')) {
    /**
     * @return array{
     *     empresasStats: array{
     *         total: int,
     *         activas: int,
     *         revision: int,
     *         completadas: int
     *     },
     *     empresasError: ?string
     * }
     */
    function dashboardEmpresaDefaults(): array
    {
        return [
            'empresasStats' => [
                'total' => 0,
                'activas' => 0,
                'revision' => 0,
                'completadas' => 0,
            ],
            'empresasError' => null,
        ];
    }
}

if (!function_exists('dashboardEmpresaErrorMessage')) {
    function dashboardEmpresaErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== ''
            ? $message
            : 'No se pudieron cargar las estadísticas de empresas.';
    }
}
