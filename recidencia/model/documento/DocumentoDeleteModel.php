<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use RuntimeException;
use Throwable;

class DocumentoDeleteModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $documentId): ?array
    {
        return $this->fetchDocument($documentId, false);
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteDocument(int $documentId): array
    {
        $this->pdo->beginTransaction();

        try {
            $document = $this->fetchDocument($documentId, true);
            if ($document === null) {
                throw new RuntimeException('El documento solicitado no existe.', 404);
            }

            $deleteSql = 'DELETE FROM rp_empresa_doc WHERE id = :id LIMIT 1';
            $statement = $this->pdo->prepare($deleteSql);
            $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount() !== 1) {
                throw new RuntimeException('No se pudo eliminar el documento.', 500);
            }

            $this->pdo->commit();

            return $document;
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
    private function fetchDocument(int $documentId, bool $forUpdate): ?array
    {
        $sql = <<<'SQL'
            SELECT d.id,
                   d.empresa_id,
                   e.nombre AS empresa_nombre,
                   d.convenio_id,
                   c.folio AS convenio_folio,
                   c.version_actual AS convenio_version,
                   c.estatus AS convenio_estatus,
                   d.tipo_id,
                   t.nombre AS tipo_nombre,
                   d.ruta,
                   d.estatus,
                   d.observacion,
                   d.creado_en
              FROM rp_empresa_doc AS d
              JOIN rp_empresa AS e ON e.id = d.empresa_id
              LEFT JOIN rp_convenio AS c ON c.id = d.convenio_id
              LEFT JOIN rp_documento_tipo AS t ON t.id = d.tipo_id
             WHERE d.id = :id
             LIMIT 1
        SQL;

        if ($forUpdate) {
            $sql .= ' FOR UPDATE';
        }

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }
}
