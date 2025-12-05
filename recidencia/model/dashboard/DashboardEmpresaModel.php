<?php

declare(strict_types=1);

namespace Residencia\Model\Dashboard;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DashboardEmpresaModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array{total: int, activas: int, revision: int, completadas: int}
     */
    public function fetchStats(): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estatus = 'Activa' THEN 1 ELSE 0 END)       AS activas,
                SUM(CASE WHEN estatus = 'En revisión' THEN 1 ELSE 0 END)  AS revision,
                SUM(CASE WHEN estatus = 'Completada' THEN 1 ELSE 0 END)   AS completadas
            FROM rp_empresa
        SQL;

        $statement = $this->pdo->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC) ?: [];

        return [
            'total' => (int) ($row['total'] ?? 0),
            'activas' => (int) ($row['activas'] ?? 0),
            'revision' => (int) ($row['revision'] ?? 0),
            'completadas' => (int) ($row['completadas'] ?? 0),
        ];
    }
}
