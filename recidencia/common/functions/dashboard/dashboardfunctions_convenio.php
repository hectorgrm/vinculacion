<?php

declare(strict_types=1);

if (!function_exists('dashboardConvenioDefaults')) {
    /**
     * @return array{
     *     conveniosStats: array{
     *         total: int,
     *         activos: int,
     *         revision: int,
     *         archivados: int,
     *         completados: int
     *     },
     *     conveniosError: ?string
     * }
     */
    function dashboardConvenioDefaults(): array
    {
        return [
            'conveniosStats' => [
                'total' => 0,
                'activos' => 0,
                'revision' => 0,
                'archivados' => 0,
                'completados' => 0,
            ],
            'conveniosError' => null,
        ];
    }
}

if (!function_exists('dashboardConvenioErrorMessage')) {
    function dashboardConvenioErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== ''
            ? $message
            : 'No se pudieron cargar las estadísticas de convenios.';
    }
}
