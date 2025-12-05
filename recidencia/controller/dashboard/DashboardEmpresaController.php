<?php

declare(strict_types=1);

namespace Residencia\Controller\Dashboard;

require_once __DIR__ . '/../../model/dashboard/DashboardEmpresaModel.php';
require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_empresa.php';

use PDOException;
use Residencia\Model\Dashboard\DashboardEmpresaModel;
use RuntimeException;

use function dashboardEmpresaDefaults;
use function dashboardEmpresaErrorMessage;

class DashboardEmpresaController
{
    private DashboardEmpresaModel $model;

    public function __construct(?DashboardEmpresaModel $model = null)
    {
        $this->model = $model ?? new DashboardEmpresaModel();
    }

    /**
     * @return array{total: int, activas: int, revision: int, completadas: int}
     */
    public function getStats(): array
    {
        try {
            $stats = $this->model->fetchStats();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las estadísticas de empresas.', 0, $exception);
        }

        $defaults = dashboardEmpresaDefaults()['empresasStats'];

        return array_merge($defaults, $stats);
    }

    /**
     * @return array{
     *     empresasStats: array{total: int, activas: int, revision: int, completadas: int},
     *     empresasError: ?string
     * }
     */
    public function handle(): array
    {
        $result = dashboardEmpresaDefaults();

        try {
            $result['empresasStats'] = $this->getStats();
        } catch (RuntimeException $exception) {
            $result['empresasError'] = dashboardEmpresaErrorMessage($exception->getMessage());
        }

        return $result;
    }
}
