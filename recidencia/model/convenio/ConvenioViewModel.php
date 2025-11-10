<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class ConvenioViewModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $convenioId): ?array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control,
                   e.representante AS empresa_representante,
                   e.cargo_representante AS empresa_representante_cargo,
                   e.telefono AS empresa_telefono,
                   e.contacto_email AS empresa_contacto_email,
                   e.direccion AS empresa_direccion,
                   e.municipio AS empresa_municipio,
                   e.estado AS empresa_estado,
                   e.cp AS empresa_cp,
                   e.creado_en AS empresa_creado_en,
                   c.folio,
                   c.estatus,
                   c.tipo_convenio,
                   c.responsable_academico,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.creado_en,
                   c.actualizado_en,
                   c.observaciones,
                   c.borrador_path,
                   c.firmado_path,
                   c.renovado_de,
                   c.machote_id,
                   cm.version_local AS machote_version_local,
                   cm.machote_padre_id,
                   cm.creado_en AS machote_creado_en,
                   cm.actualizado_en AS machote_actualizado_en,
                   p.id AS parent_id,
                   p.estatus AS parent_estatus,
                   p.fecha_inicio AS parent_fecha_inicio,
                   p.fecha_fin AS parent_fecha_fin,
                   pe.nombre AS parent_empresa_nombre
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
              LEFT JOIN rp_convenio_machote AS cm ON cm.id = c.machote_id
              LEFT JOIN rp_convenio AS p ON p.id = c.renovado_de
              LEFT JOIN rp_empresa AS pe ON pe.id = p.empresa_id
             WHERE c.id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $convenioId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }
}
