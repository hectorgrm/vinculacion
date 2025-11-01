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
            SELECT id,
                   actor_tipo,
                   actor_id,
                   accion,
                   entidad,
                   entidad_id,
                   ip,
                   ts
              FROM auditoria
        SQL;

        $conditions = [];
        $params = [];

        if ($actorTipo !== null) {
            $conditions[] = 'actor_tipo = :actor_tipo';
            $params[':actor_tipo'] = $actorTipo;
        }

        if ($actorId !== null) {
            $conditions[] = 'actor_id = :actor_id';
            $params[':actor_id'] = $actorId;
        }

        if ($accion !== null) {
            $conditions[] = 'accion LIKE :accion';
            $params[':accion'] = '%' . $accion . '%';
        }

        if ($entidad !== null) {
            $conditions[] = 'entidad LIKE :entidad';
            $params[':entidad'] = '%' . $entidad . '%';
        }

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                accion LIKE :search
                OR entidad LIKE :search
                OR ip LIKE :search
                OR CAST(actor_id AS CHAR) LIKE :search
                OR CAST(entidad_id AS CHAR) LIKE :search
            )';
            $params[':search'] = '%' . $search . '%';
        }

        if ($fechaDesde !== null) {
            $conditions[] = 'ts >= :fecha_desde';
            $params[':fecha_desde'] = $fechaDesde . ' 00:00:00';
        }

        if ($fechaHasta !== null) {
            $conditions[] = 'ts <= :fecha_hasta';
            $params[':fecha_hasta'] = $fechaHasta . ' 23:59:59';
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY ts DESC, id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
