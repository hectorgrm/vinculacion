<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../EmpresaModel.php';
require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/empresafunction.php';

use Common\Model\Database;
use Residencia\Model\EmpresaModel;
use function empresaPrepareForPersistence;

class EmpresaEditModel
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

    public function findById(int $empresaId): ?array
    {
        return $this->empresaModel->findById($empresaId);
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $empresaId, array $data): void
    {
        $payload = empresaPrepareForPersistence($data);

        $this->empresaModel->update($empresaId, $payload);
    }
}
