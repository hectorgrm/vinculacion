<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class ConvenioAuditoriaModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchHistorial(int $convenioId, ?int $empresaId, int $limit = 30): array
    {
        $limit = max(1, $limit);

        $baseSql = <<<'SQL'
            SELECT a.id,
                   a.accion,
                   a.actor_tipo,
                   a.actor_id,
                   a.entidad,
                   a.entidad_id,
                   a.ip,
                   a.ts,
                   CASE
                       WHEN a.actor_tipo = 'usuario' THEN u.nombre
                       WHEN a.actor_tipo = 'empresa' THEN emp.nombre
                       ELSE NULL
                   END AS actor_nombre,
                   c.id AS convenio_id,
                   c.folio AS convenio_folio,
                   c.version_actual AS convenio_version,
                   c.estatus AS convenio_estatus,
                   doc.id AS documento_id,
                   doc.estatus AS documento_estatus,
                   COALESCE(dt.nombre, dte.nombre) AS documento_tipo_nombre
              FROM auditoria AS a
              LEFT JOIN usuario AS u ON u.id = a.actor_id AND a.actor_tipo = 'usuario'
              LEFT JOIN rp_empresa AS emp ON emp.id = a.actor_id AND a.actor_tipo = 'empresa'
              LEFT JOIN rp_convenio AS c ON c.id = a.entidad_id AND a.entidad = 'rp_convenio'
              LEFT JOIN rp_empresa_doc AS doc ON doc.id = a.entidad_id AND a.entidad = 'rp_empresa_doc'
              LEFT JOIN rp_documento_tipo AS dt ON dt.id = doc.tipo_global_id
              LEFT JOIN rp_documento_tipo_empresa AS dte ON dte.id = doc.tipo_personalizado_id
             WHERE (%s)
             ORDER BY a.ts DESC, a.id DESC
             LIMIT :limit
        SQL;

        $conditions = ["(a.entidad = 'rp_convenio' AND a.entidad_id = :convenio_id)"];
        $parameters = [':convenio_id' => $convenioId];

        if ($empresaId !== null) {
            $conditions[] = "(a.entidad = 'rp_empresa_doc' AND doc.empresa_id = :empresa_id)";
            $parameters[':empresa_id'] = $empresaId;
        }

        $sql = sprintf($baseSql, implode(' OR ', $conditions));

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':convenio_id', $convenioId, PDO::PARAM_INT);

        if (array_key_exists(':empresa_id', $parameters)) {
            $statement->bindValue(':empresa_id', $parameters[':empresa_id'], PDO::PARAM_INT);
        }

        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
