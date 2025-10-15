<?php

declare(strict_types=1);

namespace Residencia\Controller;

require_once __DIR__ . '/../model/EmpresaModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use Residencia\Model\EmpresaModel;

class EmpresaController
{
    private EmpresaModel $empresaModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->empresaModel = new EmpresaModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listEmpresas(?string $search = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        return $this->empresaModel->fetchAll($term);
    }
}
