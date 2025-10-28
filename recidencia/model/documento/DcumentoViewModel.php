<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DocumentoViewModel
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

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $documentId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchHistory(int $empresaId, int $tipoId, int $excludeDocumentId, int $limit = 8): array
    {
        $limit = max(1, $limit);

        $sql = <<<'SQL'
            SELECT d.id,
                   d.convenio_id,
                   c.folio AS convenio_folio,
                   c.version_actual AS convenio_version,
                   d.estatus,
                   d.ruta,
                   d.creado_en
              FROM rp_empresa_doc AS d
              LEFT JOIN rp_convenio AS c ON c.id = d.convenio_id
             WHERE d.empresa_id = :empresa_id
               AND d.tipo_id = :tipo_id
               AND d.id <> :document_id
             ORDER BY d.creado_en DESC, d.id DESC
             LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':empresa_id', $empresaId, PDO::PARAM_INT);
        $statement->bindValue(':tipo_id', $tipoId, PDO::PARAM_INT);
        $statement->bindValue(':document_id', $excludeDocumentId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
