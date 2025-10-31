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
                   d.tipo_global_id,
                   d.tipo_personalizado_id,
                   tg.nombre AS tipo_global_nombre,
                   tg.descripcion AS tipo_global_descripcion,
                   tg.obligatorio AS tipo_global_obligatorio,
                   tp.nombre AS tipo_personalizado_nombre,
                   tp.descripcion AS tipo_personalizado_descripcion,
                   tp.obligatorio AS tipo_personalizado_obligatorio,
                   d.ruta,
                   d.estatus,
                   d.observacion,
                   d.creado_en
              FROM rp_empresa_doc AS d
              JOIN rp_empresa AS e ON e.id = d.empresa_id
              LEFT JOIN rp_documento_tipo AS tg ON tg.id = d.tipo_global_id
              LEFT JOIN rp_documento_tipo_empresa AS tp ON tp.id = d.tipo_personalizado_id
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

