<?php

declare(strict_types=1);

namespace Residencia\Model\Empresadocumentotipo;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_add.php';

use Common\Model\Database;
use PDO;
use function empresaDocumentoTipoAddPrepareForPersistence;

class EmpresaDocumentoTipoAddModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresaById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   rfc,
                   regimen_fiscal
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @param array<string, string> $data
     */
    public function createDocumento(array $data): int
    {
        $payload = empresaDocumentoTipoAddPrepareForPersistence($data);

        $sql = <<<'SQL'
            INSERT INTO rp_documento_tipo_empresa (
                empresa_id,
                nombre,
                descripcion,
                obligatorio
            ) VALUES (
                :empresa_id,
                :nombre,
                :descripcion,
                :obligatorio
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':empresa_id' => $payload['empresa_id'],
            ':nombre' => $payload['nombre'],
            ':descripcion' => $payload['descripcion'],
            ':obligatorio' => $payload['obligatorio'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
