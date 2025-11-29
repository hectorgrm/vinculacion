<?php

declare(strict_types=1);

require_once __DIR__ . '/empresa_documentos_helper.php';
require_once __DIR__ . '/empresa_machote_helper.php';
require_once __DIR__ . '/empresa_auditoria_helper.php';
require_once __DIR__ . '/empresa_view_helpers.php';

if (!function_exists('empresaViewTemplateHelper')) {
    /**
     * @param array<string, mixed> $handlerResult
     * @param array<string, mixed> $auditoriaHandlerResult
     * @return array<string, mixed>
     */
    function empresaViewTemplateHelper(array $handlerResult, array $auditoriaHandlerResult): array
    {
        $headerData = empresaViewBuildHeaderData($handlerResult);
        $documentosData = empresaViewDocumentosHelper($handlerResult);
        $machoteData = empresaViewMachoteHelper($handlerResult['machoteData'] ?? null);
        $machoteGenerateUrl = empresaViewMachoteGenerateUrl($handlerResult, $machoteData);
        $auditoriaData = empresaViewAuditoriaHelper($auditoriaHandlerResult);
        $portalData = empresaViewPortalAccessHelper($handlerResult);

        return array_merge(
            $headerData,
            $documentosData,
            $portalData,
            [
                'machoteData' => $machoteData,
                'machoteGenerateUrl' => $machoteGenerateUrl,
                'auditoriaItems' => $auditoriaData['items'],
                'auditoriaControllerError' => $auditoriaData['controllerError'],
                'auditoriaInputError' => $auditoriaData['inputError'],
                'auditoriaHasOverflow' => $auditoriaData['hasOverflow'],
                'auditoriaVisibleLimit' => $auditoriaData['visibleLimit'],
            ]
        );
    }
}
