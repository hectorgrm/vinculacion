<?php

declare(strict_types=1);

namespace Residencia\Controller;

require_once __DIR__ . '/../model/EmpresaModel.php';
require_once __DIR__ . '/../../common/model/db.php';
require_once __DIR__ . '/../common/functions/empresafunction.php';

use Common\Model\Database;
use PDO;
use PDOException;
use Residencia\Model\EmpresaModel;
use RuntimeException;
use function array_key_exists;
use function empresaPrepareForPersistence;


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
            return $this->empresaModel->fetchAll($term);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las empresas registradas.', 0, $exception);
        }
    }

    public function getEmpresaById(int $id): array
    {
        try {
            $empresa = $this->empresaModel->findById($id);
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
        $payload = empresaPrepareForPersistence($data);

        try {
            return $this->empresaModel->insert($payload);
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

        $payload = empresaPrepareForPersistence($data);

        try {
            $this->empresaModel->update($id, $payload);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar la empresa.', 0, $exception);
        }
    }

    public function disableEmpresa(int $empresaId, int $userId, string $reason = ''): void
    {
        try {
            $this->empresaModel->disableWithCascade($empresaId, $userId, $reason);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo desactivar la empresa.', 0, $exception);
        }
    }

    public function reactivateEmpresa(int $empresaId, int $userId, string $reason = ''): void
    {
        try {
            $this->empresaModel->reactivateWithCascade($empresaId, $userId, $reason);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo reactivar la empresa.', 0, $exception);
        }
    }
}
