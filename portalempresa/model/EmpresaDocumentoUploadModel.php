<?php

declare(strict_types=1);

namespace PortalEmpresa\Model;

require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaDocumentoUploadModel
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresaById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   numero_control,
                   regimen_fiscal
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDocumentSlot(int $empresaId, string $scope, int $tipoId, ?string $tipoEmpresa = null): ?array
    {
        $scope = $scope === 'custom' ? 'custom' : 'global';

        if ($scope === 'custom') {
            return $this->findCustomDocumentSlot($empresaId, $tipoId);
        }

        return $this->findGlobalDocumentSlot($empresaId, $tipoId, $tipoEmpresa);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findGlobalDocumentSlot(int $empresaId, int $tipoId, ?string $tipoEmpresa = null): ?array
    {
        $sql = <<<'SQL'
            SELECT 'global' AS scope,
                   t.id AS tipo_id,
                   t.nombre AS tipo_nombre,
                   t.descripcion AS tipo_descripcion,
                   t.obligatorio AS tipo_obligatorio,
                   t.tipo_empresa,
                   doc.id AS documento_id,
                   doc.ruta AS documento_ruta,
                   doc.estatus AS documento_estatus,
                   doc.observacion AS documento_observacion,
                   doc.creado_en AS documento_creado_en,
                   doc.actualizado_en AS documento_actualizado_en
              FROM rp_documento_tipo AS t
              LEFT JOIN rp_empresa_doc AS doc
                ON doc.empresa_id = :empresa_id
               AND doc.tipo_global_id = t.id
               AND doc.tipo_personalizado_id IS NULL
             WHERE t.id = :tipo_id
               AND t.activo = 1
        SQL;

        $params = [
            ':empresa_id' => $empresaId,
            ':tipo_id' => $tipoId,
        ];

        if ($tipoEmpresa !== null) {
            $sql .= ' AND (t.tipo_empresa = :tipo_empresa OR t.tipo_empresa = \'ambas\')';
            $params[':tipo_empresa'] = $tipoEmpresa;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findCustomDocumentSlot(int $empresaId, int $tipoId): ?array
    {
        $sql = <<<'SQL'
            SELECT 'custom' AS scope,
                   tipo.id AS tipo_id,
                   tipo.nombre AS tipo_nombre,
                   tipo.descripcion AS tipo_descripcion,
                   tipo.obligatorio AS tipo_obligatorio,
                   doc.id AS documento_id,
                   doc.ruta AS documento_ruta,
                   doc.estatus AS documento_estatus,
                   doc.observacion AS documento_observacion,
                   doc.creado_en AS documento_creado_en,
                   doc.actualizado_en AS documento_actualizado_en
              FROM rp_documento_tipo_empresa AS tipo
              LEFT JOIN rp_empresa_doc AS doc
                ON doc.empresa_id = :empresa_id
               AND doc.tipo_personalizado_id = tipo.id
             WHERE tipo.id = :tipo_id
               AND tipo.empresa_id = :empresa_id
               AND tipo.activo = 1
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
            ':tipo_id' => $tipoId,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array{id: int, replaced_path: ?string}
     */
    public function replaceDocumento(int $empresaId, string $scope, int $tipoId, string $ruta, ?string $observacion = null): array
    {
        $scope = $scope === 'custom' ? 'custom' : 'global';
        $existing = $this->findExistingDocumento($empresaId, $scope, $tipoId);

        if ($existing !== null) {
            $sql = <<<'SQL'
                UPDATE rp_empresa_doc
                   SET tipo_global_id = :tipo_global_id,
                       tipo_personalizado_id = :tipo_personalizado_id,
                       ruta = :ruta,
                       estatus = 'revision',
                       observacion = :observacion
                 WHERE id = :id
                 LIMIT 1
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':tipo_global_id' => $scope === 'global' ? $tipoId : null,
                ':tipo_personalizado_id' => $scope === 'custom' ? $tipoId : null,
                ':ruta' => $ruta,
                ':observacion' => $observacion,
                ':id' => (int) $existing['id'],
            ]);

            $this->cleanupDuplicates($empresaId, $scope, $tipoId, (int) $existing['id']);

            return [
                'id' => (int) $existing['id'],
                'replaced_path' => $existing['ruta'] ?? null,
            ];
        }

        $sql = <<<'SQL'
            INSERT INTO rp_empresa_doc (
                empresa_id,
                tipo_global_id,
                tipo_personalizado_id,
                ruta,
                estatus,
                observacion
            ) VALUES (
                :empresa_id,
                :tipo_global_id,
                :tipo_personalizado_id,
                :ruta,
                'revision',
                :observacion
            )
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
            ':tipo_global_id' => $scope === 'global' ? $tipoId : null,
            ':tipo_personalizado_id' => $scope === 'custom' ? $tipoId : null,
            ':ruta' => $ruta,
            ':observacion' => $observacion,
        ]);

        $newId = (int) $this->connection->lastInsertId();

        $this->cleanupDuplicates($empresaId, $scope, $tipoId, $newId);

        return [
            'id' => $newId,
            'replaced_path' => null,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findExistingDocumento(int $empresaId, string $scope, int $tipoId): ?array
    {
        if ($scope === 'custom') {
            $sql = <<<'SQL'
                SELECT id,
                       ruta,
                       estatus
                  FROM rp_empresa_doc
                 WHERE empresa_id = :empresa_id
                   AND tipo_personalizado_id = :tipo_id
                 ORDER BY actualizado_en DESC, id DESC
                 LIMIT 1
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':empresa_id' => $empresaId,
                ':tipo_id' => $tipoId,
            ]);
        } else {
            $sql = <<<'SQL'
                SELECT id,
                       ruta,
                       estatus
                  FROM rp_empresa_doc
                 WHERE empresa_id = :empresa_id
                   AND tipo_global_id = :tipo_id
                   AND tipo_personalizado_id IS NULL
                 ORDER BY actualizado_en DESC, id DESC
                 LIMIT 1
            SQL;

            $statement = $this->connection->prepare($sql);
            $statement->execute([
                ':empresa_id' => $empresaId,
                ':tipo_id' => $tipoId,
            ]);
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    private function cleanupDuplicates(int $empresaId, string $scope, int $tipoId, int $keepId): void
    {
        $conditions = [
            'empresa_id = :empresa_id',
            'id <> :keep_id',
        ];

        $params = [
            ':empresa_id' => $empresaId,
            ':keep_id' => $keepId,
        ];

        if ($scope === 'custom') {
            $conditions[] = 'tipo_personalizado_id = :tipo_id';
            $params[':tipo_id'] = $tipoId;
        } else {
            $conditions[] = 'tipo_global_id = :tipo_id';
            $conditions[] = 'tipo_personalizado_id IS NULL';
            $params[':tipo_id'] = $tipoId;
        }

        $sql = 'DELETE FROM rp_empresa_doc WHERE ' . implode(' AND ', $conditions);

        $statement = $this->connection->prepare($sql);
        $statement->execute($params);
    }
}

