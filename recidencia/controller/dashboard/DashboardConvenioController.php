<?php

declare(strict_types=1);

namespace Residencia\Controller\Dashboard;

require_once __DIR__ . '/../../model/dashboard/DashboardConvenioModel.php';
require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_convenio.php';

use PDOException;
use Residencia\Model\Dashboard\DashboardConvenioModel;
use RuntimeException;

use function dashboardConvenioDefaults;
use function dashboardConvenioErrorMessage;

class DashboardConvenioController
{
    private DashboardConvenioModel $model;

    public function __construct(?DashboardConvenioModel $model = null)
    {
        $this->model = $model ?? new DashboardConvenioModel();
    }

    /**
     * @return array{total: int, activos: int, revision: int, archivados: int, completados: int}
     */
    public function getStats(): array
    {
        try {
            $stats = $this->model->fetchStats();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las estadísticas de convenios.', 0, $exception);
        }

        $defaults = dashboardConvenioDefaults()['conveniosStats'];

        return array_merge($defaults, $stats);
    }

    /**
     * @return array{
     *     conveniosStats: array{total: int, activos: int, revision: int, archivados: int, completados: int},
     *     conveniosError: ?string
     * }
     */
    public function handle(): array
    {
        $result = dashboardConvenioDefaults();

        try {
            $result['conveniosStats'] = $this->getStats();
        } catch (RuntimeException $exception) {
            $result['conveniosError'] = dashboardConvenioErrorMessage($exception->getMessage());
        }

        return $result;
    }
}
