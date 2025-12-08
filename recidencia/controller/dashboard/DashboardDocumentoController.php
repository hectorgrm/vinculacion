<?php

declare(strict_types=1);

namespace Residencia\Controller\Dashboard;

require_once __DIR__ . '/../../model/dashboard/DashboardDocumentoModel.php';
require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_documento.php';

use PDOException;
use Residencia\Model\Dashboard\DashboardDocumentoModel;
use RuntimeException;

use function dashboardDocumentoDefaults;
use function dashboardDocumentoErrorMessage;

class DashboardDocumentoController
{
    private DashboardDocumentoModel $model;

    public function __construct(?DashboardDocumentoModel $model = null)
    {
        $this->model = $model ?? new DashboardDocumentoModel();
    }

    /**
     * @return array{total: int, aprobados: int, pendientes: int, revision: int}
     */
    public function getStats(): array
    {
        try {
            $stats = $this->model->fetchStats();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las estadísticas de documentos.', 0, $exception);
        }

        $defaults = dashboardDocumentoDefaults()['documentosStats'];

        return array_merge($defaults, $stats);
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getDocsEnRevision(int $limit = 8): array
    {
        try {
            return $this->model->fetchEnRevision($limit);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los documentos en revisión.', 0, $exception);
        }
    }

    /**
     * @return array{
     *     documentosStats: array{total: int, aprobados: int, pendientes: int, revision: int},
     *     documentosRevision: array<int, array<string, mixed>>,
     *     documentosError: ?string
     * }
     */
    public function handle(): array
    {
        $result = dashboardDocumentoDefaults();

        try {
            $result['documentosStats'] = $this->getStats();
            $result['documentosRevision'] = $this->getDocsEnRevision();
        } catch (RuntimeException $exception) {
            $result['documentosError'] = dashboardDocumentoErrorMessage($exception->getMessage());
        }

        return $result;
    }
}
