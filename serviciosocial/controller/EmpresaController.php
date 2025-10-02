<?php

declare(strict_types=1);

namespace Serviciosocial\Controller;

require_once __DIR__ . '/../model/EmpresaModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;
use Serviciosocial\Model\EmpresaModel;

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

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresa(int $id): ?array
    {
        if ($id <= 0) {
            throw new InvalidArgumentException('El identificador de la empresa debe ser un número positivo.');
        }

        return $this->empresaModel->findById($id);
    }
}
