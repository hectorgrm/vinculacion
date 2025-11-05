<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Model\EmpresaPerfilModel;

require_once __DIR__ . '/../model/EmpresaPerfilModel.php';
require_once __DIR__ . '/../helpers/empresaperfil_helper_function.php';
require_once __DIR__ . '/../common/functions/empresaperfil_function.php';

class EmpresaPerfilController
{
    private EmpresaPerfilModel $model;

    public function __construct(?EmpresaPerfilModel $model = null)
    {
        $this->model = $model ?? new EmpresaPerfilModel();
    }

    /**
     * @return array<string, mixed>
     */
    public function obtenerPerfil(int $empresaId): array
    {
        if ($empresaId <= 0) {
            throw new \InvalidArgumentException('El identificador de la empresa es inválido.');
        }

        $record = $this->model->findEmpresaById($empresaId);

        if ($record === null) {
            throw new \RuntimeException('No se encontró la empresa solicitada.');
        }

        return empresaPerfilHydrateRecord($record);
    }
}
