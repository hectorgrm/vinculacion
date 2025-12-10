<?php

declare(strict_types=1);

namespace Residencia\Model\Dashboard;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DashboardConvenioModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array{total: int, activos: int, revision: int, archivados: int, completados: int}
     */
    public function fetchStats(): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN LOWER(estatus) LIKE 'activa%' THEN 1 ELSE 0 END)    AS activos,
                SUM(CASE WHEN LOWER(estatus) LIKE 'en revisi%' THEN 1 ELSE 0 END) AS revision,
                SUM(CASE WHEN LOWER(estatus) LIKE 'archiv%' THEN 1 ELSE 0 END)    AS archivados,
                SUM(CASE WHEN LOWER(estatus) LIKE 'complet%' THEN 1 ELSE 0 END)   AS completados
            FROM rp_convenio
        SQL;

        $statement = $this->pdo->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'activos' => (int) ($row['activos'] ?? 0),
            'revision' => (int) ($row['revision'] ?? 0),
            'archivados' => (int) ($row['archivados'] ?? 0),
            'completados' => (int) ($row['completados'] ?? 0),
        ];
    }
}
