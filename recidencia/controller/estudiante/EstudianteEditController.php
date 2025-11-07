<?php

declare(strict_types=1);

namespace Residencia\Controller\Estudiante;

require_once __DIR__ . '/../../model/estudiante/EstudianteEditModel.php';

use Residencia\Model\Estudiante\EstudianteEditModel;
use RuntimeException;
use PDOException;

class EstudianteEditController
{
    private EstudianteEditModel $model;

    public function __construct(?EstudianteEditModel $model = null)
    {
        $this->model = $model ?? new EstudianteEditModel();
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
     * @return array<string, mixed>
     */
    public function fetchEstudiante(int $estudianteId): array
    {
        try {
            $record = $this->model->findById($estudianteId);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo obtener la información del estudiante.', 0, $exception);
        }

        if ($record === null) {
            throw new RuntimeException('El estudiante solicitado no existe.');
        }

        return $record;
    }

    /**
     * @param array<string, string> $data
     */
    public function updateEstudiante(int $estudianteId, array $data): void
    {
        try {
            $this->model->update($estudianteId, $data);
        } catch (PDOException $exception) {
            throw new RuntimeException('No se pudo actualizar la información del estudiante.', 0, $exception);
        }
    }
}
