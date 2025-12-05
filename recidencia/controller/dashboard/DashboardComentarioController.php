<?php

declare(strict_types=1);

namespace Residencia\Controller\Dashboard;

require_once __DIR__ . '/../../model/dashboard/DashboardComentarioModel.php';
require_once __DIR__ . '/../../common/functions/dashboard/dashboardfunctions_comentario.php';

use PDOException;
use Residencia\Model\Dashboard\DashboardComentarioModel;
use RuntimeException;

use function dashboardComentarioDefaults;
use function dashboardComentarioErrorMessage;

class DashboardComentarioController
{
    private DashboardComentarioModel $model;

    public function __construct(?DashboardComentarioModel $model = null)
    {
        $this->model = $model ?? new DashboardComentarioModel();
    }

    /**
     * @return array{total: int, abiertos: int, resueltos: int, archivados: int, revisiones: int}
     */
    public function getStats(): array
    {
        try {
            $stats = $this->model->fetchStats();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los comentarios en revisión.', 0, $exception);
        }

        $defaults = dashboardComentarioDefaults()['comentariosStats'];

        return array_merge($defaults, $stats);
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getComentarios(int $limit = 8): array
    {
        try {
            return $this->model->fetchComentarios($limit);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los comentarios en revisión.', 0, $exception);
        }
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getRevisiones(int $limit = 6): array
    {
        try {
            return $this->model->fetchRevisiones($limit);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las revisiones de machote.', 0, $exception);
        }
    }


    /**
     * @return array{
     *     comentariosStats: array{total: int, abiertos: int, resueltos: int, archivados: int, revisiones: int},
     *     comentariosRevision: array<int, array<string, mixed>>,
     *     comentariosError: ?string
     * }
     */
    public function handle(): array
    {
        $result = dashboardComentarioDefaults();

        try {
            $result['comentariosStats'] = $this->getStats();
            $result['comentariosRevision'] = $this->getRevisiones();
        } catch (RuntimeException $exception) {
            $result['comentariosError'] = dashboardComentarioErrorMessage($exception->getMessage());
        }

        return $result;
    }
}
