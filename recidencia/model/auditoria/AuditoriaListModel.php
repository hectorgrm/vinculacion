<?php

declare(strict_types=1);

namespace Residencia\Model\Auditoria;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class AuditoriaListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAuditorias(
        ?string $actorTipo = null,
        ?int $actorId = null,
        ?string $accion = null,
        ?string $entidad = null,
        ?string $search = null,
        ?string $fechaDesde = null,
        ?string $fechaHasta = null
    ): array {
        $sql = <<<'SQL'
            SELECT a.id,
                   a.actor_tipo,
                   a.actor_id,
                   a.accion,
                   a.entidad,
                   a.entidad_id,
                   a.ip,
                   a.ts,
                   CASE
                       WHEN a.actor_tipo = 'usuario' THEN u.nombre
                       WHEN a.actor_tipo = 'empresa' THEN emp.nombre
                       ELSE NULL
                   END AS actor_nombre
              FROM auditoria AS a
              LEFT JOIN usuario AS u ON u.id = a.actor_id AND a.actor_tipo = 'usuario'
              LEFT JOIN rp_empresa AS emp ON emp.id = a.actor_id AND a.actor_tipo = 'empresa'
        SQL;

        $conditions = [];
        $params = [];

        if ($actorTipo !== null) {
            $conditions[] = 'a.actor_tipo = :actor_tipo';
            $params[':actor_tipo'] = $actorTipo;
        }

        if ($actorId !== null) {
            $conditions[] = 'a.actor_id = :actor_id';
            $params[':actor_id'] = $actorId;
        }

        if ($accion !== null) {
            $conditions[] = 'a.accion LIKE :accion';
            $params[':accion'] = '%' . $accion . '%';
        }

        if ($entidad !== null) {
            $conditions[] = 'a.entidad LIKE :entidad';
            $params[':entidad'] = '%' . $entidad . '%';
        }

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                a.accion LIKE :search
                OR a.entidad LIKE :search
                OR a.ip LIKE :search
                OR CAST(a.actor_id AS CHAR) LIKE :search
                OR CAST(a.entidad_id AS CHAR) LIKE :search
            )';
            $params[':search'] = '%' . $search . '%';
        }

        if ($fechaDesde !== null) {
            $conditions[] = 'a.ts >= :fecha_desde';
            $params[':fecha_desde'] = $fechaDesde . ' 00:00:00';
        }

        if ($fechaHasta !== null) {
            $conditions[] = 'a.ts <= :fecha_hasta';
            $params[':fecha_hasta'] = $fechaHasta . ' 23:59:59';
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY a.ts DESC, a.id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
