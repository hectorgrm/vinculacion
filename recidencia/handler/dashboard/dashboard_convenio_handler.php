<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_convenio.php';
require_once __DIR__ . '/../../controller/dashboard/DashboardConvenioController.php';

use Residencia\Controller\Dashboard\DashboardConvenioController;

if (!function_exists('dashboardConvenioHandler')) {
    /**
     * @return array{
     *     conveniosStats: array{total: int, activos: int, revision: int, archivados: int, completados: int},
     *     conveniosError: ?string
     * }
     */
    function dashboardConvenioHandler(): array
    {
        $defaults = dashboardConvenioDefaults();

        try {
            $controller = new DashboardConvenioController();
            $data = $controller->handle();

            if (!is_array($data)) {
                return $defaults;
            }

            return array_merge($defaults, $data);
        } catch (Throwable $exception) {
            $defaults['conveniosError'] = dashboardConvenioErrorMessage($exception->getMessage());

            return $defaults;
        }
    }
}

$dashboardConvenio = dashboardConvenioHandler();
$conveniosStats = $dashboardConvenio['conveniosStats'];
$conveniosError = $dashboardConvenio['conveniosError'];

return $dashboardConvenio;
