<?php

declare(strict_types=1);

namespace Residencia\Model\Empresadocumentotipo;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use PDOException;
use RuntimeException;
use Throwable;

class EmpresaDocumentoTipoDeleteModel
{
    private PDO $pdo;

    /** @var array<string, bool> */
    private static array $tableColumnCache = [];

    /** @var array<int, array{table: string, column: string}>|null */
    private static ?array $usageColumnCache = null;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDocumentoForDelete(int $documentoId, ?int $empresaId = null): ?array
    {
        $columns = $this->buildDocumentoSelectList();

        $sql = sprintf(
            <<<'SQL'
                SELECT %s
                  FROM rp_documento_tipo_empresa AS d
             LEFT JOIN rp_empresa AS e ON e.id = d.empresa_id
                 WHERE d.id = :id
            SQL,
            $columns
        );

        $params = [
            ':id' => $documentoId,
        ];

        if ($empresaId !== null) {
            $sql .= ' AND d.empresa_id = :empresa_id';
            $params[':empresa_id'] = $empresaId;
        }

        $sql .= ' LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            return null;
        }

        $empresa = null;
        if ($record['empresa_lookup_id'] !== null) {
            $empresa = [
                'id' => (int) $record['empresa_lookup_id'],
                'nombre' => $record['empresa_lookup_nombre'],
                'rfc' => $record['empresa_lookup_rfc'],
                'regimen_fiscal' => $record['empresa_lookup_regimen_fiscal'],
            ];
        }

        unset(
            $record['empresa_lookup_id'],
            $record['empresa_lookup_nombre'],
            $record['empresa_lookup_rfc'],
            $record['empresa_lookup_regimen_fiscal']
        );

        $record['supports_activo'] = $this->tableHasColumn('rp_documento_tipo_empresa', 'activo');
        $record['supports_tipo_empresa'] = $this->tableHasColumn('rp_documento_tipo_empresa', 'tipo_empresa');

