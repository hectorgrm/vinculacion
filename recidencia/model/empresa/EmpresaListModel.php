<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../EmpresaModel.php';
require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use Residencia\Model\EmpresaModel;

class EmpresaListModel
{
    private EmpresaModel $empresaModel;

    public function __construct(?EmpresaModel $empresaModel = null)
    {
        if ($empresaModel instanceof EmpresaModel) {
            $this->empresaModel = $empresaModel;
            return;
        }

        $pdo = Database::getConnection();
        $this->empresaModel = new EmpresaModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresas(?string $search = null): array
    {
        return $this->empresaModel->fetchAll($search);
    }
}
