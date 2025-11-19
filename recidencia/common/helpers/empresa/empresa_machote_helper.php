<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_view_helpers.php';

if (!function_exists('empresaViewMachoteHelper')) {
    /**
     * @param mixed $machoteData
     * @return array<string, mixed>|null
     */
    function empresaViewMachoteHelper(mixed $machoteData): ?array
    {
        return empresaViewBuildMachoteData($machoteData);
    }
}
