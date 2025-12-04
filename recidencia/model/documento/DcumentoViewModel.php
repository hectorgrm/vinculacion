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
                   e.estatus AS empresa_estatus,
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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchHistory(
        int $empresaId,
        ?int $tipoGlobalId,
        ?int $tipoPersonalizadoId,
        int $excludeDocumentId,
        int $limit = 8
    ): array
    {
        $limit = max(1, $limit);

        $conditions = [
            'd.empresa_id = :empresa_id',
            'd.id <> :document_id',
        ];
        $params = [
            ':empresa_id' => $empresaId,
            ':document_id' => $excludeDocumentId,
        ];

        if ($tipoGlobalId !== null) {
            $conditions[] = 'd.tipo_global_id = :tipo_global_id';
            $params[':tipo_global_id'] = $tipoGlobalId;
        }

        if ($tipoPersonalizadoId !== null) {
            $conditions[] = 'd.tipo_personalizado_id = :tipo_personalizado_id';
            $params[':tipo_personalizado_id'] = $tipoPersonalizadoId;
        }

        $sql = <<<'SQL'
            SELECT d.id,
                   d.estatus,
                   d.ruta,
                   d.creado_en
              FROM rp_empresa_doc AS d
             WHERE %s
             ORDER BY d.creado_en DESC, d.id DESC
             LIMIT :limit
SQL;

        if ($tipoGlobalId === null && $tipoPersonalizadoId === null) {
            // Nada que comparar, devolver historial vacío.
            return [];
        }

        $sql = sprintf($sql, implode(' AND ', $conditions));

        $statement = $this->pdo->prepare($sql);
        foreach ($params as $param => $value) {
            $statement->bindValue($param, $value, PDO::PARAM_INT);
        }
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAuditHistory(int $documentId, int $limit = 12): array
    {
        $limit = max(1, $limit);

        $sql = <<<'SQL'
            SELECT a.id,
                   a.accion,
                   a.actor_tipo,
                   a.actor_id,
                   a.entidad,
                   a.entidad_id,
                   a.ip,
                   a.ts,
                   CASE
                       WHEN a.actor_tipo = 'usuario' THEN u.nombre
                       WHEN a.actor_tipo = 'empresa' THEN e.nombre
                       ELSE NULL
                   END AS actor_nombre
              FROM auditoria AS a
              LEFT JOIN usuario AS u ON u.id = a.actor_id AND a.actor_tipo = 'usuario'
              LEFT JOIN rp_empresa AS e ON e.id = a.actor_id AND a.actor_tipo = 'empresa'
             WHERE a.entidad IN ('rp_empresa_doc', 'documento')
               AND a.entidad_id = :document_id
             ORDER BY a.ts DESC, a.id DESC
             LIMIT :limit
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':document_id', $documentId, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }
}
