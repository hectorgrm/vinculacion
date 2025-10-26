<?php

declare(strict_types=1);

namespace Residencia\Controller;

require_once __DIR__ . '/../model/empresa/EmpresaStatusModel.php';
require_once __DIR__ . '/../model/empresa/EmpresaAddModel.php';
require_once __DIR__ . '/../model/empresa/EmpresaEditModel.php';
require_once __DIR__ . '/../model/empresa/EmpresaListModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use PDOException;
use Residencia\Model\Empresa\EmpresaAddModel;
use Residencia\Model\Empresa\EmpresaEditModel;
use Residencia\Model\Empresa\EmpresaListModel;
use Residencia\Model\Empresa\EmpresaStatusModel;
use RuntimeException;
use function array_key_exists;


class EmpresaController
{
    private EmpresaStatusModel $empresaStatusModel;
    private EmpresaAddModel $empresaAddModel;
    private EmpresaEditModel $empresaEditModel;
    private EmpresaListModel $empresaListModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->empresaStatusModel = new EmpresaStatusModel($pdo);
        $this->empresaAddModel = new EmpresaAddModel($pdo);
        $this->empresaEditModel = new EmpresaEditModel($pdo);
        $this->empresaListModel = new EmpresaListModel($pdo);
    }
//
    /**
     * @return array<int, array<string, mixed>>
     */
    public function listEmpresas(?string $search = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        try {
            return $this->empresaListModel->fetchAll($term);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las empresas registradas.', 0, $exception);
        }
    }

    public function getEmpresaById(int $id): array
    {
        try {
            $empresa = $this->empresaEditModel->findById($id);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información de la empresa.', 0, $exception);
        }

        if ($empresa === null) {
            throw new RuntimeException('La empresa solicitada no existe.');
        }

        return $empresa;
    }

    /**
     * @param array<string, string> $data
     */
    public function createEmpresa(array $data): int
    {
        try {
            return $this->empresaAddModel->create($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar la empresa.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function updateEmpresa(int $id, array $data): void
    {
        if (!array_key_exists('numero_control', $data)) {
            $existing = $this->getEmpresaById($id);
            $data['numero_control'] = isset($existing['numero_control']) && $existing['numero_control'] !== null
                ? (string) $existing['numero_control']
                : '';
        }

        try {
            $this->empresaEditModel->update($id, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar la empresa.', 0, $exception);
        }
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
