<?php

declare(strict_types=1);

namespace Residencia\Model\Estudiante;

use PDO;

/**
 * Data access for the student detail view.
 */
class EstudianteViewModel
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    /**
     * Obtains the student record with its related company and optional agreement.
     *
     * @return array<string, mixed>|null
     */
    public function obtenerEstudiantePorId(int $estudianteId): ?array
    {
        $sql = <<<'SQL'
SELECT
    e.id AS estudiante_id,
    e.nombre AS estudiante_nombre,
    e.apellido_paterno AS estudiante_apellido_paterno,
    e.apellido_materno AS estudiante_apellido_materno,
    e.matricula AS estudiante_matricula,
    e.carrera AS estudiante_carrera,
    e.correo_institucional AS estudiante_correo_institucional,
    e.telefono AS estudiante_telefono,
    e.estatus AS estudiante_estatus,
    e.creado_en AS estudiante_creado_en,
    emp.id AS empresa_id,
    emp.nombre AS empresa_nombre,
    emp.contacto_nombre AS empresa_contacto_nombre,
    emp.contacto_email AS empresa_contacto_email,
    emp.telefono AS empresa_telefono,
    emp.direccion AS empresa_direccion,
    emp.municipio AS empresa_municipio,
    emp.estado AS empresa_estado,
    emp.cp AS empresa_cp,
    emp.representante AS empresa_representante,
    emp.cargo_representante AS empresa_cargo_representante,
    c.id AS convenio_id,
    c.folio AS convenio_folio,
    c.estatus AS convenio_estatus,
    c.tipo_convenio AS convenio_tipo,
    c.fecha_inicio AS convenio_fecha_inicio,
    c.fecha_fin AS convenio_fecha_fin,
    c.responsable_academico AS convenio_responsable_academico
FROM rp_estudiante e
INNER JOIN rp_empresa emp ON emp.id = e.empresa_id
LEFT JOIN rp_convenio c ON c.id = e.convenio_id
WHERE e.id = :estudiante_id
LIMIT 1
SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':estudiante_id', $estudianteId, PDO::PARAM_INT);
        $stmt->execute();

        $registro = $stmt->fetch();

        return $registro !== false ? $registro : null;
    }
}
