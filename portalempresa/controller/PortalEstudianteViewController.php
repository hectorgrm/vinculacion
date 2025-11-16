<?php

declare(strict_types=1);

namespace PortalEmpresa\Controller;

use PortalEmpresa\Model\EstudianteModel;
use RuntimeException;

require_once __DIR__ . '/../model/EstudianteModel.php';
require_once __DIR__ . '/../helpers/estudiante_helper.php';

class PortalEstudianteViewController
{
    private EstudianteModel $model;

    public function __construct(?EstudianteModel $model = null)
    {
        $this->model = $model ?? new EstudianteModel();
    }

    /**
     * @return array<string, mixed>
     */
    public function obtenerDetalle(int $empresaId, int $estudianteId): array
    {
        if ($empresaId <= 0) {
            throw new RuntimeException('La sesión de la empresa no es válida.');
        }

        if ($estudianteId <= 0) {
            throw new RuntimeException('El estudiante solicitado no es válido.');
        }

        $registro = $this->model->findOneByEmpresa($estudianteId, $empresaId);

        if ($registro === null) {
            throw new RuntimeException('No se encontró el estudiante solicitado.');
        }

        return estudianteHydrateRecord($registro);
    }
}
