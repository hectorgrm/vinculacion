<?php

declare(strict_types=1);

namespace Residencia\Model\DocumentoTipo;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_edit.php';

use Common\Model\Database;
use PDO;
use function documentoTipoEditPrepareForPersistence;

class DocumentoTipoEditModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    public function findById(int $documentoTipoId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   descripcion,
                   obligatorio,
                   tipo_empresa,
                   activo
              FROM rp_documento_tipo
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $documentoTipoId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $documentoTipoId, array $data): void
    {
        $payload = documentoTipoEditPrepareForPersistence($data);

        $sql = <<<'SQL'
            UPDATE rp_documento_tipo
               SET nombre = :nombre,
                   descripcion = :descripcion,
                   obligatorio = :obligatorio,
                   tipo_empresa = :tipo_empresa
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $payload['nombre'],
            ':descripcion' => $payload['descripcion'],
            ':obligatorio' => $payload['obligatorio'],
            ':tipo_empresa' => $payload['tipo_empresa'],
            ':id' => $documentoTipoId,
        ]);
    }

    public function reactivate(int $documentoTipoId): void
    {
        $sql = <<<'SQL'
            UPDATE rp_documento_tipo
               SET activo = 1
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $documentoTipoId]);
    }
}
