<?php

declare(strict_types=1);

namespace Residencia\Model\Dashboard;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DashboardComentarioModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array{total: int, abiertos: int, resueltos: int, archivados: int, revisiones: int}
     */
    public function fetchStats(): array
    {
        $sql = <<<'SQL'
            SELECT
                (SELECT COUNT(*) FROM rp_machote_revision WHERE estado = 'en_revision') AS revisiones,
                COUNT(msg.id) AS total,
                SUM(CASE WHEN msg.estatus = 'abierto' THEN 1 ELSE 0 END)   AS abiertos,
                SUM(CASE WHEN msg.estatus = 'resuelto' THEN 1 ELSE 0 END)  AS resueltos,
                SUM(CASE WHEN msg.estatus = 'Archivado' THEN 1 ELSE 0 END) AS archivados
            FROM rp_machote_revision AS r
            LEFT JOIN rp_machote_revision_msg AS msg
                ON msg.revision_id = r.id
                AND msg.estatus IN ('abierto', 'resuelto', 'Archivado')
            WHERE r.estado = 'en_revision'
        SQL;

        $statement = $this->pdo->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'abiertos' => (int) ($row['abiertos'] ?? 0),
            'resueltos' => (int) ($row['resueltos'] ?? 0),
            'archivados' => (int) ($row['archivados'] ?? 0),
            'revisiones' => (int) ($row['revisiones'] ?? 0),
        ];
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function fetchRevisiones(int $limit = 6): array
    {
        $sql = <<<'SQL'
            SELECT
                r.id AS revision_id,
                r.estado,
                r.machote_version,
                e.nombre AS empresa_nombre,
                COALESCE(SUM(CASE WHEN msg.estatus = 'abierto' THEN 1 ELSE 0 END), 0)   AS abiertos,
                COALESCE(SUM(CASE WHEN msg.estatus = 'resuelto' THEN 1 ELSE 0 END), 0)  AS resueltos,
                COALESCE(SUM(CASE WHEN msg.estatus = 'Archivado' THEN 1 ELSE 0 END), 0) AS archivados,
                COUNT(msg.id) AS total,
                CASE
                    WHEN COUNT(msg.id) > 0 THEN ROUND(SUM(CASE WHEN msg.estatus = 'resuelto' THEN 1 ELSE 0 END) / COUNT(msg.id) * 100)
                    ELSE 0
                END AS progreso,
                MAX(msg.actualizado_en) AS actualizado_en
            FROM rp_machote_revision AS r
            INNER JOIN rp_empresa AS e ON e.id = r.empresa_id
            LEFT JOIN rp_machote_revision_msg AS msg
                ON msg.revision_id = r.id
                AND msg.estatus IN ('abierto', 'resuelto', 'Archivado')
            WHERE r.estado = 'en_revision'
            GROUP BY r.id, e.nombre, r.machote_version, r.estado
            ORDER BY actualizado_en DESC, r.id DESC
            LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function fetchComentarios(int $limit = 8): array
    {
        $sql = <<<'SQL'
            SELECT
                msg.id,
                msg.revision_id,
                msg.asunto,
                msg.estatus,
                msg.actualizado_en,
                r.machote_version,
                e.nombre AS empresa_nombre
            FROM rp_machote_revision_msg AS msg
            INNER JOIN rp_machote_revision AS r ON r.id = msg.revision_id
            INNER JOIN rp_empresa AS e ON e.id = r.empresa_id
            WHERE r.estado = 'en_revision'
              AND msg.estatus IN ('abierto', 'resuelto')
            ORDER BY msg.actualizado_en DESC, msg.id DESC
            LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
