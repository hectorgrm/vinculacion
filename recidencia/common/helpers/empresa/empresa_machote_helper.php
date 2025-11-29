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

if (!function_exists('empresaViewMachoteGenerateUrl')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @param array<string, mixed>|null $machoteData
     */
    function empresaViewMachoteGenerateUrl(array $handlerResult, ?array $machoteData = null): ?string
    {
        if ($machoteData !== null) {
            return null;
        }

        $empresaId = $handlerResult['empresaId'] ?? null;

        if (!is_int($empresaId)) {
            return null;
        }

        $conveniosActivos = $handlerResult['conveniosActivos'] ?? [];

        if (!is_array($conveniosActivos) || $conveniosActivos === []) {
            return null;
        }

        $convenioId = empresaViewDefaultConvenioId($conveniosActivos);

        if ($convenioId === null) {
            return null;
        }

        return '../../handler/machote/machote_generate_handler.php?' . http_build_query([
            'empresa_id' => $empresaId,
            'convenio_id' => $convenioId,
        ]);
    }
}
