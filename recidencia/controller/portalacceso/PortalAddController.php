<?php

declare(strict_types=1);

namespace Residencia\Controller\PortalAcceso;

require_once __DIR__ . '/../../model/portalacceso/PortalAddModel.php';

use Residencia\Model\PortalAcceso\PortalAddModel;
use RuntimeException;
use PDOException;

class PortalAddController
{
    private PortalAddModel $model;

    public function __construct(?PortalAddModel $model = null)
    {
        $this->model = $model ?? new PortalAddModel();
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function fetchEmpresas(): array
    {
        try {
            return $this->model->getEmpresas();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el listado de empresas.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function createPortalAccess(array $data): int
    {
        try {
            return $this->model->create($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo crear el acceso al portal.', 0, $exception);
        }
    }
}
