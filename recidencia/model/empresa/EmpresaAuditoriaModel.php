<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaAuditoriaModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByEmpresaId(int $empresaId): array
    {
        $sql = <<<'SQL'
            (SELECT a.id,
                    a.actor_tipo,
                    a.actor_id,
                    a.accion,
                    a.entidad,
                    a.entidad_id,
                    a.ip,
                    a.ts,
                    CASE
                        WHEN a.actor_tipo = 'usuario' THEN u.nombre
                        WHEN a.actor_tipo = 'empresa' THEN actor_empresa.nombre
                        ELSE NULL
                    END AS actor_nombre,
                    NULL AS documento_id,
                    NULL AS documento_estatus,
                    NULL AS documento_tipo_nombre,
                    NULL AS convenio_id,
                    NULL AS convenio_folio,
                    NULL AS convenio_responsable,
                    NULL AS convenio_estatus,
                    empresa.nombre AS empresa_nombre
               FROM auditoria AS a
               LEFT JOIN usuario AS u
                      ON u.id = a.actor_id
                     AND a.actor_tipo = 'usuario'
               LEFT JOIN rp_empresa AS actor_empresa
                      ON actor_empresa.id = a.actor_id
                     AND a.actor_tipo = 'empresa'
               LEFT JOIN rp_empresa AS empresa
                      ON empresa.id = a.entidad_id
              WHERE a.entidad = 'rp_empresa'
                AND a.entidad_id = :empresa_id)
            UNION ALL
            (SELECT a.id,
                    a.actor_tipo,
                    a.actor_id,
                    a.accion,
                    a.entidad,
                    a.entidad_id,
                    a.ip,
                    a.ts,
                    CASE
                        WHEN a.actor_tipo = 'usuario' THEN u.nombre
                        WHEN a.actor_tipo = 'empresa' THEN actor_empresa.nombre
                        ELSE NULL
                    END AS actor_nombre,
                    doc.id AS documento_id,
                    doc.estatus AS documento_estatus,
                    COALESCE(dt.nombre, dte.nombre) AS documento_tipo_nombre,
                    NULL AS convenio_id,
                    NULL AS convenio_folio,
                    NULL AS convenio_responsable,
                    NULL AS convenio_estatus,
                    NULL AS empresa_nombre
               FROM auditoria AS a
               LEFT JOIN usuario AS u
                      ON u.id = a.actor_id
                     AND a.actor_tipo = 'usuario'
               LEFT JOIN rp_empresa AS actor_empresa
                      ON actor_empresa.id = a.actor_id
                     AND a.actor_tipo = 'empresa'
               LEFT JOIN rp_empresa_doc AS doc
                      ON doc.id = a.entidad_id
               LEFT JOIN rp_documento_tipo AS dt
                      ON dt.id = doc.tipo_global_id
               LEFT JOIN rp_documento_tipo_empresa AS dte
                      ON dte.id = doc.tipo_personalizado_id
              WHERE a.entidad IN ('rp_empresa_doc', 'documento')
                AND doc.empresa_id = :empresa_id)
            UNION ALL
            (SELECT a.id,
                    a.actor_tipo,
                    a.actor_id,
                    a.accion,
                    a.entidad,
                    a.entidad_id,
                    a.ip,
                    a.ts,
                    CASE
                        WHEN a.actor_tipo = 'usuario' THEN u.nombre
                        WHEN a.actor_tipo = 'empresa' THEN actor_empresa.nombre
                        ELSE NULL
                    END AS actor_nombre,
                    NULL AS documento_id,
                    NULL AS documento_estatus,
                    NULL AS documento_tipo_nombre,
                    c.id AS convenio_id,
                    c.folio AS convenio_folio,
                    c.responsable_academico AS convenio_responsable,
                    c.estatus AS convenio_estatus,
                    NULL AS empresa_nombre
               FROM auditoria AS a
               LEFT JOIN usuario AS u
                      ON u.id = a.actor_id
                     AND a.actor_tipo = 'usuario'
               LEFT JOIN rp_empresa AS actor_empresa
                      ON actor_empresa.id = a.actor_id
                     AND a.actor_tipo = 'empresa'
               LEFT JOIN rp_convenio AS c
                      ON c.id = a.entidad_id
              WHERE a.entidad = 'rp_convenio'
                AND c.empresa_id = :empresa_id)
            ORDER BY ts DESC,
                     id DESC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
        $statement->execute();

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records ?: [];
    }
}
