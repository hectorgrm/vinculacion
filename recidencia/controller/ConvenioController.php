<?php

declare(strict_types=1);

namespace Residencia\Controller;

require_once __DIR__ . '/../model/ConvenioModel.php';
require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;
use PDOException;
use Residencia\Model\ConvenioModel;
use RuntimeException;

class ConvenioController
{
    private ConvenioModel $convenioModel;

    public function __construct(?PDO $pdo = null)
    {
        if ($pdo === null) {
            $pdo = Database::getConnection();
        }

        $this->convenioModel = new ConvenioModel($pdo);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listConvenios(?string $search = null, ?string $estatus = null): array
    {
        $term = $search !== null ? trim($search) : null;

        if ($term === '') {
            $term = null;
        }

        $statusFilter = $estatus !== null ? trim($estatus) : null;

        if ($statusFilter === '') {
            $statusFilter = null;
        }

        try {
            return $this->convenioModel->fetchAll($term, $statusFilter);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener los convenios registrados.', 0, $exception);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getEmpresasForSelect(): array
    {
        try {
            return $this->convenioModel->fetchEmpresasForSelect();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudieron obtener las empresas disponibles.', 0, $exception);
        }
    }

    /**
     * @param array<string, mixed> $data
     */
    public function createConvenio(array $data): int
    {
        try {
            return $this->convenioModel->insert($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar el convenio.', 0, $exception);
        }
    }
}
