<?php

declare(strict_types=1);

if (!function_exists('dashboardDocumentoDefaults')) {
    /**
     * @return array{
     *     documentosStats: array{
     *         total: int,
     *         aprobados: int,
     *         pendientes: int,
     *         revision: int
     *     },
     *     documentosRevision: array<int, array<string, mixed>>,
     *     documentosError: ?string
     * }
     */
    function dashboardDocumentoDefaults(): array
    {
        return [
            'documentosStats' => [
                'total' => 0,
                'aprobados' => 0,
                'pendientes' => 0,
                'revision' => 0,
            ],
            'documentosRevision' => [],
            'documentosError' => null,
        ];
    }
}

if (!function_exists('dashboardDocumentoErrorMessage')) {
    function dashboardDocumentoErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== ''
            ? $message
            : 'No se pudieron cargar los documentos en revisión.';
    }
}
