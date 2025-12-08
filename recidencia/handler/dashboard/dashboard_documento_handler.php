<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_documento.php';
require_once __DIR__ . '/../../controller/dashboard/DashboardDocumentoController.php';

use Residencia\Controller\Dashboard\DashboardDocumentoController;

if (!function_exists('dashboardDocumentoHandler')) {
    /**
     * @return array{
     *     documentosStats: array{total: int, aprobados: int, pendientes: int, revision: int},
     *     documentosRevision: array<int, array<string, mixed>>,
     *     documentosError: ?string
     * }
     */
    function dashboardDocumentoHandler(): array
    {
        $defaults = dashboardDocumentoDefaults();

        try {
            $controller = new DashboardDocumentoController();
            $data = $controller->handle();

            if (!is_array($data)) {
                return $defaults;
            }

            return array_merge($defaults, $data);
        } catch (Throwable $exception) {
            $defaults['documentosError'] = dashboardDocumentoErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$dashboardDocumentos = dashboardDocumentoHandler();
$docsStats = $dashboardDocumentos['documentosStats'];
$docsEnRevision = $dashboardDocumentos['documentosRevision'];
$docsError = $dashboardDocumentos['documentosError'];

return $dashboardDocumentos;
