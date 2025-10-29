<?php

declare(strict_types=1);

namespace Residencia\Model\DocumentoTipo;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use RuntimeException;
use Throwable;

class DocumentoTipoDeleteModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
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

        return $result !== false ? $result : null;
    }

    /**
     * @return array{
     *     action: 'deleted'|'deactivated',
     *     documentoTipo: array<string, mixed>,
     *     usageCount: int
     * }
     */
    public function deleteOrDeactivate(int $documentoTipoId): array
    {
        $this->pdo->beginTransaction();

        try {
            $documentoTipo = $this->fetchForUpdate($documentoTipoId);
            if ($documentoTipo === null) {
                throw new RuntimeException('El tipo de documento solicitado no existe.', 404);
            }

            $usageCount = $this->countDocumentsByTipo($documentoTipoId);
            $inUse = $usageCount > 0;

            if ($inUse) {
                $updateSql = 'UPDATE rp_documento_tipo SET activo = 0 WHERE id = :id';
                $update = $this->pdo->prepare($updateSql);
                $update->execute([':id' => $documentoTipoId]);

                $documentoTipo['activo'] = 0;

                $this->pdo->commit();

                return [
                    'action' => 'deactivated',
                    'documentoTipo' => $documentoTipo,
                    'usageCount' => $usageCount,
                ];
            }

            $deleteSql = 'DELETE FROM rp_documento_tipo WHERE id = :id LIMIT 1';
            $delete = $this->pdo->prepare($deleteSql);
            $delete->execute([':id' => $documentoTipoId]);

            if ($delete->rowCount() !== 1) {
                throw new RuntimeException('No se pudo eliminar el tipo de documento.', 500);
            }

            $this->pdo->commit();

            return [
                'action' => 'deleted',
                'documentoTipo' => $documentoTipo,
                'usageCount' => $usageCount,
            ];
        } catch (Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $throwable;
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchForUpdate(int $documentoTipoId): ?array
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
             FOR UPDATE
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $documentoTipoId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    private function countDocumentsByTipo(int $documentoTipoId): int
    {
        $sql = 'SELECT COUNT(*) FROM rp_empresa_doc WHERE tipo_id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $documentoTipoId]);

        return (int) $statement->fetchColumn();
    }
}
