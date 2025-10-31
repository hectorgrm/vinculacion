<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DocumentoUploadModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchEmpresas(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre
              FROM rp_empresa
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTiposGlobales(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   descripcion,
                   obligatorio
              FROM rp_documento_tipo
             WHERE activo = 1
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTiposPersonalizados(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   descripcion,
                   obligatorio,
                   creado_en
              FROM rp_documento_tipo_empresa
             WHERE empresa_id = :empresa_id
               AND activo = 1
             ORDER BY obligatorio DESC, nombre ASC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    public function empresaExists(int $empresaId): bool
    {
        $sql = 'SELECT COUNT(*) FROM rp_empresa WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function tipoGlobalExists(int $tipoId): bool
    {
        $sql = 'SELECT COUNT(*) FROM rp_documento_tipo WHERE id = :id AND activo = 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $tipoId]);

        return (int) $statement->fetchColumn() > 0;
    }

    public function tipoPersonalizadoBelongsToEmpresa(int $tipoPersonalizadoId, int $empresaId): bool
    {
        $sql = <<<'SQL'
            SELECT COUNT(*)
              FROM rp_documento_tipo_empresa
             WHERE id = :id
               AND empresa_id = :empresa_id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':id' => $tipoPersonalizadoId,
            ':empresa_id' => $empresaId,
        ]);

        return (int) $statement->fetchColumn() > 0;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function saveDocumento(array $data): array
    {
        $empresaId = (int) ($data['empresa_id'] ?? 0);
        $tipoGlobalId = $data['tipo_global_id'] !== null ? (int) $data['tipo_global_id'] : null;
        $tipoPersonalizadoId = $data['tipo_personalizado_id'] !== null ? (int) $data['tipo_personalizado_id'] : null;

        $existing = $this->findExistingDocumento($empresaId, $tipoGlobalId, $tipoPersonalizadoId);

        if ($existing !== null) {
            $sql = <<<'SQL'
                UPDATE rp_empresa_doc
                   SET tipo_global_id = :tipo_global_id,
                       tipo_personalizado_id = :tipo_personalizado_id,
                       ruta = :ruta,
                       estatus = :estatus,
                       observacion = :observacion
                 WHERE id = :id
                 LIMIT 1
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':tipo_global_id' => $tipoGlobalId,
                ':tipo_personalizado_id' => $tipoPersonalizadoId,
                ':ruta' => (string) ($data['ruta'] ?? ''),
                ':estatus' => (string) ($data['estatus'] ?? 'pendiente'),
                ':observacion' => $data['observacion'] ?? null,
                ':id' => (int) $existing['id'],
            ]);

            $this->cleanupDuplicates($empresaId, $tipoGlobalId, $tipoPersonalizadoId, (int) $existing['id']);

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
                :estatus,
                :observacion
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
            ':tipo_global_id' => $tipoGlobalId,
            ':tipo_personalizado_id' => $tipoPersonalizadoId,
            ':ruta' => (string) ($data['ruta'] ?? ''),
            ':estatus' => (string) ($data['estatus'] ?? 'pendiente'),
            ':observacion' => $data['observacion'] ?? null,
        ]);

        $newId = (int) $this->pdo->lastInsertId();

        $this->cleanupDuplicates($empresaId, $tipoGlobalId, $tipoPersonalizadoId, $newId);

        return [
            'id' => $newId,
            'replaced_path' => null,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findExistingDocumento(int $empresaId, mixed $tipoGlobalId, mixed $tipoPersonalizadoId): ?array
    {
        $tipoGlobalId = $tipoGlobalId !== null ? (int) $tipoGlobalId : null;
        $tipoPersonalizadoId = $tipoPersonalizadoId !== null ? (int) $tipoPersonalizadoId : null;

        if ($tipoGlobalId === null && $tipoPersonalizadoId === null) {
            return null;
        }

        if ($tipoPersonalizadoId !== null) {
            $sql = <<<'SQL'
                SELECT id, ruta
                  FROM rp_empresa_doc
                 WHERE empresa_id = :empresa_id
                   AND tipo_personalizado_id = :tipo_personalizado_id
                 ORDER BY actualizado_en DESC, id DESC
                 LIMIT 1
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':empresa_id' => $empresaId,
                ':tipo_personalizado_id' => $tipoPersonalizadoId,
            ]);
        } elseif ($tipoGlobalId !== null) {
            $sql = <<<'SQL'
                SELECT id, ruta
                  FROM rp_empresa_doc
                 WHERE empresa_id = :empresa_id
                   AND tipo_global_id = :tipo_global_id
                   AND tipo_personalizado_id IS NULL
                 ORDER BY actualizado_en DESC, id DESC
                 LIMIT 1
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':empresa_id' => $empresaId,
                ':tipo_global_id' => $tipoGlobalId,
            ]);
        } else {
            return null;
        }

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    private function cleanupDuplicates(int $empresaId, ?int $tipoGlobalId, ?int $tipoPersonalizadoId, int $keepId): void
    {
        $conditions = [
            'empresa_id = :empresa_id',
            'id <> :keep_id',
        ];
        $params = [
            ':empresa_id' => $empresaId,
            ':keep_id' => $keepId,
        ];

        if ($tipoPersonalizadoId !== null) {
            $conditions[] = 'tipo_personalizado_id = :tipo_personalizado_id';
            $params[':tipo_personalizado_id'] = $tipoPersonalizadoId;
        } elseif ($tipoGlobalId !== null) {
            $conditions[] = 'tipo_global_id = :tipo_global_id';
            $conditions[] = 'tipo_personalizado_id IS NULL';
            $params[':tipo_global_id'] = $tipoGlobalId;
        } else {
            return;
        }

        $sql = 'DELETE FROM rp_empresa_doc WHERE ' . implode(' AND ', $conditions);
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
    }
}
