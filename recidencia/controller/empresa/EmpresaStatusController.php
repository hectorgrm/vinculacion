<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaStatusModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use PDOException;
use Residencia\Model\Empresa\EmpresaStatusModel;
use RuntimeException;

class EmpresaStatusController
{
    private EmpresaStatusModel $empresaStatusModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->empresaStatusModel = new EmpresaStatusModel($pdo);
    }

    public function disableEmpresa(int $empresaId, int $userId, string $reason = ''): void
    {
        try {
            $this->empresaStatusModel->disableWithCascade($empresaId, $userId, $reason);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo desactivar la empresa.', 0, $exception);
        }
    }

    public function reactivateEmpresa(int $empresaId, int $userId, string $reason = ''): void
    {
        try {
            $this->empresaStatusModel->reactivateWithCascade($empresaId, $userId, $reason);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo reactivar la empresa.', 0, $exception);
        }
    }
}
