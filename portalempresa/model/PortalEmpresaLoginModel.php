<?php
declare(strict_types=1);

namespace PortalEmpresa\Model;

require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;

class PortalEmpresaLoginModel
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        if ($connection instanceof PDO) {
            $this->connection = $connection;
            return;
        }

        $this->connection = Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByToken(string $token): ?array
    {
        $sql = <<<'SQL'
            SELECT pa.id AS portal_id,
                   pa.empresa_id,
                   pa.token,
                   pa.nip,
                   pa.activo,
                   pa.expiracion,
                   pa.creado_en,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control,
                   e.estatus AS empresa_estatus
              FROM rp_portal_acceso AS pa
              INNER JOIN rp_empresa AS e ON e.id = pa.empresa_id
             WHERE pa.token = :token
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':token' => $token,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }
}
