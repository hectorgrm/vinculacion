<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_view_helpers.php';

if (!function_exists('empresaViewDocumentosHelper')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @return array<string, mixed>
     */
    function empresaViewDocumentosHelper(array $handlerResult): array
    {
        return empresaViewBuildDocumentosData($handlerResult);
    }
}
