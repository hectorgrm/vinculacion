<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_empresa.php';
require_once __DIR__ . '/../../controller/dashboard/DashboardEmpresaController.php';

use Residencia\Controller\Dashboard\DashboardEmpresaController;

if (!function_exists('dashboardEmpresaHandler')) {
    /**
     * @return array{
     *     empresasStats: array{total: int, activas: int, revision: int, completadas: int},
     *     empresasError: ?string
     * }
     */
    function dashboardEmpresaHandler(): array
    {
        $defaults = dashboardEmpresaDefaults();

        try {
            $controller = new DashboardEmpresaController();
            $data = $controller->handle();

            if (!is_array($data)) {
                return $defaults;
            }

            return array_merge($defaults, $data);
        } catch (Throwable $exception) {
            $defaults['empresasError'] = dashboardEmpresaErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$dashboardEmpresa = dashboardEmpresaHandler();
$empresasStats = $dashboardEmpresa['empresasStats'];
$empresasError = $dashboardEmpresa['empresasError'];

return $dashboardEmpresa;
