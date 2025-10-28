<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DocumentoReviewModel
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

    public function updateStatus(int $documentId, string $estatus, ?string $observacion): void
    {
        $sql = <<<'SQL'
            UPDATE rp_empresa_doc
               SET estatus = :estatus,
                   observacion = :observacion
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);

        if ($observacion === null || $observacion === '') {
            $statement->bindValue(':observacion', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':observacion', $observacion, PDO::PARAM_STR);
        }

        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();
    }
}

