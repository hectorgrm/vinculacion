<?php

declare(strict_types=1);

namespace Residencia\Controller\Estudiante;

use Residencia\Model\Estudiante\EstudianteDeactivateModel;
use RuntimeException;

class EstudianteDeactivateController
{
    public function __construct(private readonly EstudianteDeactivateModel $model)
    {
    }

    /**
     * @return array{
     *     estudiante: array<string, mixed>,
     *     empresa: ?array<string, mixed>,
     *     convenio: ?array<string, mixed>
     * }|null
     */
    public function obtenerDetalle(int $estudianteId): ?array
    {
        $registro = $this->model->findByIdWithRelations($estudianteId);

        if ($registro === null) {
            return null;
        }

        $estudiante = [
            'id' => isset($registro['estudiante_id']) ? (int) $registro['estudiante_id'] : $estudianteId,
            'nombre' => isset($registro['estudiante_nombre']) ? (string) $registro['estudiante_nombre'] : '',
            'apellido_paterno' => isset($registro['estudiante_apellido_paterno']) ? (string) $registro['estudiante_apellido_paterno'] : '',
            'apellido_materno' => isset($registro['estudiante_apellido_materno']) ? (string) $registro['estudiante_apellido_materno'] : '',
            'matricula' => isset($registro['estudiante_matricula']) ? (string) $registro['estudiante_matricula'] : '',
            'carrera' => isset($registro['estudiante_carrera']) ? (string) $registro['estudiante_carrera'] : '',
            'estatus' => isset($registro['estudiante_estatus']) ? (string) $registro['estudiante_estatus'] : '',
        ];

        $empresa = null;

        if (isset($registro['empresa_id'])) {
            $empresa = [
                'id' => (int) $registro['empresa_id'],
                'nombre' => isset($registro['empresa_nombre']) ? (string) $registro['empresa_nombre'] : '',
            ];
        }

        $convenio = null;

        if (isset($registro['convenio_id']) && $registro['convenio_id'] !== null) {
            $convenio = [
                'id' => (int) $registro['convenio_id'],
                'folio' => isset($registro['convenio_folio']) ? (string) $registro['convenio_folio'] : '',
                'estatus' => isset($registro['convenio_estatus']) ? (string) $registro['convenio_estatus'] : '',
            ];
        }

        return [
            'estudiante' => $estudiante,
            'empresa' => $empresa,
            'convenio' => $convenio,
        ];
    }

    public function desactivar(int $estudianteId): void
    {
        $resultado = $this->model->updateStatus($estudianteId, 'Inactivo');

        if (!$resultado) {
            throw new RuntimeException('No se pudo actualizar el estatus del estudiante.');
        }
    }
}
