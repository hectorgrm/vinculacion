<?php

declare(strict_types=1);

if (!function_exists('dashboardComentarioDefaults')) {
    /**
     * @return array{
     *     comentariosStats: array{total: int, abiertos: int, resueltos: int},
     *     comentariosRevision: array<int, array<string, mixed>>,
     *     comentariosError: ?string
     * }
     */
    function dashboardComentarioDefaults(): array
    {
        return [
            'comentariosStats' => [
                'total' => 0,
                'abiertos' => 0,
                'resueltos' => 0,
            ],
            'comentariosRevision' => [],
            'comentariosError' => null,
        ];
    }
}

if (!function_exists('dashboardComentarioErrorMessage')) {
    function dashboardComentarioErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== ''
            ? $message
            : 'No se pudieron cargar los comentarios en revisión.';
    }
}