        return [
            'documento' => $record,
            'empresa' => $empresa,
        ];
    }

    public function countLinkedUploadsFor(int $documentoId): int
    {
        return $this->countLinkedUploads($documentoId);
    }

    /**
     * @return array{
     *     action: 'deleted'|'deactivated',
     *     documento: array<string, mixed>,
     *     usageCount: int,
     *     supportsActivo: bool
     * }
     */
    public function deleteOrDeactivate(int $documentoId, ?int $empresaId = null): array
    {
        $this->pdo->beginTransaction();

        try {
            $documento = $this->fetchForUpdate($documentoId, $empresaId);

            if ($documento === null) {
                throw new RuntimeException('El documento individual solicitado no existe.', 404);
            }

            $supportsActivo = $this->tableHasColumn('rp_documento_tipo_empresa', 'activo');
            $usageCount = $this->countLinkedUploads($documentoId);

            if ($usageCount > 0) {
                if (!$supportsActivo) {
                    throw new RuntimeException(
                        'El documento individual tiene archivos vinculados y la tabla no admite la columna activo.',
                        409
                    );
                }

                $updateSql = <<<'SQL'
                    UPDATE rp_documento_tipo_empresa
                       SET activo = 0
                     WHERE id = :id
                SQL;

                $update = $this->pdo->prepare($updateSql);
                $update->execute([':id' => $documentoId]);

                $documento['activo'] = 0;

                $this->pdo->commit();

                return [
                    'action' => 'deactivated',
                    'documento' => $documento,
                    'usageCount' => $usageCount,
                    'supportsActivo' => $supportsActivo,
                ];
            }

            $deleteSql = <<<'SQL'
                DELETE FROM rp_documento_tipo_empresa
                      WHERE id = :id
                    LIMIT 1
            SQL;

            $delete = $this->pdo->prepare($deleteSql);
            $delete->execute([':id' => $documentoId]);

            if ($delete->rowCount() !== 1) {
                throw new RuntimeException('No se pudo eliminar el documento individual.', 500);
            }

            $this->pdo->commit();

            return [
                'action' => 'deleted',
                'documento' => $documento,
                'usageCount' => $usageCount,
                'supportsActivo' => $supportsActivo,
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
    private function fetchForUpdate(int $documentoId, ?int $empresaId): ?array
    {
        $columns = implode(
            ",\n                   ",
            $this->buildSelectableColumns()
        );

        $sql = sprintf(
            <<<'SQL'
                SELECT %s
                  FROM rp_documento_tipo_empresa
                 WHERE id = :id
            SQL,
            $columns
        );

        $params = [
            ':id' => $documentoId,
        ];

        if ($empresaId !== null) {
            $sql .= ' AND empresa_id = :empresa_id';
            $params[':empresa_id'] = $empresaId;
        }

        $sql .= ' LIMIT 1 FOR UPDATE';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @return array<int, string>
     */
    private function buildDocumentoSelectList(): string
    {
        $columns = [];

        foreach ($this->buildSelectableColumns() as $column) {
            $columns[] = 'd.' . $column;
        }

        $columns[] = 'e.id AS empresa_lookup_id';
        $columns[] = 'e.nombre AS empresa_lookup_nombre';
        $columns[] = 'e.rfc AS empresa_lookup_rfc';
        $columns[] = 'e.regimen_fiscal AS empresa_lookup_regimen_fiscal';

        return implode(",\n                 ", $columns);
    }

    /**
     * @return array<int, string>
     */
    private function buildSelectableColumns(): array
    {
        $columns = [
            'id',
            'empresa_id',
            'nombre',
            'descripcion',
            'obligatorio',
        ];

        if ($this->tableHasColumn('rp_documento_tipo_empresa', 'tipo_empresa')) {
            $columns[] = 'tipo_empresa';
        }

        if ($this->tableHasColumn('rp_documento_tipo_empresa', 'activo')) {
            $columns[] = 'activo';
        }

        if ($this->tableHasColumn('rp_documento_tipo_empresa', 'creado_en')) {
            $columns[] = 'creado_en';
        }

        return $columns;
    }

    private function tableHasColumn(string $table, string $column): bool
    {
        $cacheKey = $table . '.' . $column;

        if (!array_key_exists($cacheKey, self::$tableColumnCache)) {
            $sql = <<<'SQL'
                SELECT COUNT(*) AS total
                  FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = :table
                   AND COLUMN_NAME = :column
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([
                ':table' => $table,
                ':column' => $column,
            ]);

            self::$tableColumnCache[$cacheKey] = (int) $statement->fetchColumn() > 0;
        }

        return self::$tableColumnCache[$cacheKey];
    }

    private function countLinkedUploads(int $documentoId): int
    {
        $total = 0;

        foreach ($this->getUsageColumnCandidates() as $meta) {
            $table = $meta['table'];
            $column = $meta['column'];

            $sql = sprintf(
                'SELECT COUNT(*) FROM %s WHERE %s = :id',
                $this->quoteIdentifier($table),
                $this->quoteIdentifier($column)
            );

            try {
                $statement = $this->pdo->prepare($sql);
                $statement->execute([':id' => $documentoId]);

                $total += (int) $statement->fetchColumn();
            } catch (PDOException) {
                // Ignore tables that cannot be queried.
            }
        }

        return $total;
    }

    /**
     * @return array<int, array{table: string, column: string}>
     */
    private function getUsageColumnCandidates(): array
    {
        if (self::$usageColumnCache !== null) {
            return self::$usageColumnCache;
        }

        $candidates = [];

        $fkSql = <<<'SQL'
            SELECT TABLE_NAME, COLUMN_NAME
              FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND REFERENCED_TABLE_NAME = 'rp_documento_tipo_empresa'
               AND REFERENCED_COLUMN_NAME = 'id'
               AND TABLE_NAME <> 'rp_documento_tipo_empresa'
        SQL;

        $statement = $this->pdo->query($fkSql);

        if ($statement !== false) {
            $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

            foreach ($rows as $row) {
                $table = $row['TABLE_NAME'] ?? null;
                $column = $row['COLUMN_NAME'] ?? null;

                if (!is_string($table) || !is_string($column)) {
                    continue;
                }

                $key = $table . '.' . $column;
                $candidates[$key] = [
                    'table' => $table,
                    'column' => $column,
                ];
            }
        }

        if ($candidates === []) {
            $columnSql = <<<'SQL'
                SELECT TABLE_NAME, COLUMN_NAME
                  FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND COLUMN_NAME = 'documento_tipo_empresa_id'
                   AND TABLE_NAME <> 'rp_documento_tipo_empresa'
            SQL;

            $statement = $this->pdo->query($columnSql);

            if ($statement !== false) {
                $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    $table = $row['TABLE_NAME'] ?? null;
                    $column = $row['COLUMN_NAME'] ?? null;

                    if (!is_string($table) || !is_string($column)) {
                        continue;
                    }

                    $key = $table . '.' . $column;
                    if (!array_key_exists($key, $candidates)) {
                        $candidates[$key] = [
                            'table' => $table,
                            'column' => $column,
                        ];
                    }
                }
            }
        }

        self::$usageColumnCache = array_values($candidates);

        return self::$usageColumnCache;
    }

    private function quoteIdentifier(string $identifier): string
    {
        return '`' . str_replace('`', '``', $identifier) . '`';
    }
}
