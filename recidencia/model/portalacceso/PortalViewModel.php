<?php

declare(strict_types=1);

namespace Residencia\Model\PortalAcceso;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class PortalViewModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * Obtiene un acceso al portal por su identificador.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $portalId): ?array
    {
        $sql = <<<'SQL'
            SELECT
                pa.id,
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
            WHERE pa.id = :portal_id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':portal_id' => $portalId,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @param array{
     *     token: string,
     *     nip: ?string,
     *     activo: int,
     *     expiracion: ?string
     * } $data
     */
    public function updateAccess(int $portalId, array $data): bool
    {
        $sql = <<<'SQL'
            UPDATE rp_portal_acceso
               SET token = :token,
                   nip = :nip,
                   activo = :activo,
                   expiracion = :expiracion
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':token', $data['token']);
        $statement->bindValue(':nip', $data['nip'], $data['nip'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':activo', $data['activo'], PDO::PARAM_INT);
        $statement->bindValue(':expiracion', $data['expiracion'], $data['expiracion'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':id', $portalId, PDO::PARAM_INT);

        $statement->execute();

        return $statement->rowCount() > 0;
    }

    public function deleteAccess(int $portalId): bool
    {
        $sql = <<<'SQL'
            DELETE FROM rp_portal_acceso
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $portalId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }
}
