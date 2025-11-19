<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_view_helpers.php';

if (!function_exists('empresaViewAuditoriaHelper')) {
    /**
     * @param array<string, mixed> $auditoriaHandlerResult
     * @return array{items: array<int, array<string, mixed>>, controllerError: ?string, inputError: ?string, hasOverflow: bool, visibleLimit: int}
     */
    function empresaViewAuditoriaHelper(array $auditoriaHandlerResult): array
    {
        $items = $auditoriaHandlerResult['items'] ?? [];

        if (!is_array($items)) {
            $items = [];
        }

        $decoratedItems = empresaViewDecorateAuditoriaItems($items);

        return [
            'items' => $decoratedItems['items'],
            'controllerError' => $auditoriaHandlerResult['controllerError'] ?? null,
            'inputError' => $auditoriaHandlerResult['inputError'] ?? null,
            'hasOverflow' => $decoratedItems['hasOverflow'],
            'visibleLimit' => $decoratedItems['visibleLimit'],
        ];
    }
}
