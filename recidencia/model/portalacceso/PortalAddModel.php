<?php

declare(strict_types=1);

namespace Residencia\Model\PortalAcceso;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/portalacceso/portalacceso_functions.php';

use Common\Model\Database;
use PDO;
use function portalAccessPrepareForPersistence;

class PortalAddModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getEmpresas(): array
    {
        $sql = <<<'SQL'
            SELECT id, nombre, numero_control
              FROM rp_empresa
             WHERE estatus = 'Activa'
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        $empresas = [];

        if ($statement === false) {
            return $empresas;
        }

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!is_array($row)) {
                continue;
            }

            $empresas[] = [
                'id' => (string) $row['id'],
                'nombre' => (string) $row['nombre'],
                'numero_control' => isset($row['numero_control']) ? (string) $row['numero_control'] : '',
            ];
        }

        return $empresas;
    }

    public function empresaHasPortalAccess(int $empresaId): bool
    {
        $sql = <<<'SQL'
            SELECT 1
              FROM rp_portal_acceso
             WHERE empresa_id = :empresa_id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        return (bool) $statement->fetchColumn();
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        $payload = portalAccessPrepareForPersistence($data);

        $sql = <<<'SQL'
            INSERT INTO rp_portal_acceso (
                empresa_id,
                token,
                nip,
                activo,
                expiracion
            ) VALUES (
                :empresa_id,
                :token,
                :nip,
                :activo,
                :expiracion
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':empresa_id' => $payload['empresa_id'],
            ':token' => $payload['token'],
            ':nip' => $payload['nip'],
            ':activo' => $payload['activo'],
            ':expiracion' => $payload['expiracion'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
