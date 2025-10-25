<?php

declare(strict_types=1);

if (!function_exists('empresaListDefaults')) {
    /**
     * @return array{
     *     search: string,
     *     empresas: array<int, array<string, mixed>>,
     *     errorMessage: ?string
     * }
     */
    function empresaListDefaults(): array
    {
        return [
            'search' => '',
            'empresas' => [],
            'errorMessage' => null,
        ];
    }
}

if (!function_exists('empresaNormalizeSearch')) {
    function empresaNormalizeSearch(?string $search): string
    {
        $search = trim((string) $search);

        return $search;
    }
}

if (!function_exists('empresaListErrorMessage')) {
    function empresaListErrorMessage(string $message): string
    {
        $message = trim($message);

        return $message !== '' ? $message : 'No se pudo obtener la lista de empresas. Intenta nuevamente mA�s tarde.';
    }
}
