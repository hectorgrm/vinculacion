<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaLogoUploadModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array{id: int, logo_path: ?string}|null
     */
    public function findLogoInfo(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   logo_path
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            return null;
        }

        return [
            'id' => (int) $result['id'],
            'logo_path' => isset($result['logo_path']) && $result['logo_path'] !== null
                ? (string) $result['logo_path']
                : null,
        ];
    }

    public function updateLogoPath(int $empresaId, string $logoPath): void
    {
        $sql = <<<'SQL'
            UPDATE rp_empresa
               SET logo_path = :logo_path
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':logo_path' => $logoPath,
            ':id' => $empresaId,
        ]);
    }
}
