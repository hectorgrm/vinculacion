<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/GestEstudianteModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Serviciosocial\Model\GestEstudianteModel;

class GestEstudianteController
{
    private GestEstudianteModel $model;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->model = new GestEstudianteModel($pdo);
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
}
