<?php

declare(strict_types=1);

namespace Residencia\Model\PortalAcceso;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class PortalListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null, ?string $status = null): array
    {
        $sql = <<<'SQL'
            SELECT pa.id,
                   pa.empresa_id,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control,
                   pa.token,
                   pa.nip,
                   pa.activo,
                   pa.expiracion,
                   pa.creado_en
              FROM rp_portal_acceso AS pa
              INNER JOIN rp_empresa AS e ON e.id = pa.empresa_id
        SQL;

        $conditions = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                e.nombre LIKE :search
                OR e.numero_control LIKE :search
                OR pa.token LIKE :search
                OR pa.nip LIKE :search
            )';
            $params[':search'] = '%' . $search . '%';
        }

        if ($status !== null && $status !== '') {
            switch ($status) {
                case 'activo':
                    $conditions[] = 'pa.activo = 1 AND (pa.expiracion IS NULL OR pa.expiracion > NOW())';
                    break;
                case 'inactivo':
                    $conditions[] = 'pa.activo = 0';
                    break;
                case 'expirado':
                    $conditions[] = 'pa.expiracion IS NOT NULL AND pa.expiracion <= NOW()';
                    break;
            }
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY pa.creado_en DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
