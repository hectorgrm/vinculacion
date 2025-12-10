<?php

declare(strict_types=1);

namespace Residencia\Model\Dashboard;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DashboardDocumentoModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array{total: int, aprobados: int, pendientes: int, revision: int}
     */
    public function fetchStats(): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN LOWER(estatus) = 'aprobado' THEN 1 ELSE 0 END)                                    AS aprobados,
                SUM(CASE WHEN LOWER(estatus) IN ('pendiente', 'revision', 'rechazado') THEN 1 ELSE 0 END)       AS pendientes,
                SUM(CASE WHEN LOWER(estatus) IN ('revision', 'pendiente') THEN 1 ELSE 0 END)                    AS revision
            FROM rp_empresa_doc
        SQL;

        $statement = $this->pdo->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'aprobados' => (int) ($row['aprobados'] ?? 0),
            'pendientes' => (int) ($row['pendientes'] ?? 0),
            'revision' => (int) ($row['revision'] ?? 0),
        ];
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function fetchEnRevision(int $limit = 8): array
    {
        $sql = <<<'SQL'
            SELECT d.id,
                   d.empresa_id,
                   e.nombre AS empresa_nombre,
                   COALESCE(tg.nombre, tp.nombre, CONCAT('Documento #', d.id)) AS tipo_nombre,
                   d.estatus,
                   d.actualizado_en
              FROM rp_empresa_doc AS d
              JOIN rp_empresa AS e ON e.id = d.empresa_id
              LEFT JOIN rp_documento_tipo AS tg ON tg.id = d.tipo_global_id
              LEFT JOIN rp_documento_tipo_empresa AS tp ON tp.id = d.tipo_personalizado_id
             WHERE LOWER(d.estatus) IN ('revision', 'pendiente')
             ORDER BY d.actualizado_en DESC, d.id DESC
             LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
