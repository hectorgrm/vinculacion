<?php

declare(strict_types=1);

namespace Residencia\Model\Empresadocumentotipo;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaDocumentoTipoEditModel
{
    private PDO $pdo;

    /** @var array<string, bool> */
    private static array $columnCache = [];

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    private function tableHasColumn(string $column): bool
    {
        if (!array_key_exists($column, self::$columnCache)) {
            $sql = <<<'SQL'
                SELECT COUNT(*) AS total
                  FROM INFORMATION_SCHEMA.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'rp_documento_tipo_empresa'
                   AND COLUMN_NAME = :column
            SQL;

            $statement = $this->pdo->prepare($sql);
            $statement->execute([':column' => $column]);

            self::$columnCache[$column] = (int) $statement->fetchColumn() > 0;
        }

        return self::$columnCache[$column];
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

        if ($this->tableHasColumn('tipo_empresa')) {
            $columns[] = 'tipo_empresa';
        }

        if ($this->tableHasColumn('activo')) {
            $columns[] = 'activo';
        }

        return $columns;
    }

    /**
     * @param array<string, mixed>|false $record
     * @return array<string, mixed>|null
     */
    private function decorateRecord(array|false $record): ?array
    {
        if ($record === false) {
            return null;
        }

        return $record;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresaById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   rfc,
                   regimen_fiscal
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDocumentoById(int $documentoId): ?array
    {
        $columns = implode(
            ",\n                   ",
            $this->buildSelectableColumns()
        );

        $sql = <<<'SQL'
            SELECT %s
              FROM rp_documento_tipo_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare(sprintf($sql, $columns));
        $statement->execute([':id' => $documentoId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->decorateRecord($record);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findDocumentoForEmpresa(int $documentoId, int $empresaId): ?array
    {
        $columns = implode(
            ",\n                   ",
            $this->buildSelectableColumns()
        );

        $sql = <<<'SQL'
            SELECT %s
              FROM rp_documento_tipo_empresa
             WHERE id = :id
               AND empresa_id = :empresa_id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare(sprintf($sql, $columns));
        $statement->execute([
            ':id' => $documentoId,
            ':empresa_id' => $empresaId,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $this->decorateRecord($record);
    }

    /**
     * @param array{
     *     empresa_id: int,
     *     documento_id: int,
     *     nombre: string,
     *     descripcion: ?string,
     *     obligatorio: int,
     *     tipo_empresa: string
     * } $payload
     */
    public function updateDocumento(array $payload): void
    {
        $supportsTipoEmpresa = $this->tableHasColumn('tipo_empresa');

        $setParts = [
            'nombre = :nombre',
            'descripcion = :descripcion',
            'obligatorio = :obligatorio',
        ];

        if ($supportsTipoEmpresa) {
            $setParts[] = 'tipo_empresa = :tipo_empresa';
        }

        $sql = <<<'SQL'
            UPDATE rp_documento_tipo_empresa
               SET %s
             WHERE id = :id
               AND empresa_id = :empresa_id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare(sprintf($sql, implode(",\n                   ", $setParts)));
        $statement->bindValue(':nombre', $payload['nombre']);

        if ($payload['descripcion'] === null) {
            $statement->bindValue(':descripcion', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':descripcion', $payload['descripcion']);
        }

        $statement->bindValue(':obligatorio', $payload['obligatorio'], PDO::PARAM_INT);

        if ($supportsTipoEmpresa) {
            $statement->bindValue(':tipo_empresa', $payload['tipo_empresa']);
        }

        $statement->bindValue(':id', $payload['documento_id'], PDO::PARAM_INT);
        $statement->bindValue(':empresa_id', $payload['empresa_id'], PDO::PARAM_INT);

        $statement->execute();
    }
}
