<?php

declare(strict_types=1);

namespace Residencia\Controller\Estudiante;

use Residencia\Model\Estudiante\EstudianteViewModel;

/**
 * Coordinates the retrieval of data required by the student detail view.
 */
class EstudianteViewController
{
    public function __construct(private readonly EstudianteViewModel $model)
    {
    }

    /**
     * @return array{
     *     estudiante: ?array<string, mixed>,
     *     empresa: ?array<string, mixed>,
     *     convenio: ?array<string, mixed>
     * }
     */
    public function obtenerDetalle(int $estudianteId): array
    {
        $registro = $this->model->obtenerEstudiantePorId($estudianteId);

        if ($registro === null) {
            return [
                'estudiante' => null,
                'empresa' => null,
                'convenio' => null,
            ];
        }

        $estudiante = [
            'id' => isset($registro['estudiante_id']) ? (int) $registro['estudiante_id'] : null,
            'nombre' => isset($registro['estudiante_nombre']) ? (string) $registro['estudiante_nombre'] : null,
            'apellido_paterno' => isset($registro['estudiante_apellido_paterno']) ? (string) $registro['estudiante_apellido_paterno'] : null,
            'apellido_materno' => isset($registro['estudiante_apellido_materno']) ? (string) $registro['estudiante_apellido_materno'] : null,
            'matricula' => isset($registro['estudiante_matricula']) ? (string) $registro['estudiante_matricula'] : null,
            'carrera' => isset($registro['estudiante_carrera']) ? (string) $registro['estudiante_carrera'] : null,
            'correo_institucional' => isset($registro['estudiante_correo_institucional']) ? (string) $registro['estudiante_correo_institucional'] : null,
            'telefono' => isset($registro['estudiante_telefono']) ? (string) $registro['estudiante_telefono'] : null,
            'estatus' => isset($registro['estudiante_estatus']) ? (string) $registro['estudiante_estatus'] : null,
            'creado_en' => isset($registro['estudiante_creado_en']) ? (string) $registro['estudiante_creado_en'] : null,
        ];

        $empresa = null;
        if (isset($registro['empresa_id'])) {
            $empresa = [
                'id' => (int) $registro['empresa_id'],
                'nombre' => isset($registro['empresa_nombre']) ? (string) $registro['empresa_nombre'] : null,
                'contacto_nombre' => isset($registro['empresa_contacto_nombre']) ? (string) $registro['empresa_contacto_nombre'] : null,
                'contacto_email' => isset($registro['empresa_contacto_email']) ? (string) $registro['empresa_contacto_email'] : null,
                'telefono' => isset($registro['empresa_telefono']) ? (string) $registro['empresa_telefono'] : null,
                'direccion' => isset($registro['empresa_direccion']) ? (string) $registro['empresa_direccion'] : null,
                'municipio' => isset($registro['empresa_municipio']) ? (string) $registro['empresa_municipio'] : null,
                'estado' => isset($registro['empresa_estado']) ? (string) $registro['empresa_estado'] : null,
                'cp' => isset($registro['empresa_cp']) ? (string) $registro['empresa_cp'] : null,
                'representante' => isset($registro['empresa_representante']) ? (string) $registro['empresa_representante'] : null,
                'cargo_representante' => isset($registro['empresa_cargo_representante']) ? (string) $registro['empresa_cargo_representante'] : null,
            ];
        }

        $convenio = null;
        if (isset($registro['convenio_id']) && $registro['convenio_id'] !== null) {
            $convenio = [
                'id' => (int) $registro['convenio_id'],
                'folio' => isset($registro['convenio_folio']) ? (string) $registro['convenio_folio'] : null,
                'estatus' => isset($registro['convenio_estatus']) ? (string) $registro['convenio_estatus'] : null,
                'tipo_convenio' => isset($registro['convenio_tipo']) ? (string) $registro['convenio_tipo'] : null,
                'fecha_inicio' => isset($registro['convenio_fecha_inicio']) ? (string) $registro['convenio_fecha_inicio'] : null,
                'fecha_fin' => isset($registro['convenio_fecha_fin']) ? (string) $registro['convenio_fecha_fin'] : null,
                'responsable_academico' => isset($registro['convenio_responsable_academico']) ? (string) $registro['convenio_responsable_academico'] : null,
            ];
        }

        return [
            'estudiante' => $estudiante,
            'empresa' => $empresa,
            'convenio' => $convenio,
        ];
    }
}
