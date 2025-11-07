<?php

declare(strict_types=1);

namespace Residencia\Controller\Estudiante;

require_once __DIR__ . '/../../model/estudiante/EstudianteAddModel.php';

use Residencia\Model\Estudiante\EstudianteAddModel;
use RuntimeException;
use PDOException;

class EstudianteAddController
{
    private EstudianteAddModel $model;

    public function __construct(?EstudianteAddModel $model = null)
    {
        $this->model = $model ?? new EstudianteAddModel();
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
     * @return array<int, array<string, string>>
     */
    public function fetchConvenios(): array
    {
        try {
            return $this->model->getConvenios();
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener el listado de convenios.', 0, $exception);
        }
    }

    /**
     * @param array<string, string> $data
     */
    public function createEstudiante(array $data): int
    {
        try {
            return $this->model->create($data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo registrar al estudiante.', 0, $exception);
        }
    }
}
