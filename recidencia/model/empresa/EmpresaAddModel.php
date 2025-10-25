<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../EmpresaModel.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';

use Common\Model\Database;
use Residencia\Model\EmpresaModel;

class EmpresaAddModel
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
     * @param array<string, string> $data
     */
    public function createEmpresa(array $data): int
    {
        $payload = empresaPrepareForPersistence($data);

        return $this->empresaModel->insert($payload);
    }
}
