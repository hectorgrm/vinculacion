<?php

declare(strict_types=1);

namespace Residencia\Controller\Empresa;

require_once __DIR__ . '/../../model/empresa/EmpresaEditModel.php';

use Residencia\Model\Empresa\EmpresaEditModel;
use RuntimeException;
use PDOException;

class EmpresaEditController
{
    private EmpresaEditModel $model;

    public function __construct(?EmpresaEditModel $model = null)
    {
        $this->model = $model ?? new EmpresaEditModel();
    }

    public function getEmpresaById(int $empresaId): array
    {
        try {
            $empresa = $this->model->findById($empresaId);
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
    public function updateEmpresa(int $empresaId, array $data): void
    {
        try {
            $this->model->update($empresaId, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar la empresa.', 0, $exception);
        }
    }
}
