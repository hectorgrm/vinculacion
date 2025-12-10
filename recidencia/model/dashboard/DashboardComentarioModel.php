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
                (SELECT COUNT(*) FROM rp_convenio_machote WHERE LOWER(estatus) LIKE 'en revisi%') AS revisiones,
                COUNT(c.id) AS total,
                SUM(CASE WHEN LOWER(c.estatus) = 'pendiente' THEN 1 ELSE 0 END) AS abiertos,
                SUM(CASE WHEN LOWER(c.estatus) = 'resuelto' THEN 1 ELSE 0 END)  AS resueltos,
                SUM(CASE WHEN LOWER(c.estatus) = 'archivado' THEN 1 ELSE 0 END) AS archivados
            FROM rp_convenio_machote AS m
            LEFT JOIN rp_machote_comentario AS c
                ON c.machote_id = m.id
                AND LOWER(c.estatus) IN ('pendiente', 'resuelto', 'archivado')
            WHERE LOWER(m.estatus) LIKE 'en revisi%'
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
                m.id AS machote_id,
                m.id AS revision_id,
                'en_revision' AS estado,
                m.version_local AS machote_version,
                e.nombre AS empresa_nombre,
                COALESCE(SUM(CASE WHEN LOWER(c.estatus) = 'pendiente' THEN 1 ELSE 0 END), 0)   AS abiertos,
                COALESCE(SUM(CASE WHEN LOWER(c.estatus) = 'resuelto' THEN 1 ELSE 0 END), 0)    AS resueltos,
                COALESCE(SUM(CASE WHEN LOWER(c.estatus) = 'archivado' THEN 1 ELSE 0 END), 0)   AS archivados,
                COUNT(c.id) AS total,
                CASE
                    WHEN COUNT(c.id) > 0 THEN ROUND(SUM(CASE WHEN LOWER(c.estatus) = 'resuelto' THEN 1 ELSE 0 END) / COUNT(c.id) * 100)
                    ELSE 0
                END AS progreso,
                COALESCE(MAX(c.creado_en), m.actualizado_en, m.creado_en) AS actualizado_en
            FROM rp_convenio_machote AS m
            INNER JOIN rp_convenio AS cv ON cv.id = m.convenio_id
            INNER JOIN rp_empresa AS e ON e.id = cv.empresa_id
            LEFT JOIN rp_machote_comentario AS c
                ON c.machote_id = m.id
                AND LOWER(c.estatus) IN ('pendiente', 'resuelto', 'archivado')
            WHERE LOWER(m.estatus) LIKE 'en revisi%'
            GROUP BY m.id, m.version_local, m.estatus, e.nombre, m.actualizado_en, m.creado_en
            ORDER BY actualizado_en DESC, m.id DESC
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
                c.id,
                m.id AS revision_id,
                c.asunto,
                c.estatus,
                c.creado_en AS actualizado_en,
                m.version_local AS machote_version,
                e.nombre AS empresa_nombre
            FROM rp_machote_comentario AS c
            INNER JOIN rp_convenio_machote AS m ON m.id = c.machote_id
            INNER JOIN rp_convenio AS cv ON cv.id = m.convenio_id
            INNER JOIN rp_empresa AS e ON e.id = cv.empresa_id
            WHERE LOWER(m.estatus) LIKE 'en revisi%'
              AND LOWER(c.estatus) IN ('pendiente', 'resuelto')
            ORDER BY actualizado_en DESC, c.id DESC
            LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
