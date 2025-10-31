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
    public function insertDocumento(array $data): int
    {
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
            ':empresa_id' => (int) ($data['empresa_id'] ?? 0),
            ':tipo_global_id' => $data['tipo_global_id'],
            ':tipo_personalizado_id' => $data['tipo_personalizado_id'],
            ':ruta' => (string) ($data['ruta'] ?? ''),
            ':estatus' => (string) ($data['estatus'] ?? 'pendiente'),
            ':observacion' => $data['observacion'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
