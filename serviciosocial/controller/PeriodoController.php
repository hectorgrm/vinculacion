<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/PeriodoModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\PeriodoModel;

class PeriodoController
{
    private PeriodoModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new PeriodoModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(string $searchTerm = ''): array
    {
        $searchTerm = trim($searchTerm);
        if ($searchTerm === '') {
            return $this->model->getAll();
        }

        return $this->model->search($searchTerm);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->model->findById($id);
    }
}
