<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class DocumentosModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchDocuments(?string $searchTerm = null, ?string $estatus = null): array
    {
        $sql = $this->baseSelectQuery();

        $conditions = [];
        $params = [];

        if ($estatus !== null) {
            $conditions[] = 'd.estatus = :estatus';
            $params[':estatus'] = $estatus;
        }

        if ($searchTerm !== null) {
            $conditions[] = '(
                est.nombre LIKE :search
                OR est.matricula LIKE :search
                OR t.nombre LIKE :search
            )';
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if ($conditions !== []) {
            $sql .= "\nWHERE " . implode("\n  AND ", $conditions);
        }

        $sql .= "\nORDER BY d.actualizado_en DESC, d.id DESC";

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapDocumentRow'], $rows);
    }

    public function fetchDocumentById(int $documentId): ?array
    {
        $sql = $this->baseSelectQuery() . "\nWHERE d.id = :id\nLIMIT 1";

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapDocumentRow($row);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateDocument(int $documentId, array $data): bool
    {
        $fields = [];
        $params = [':id' => $documentId];

        if (array_key_exists('estatus', $data)) {
            $fields[] = 'estatus = :estatus';
            $params[':estatus'] = $data['estatus'];
        }

        if (array_key_exists('observacion', $data)) {
            $fields[] = 'observacion = :observacion';
            $params[':observacion'] = $data['observacion'];
        }

        if (array_key_exists('recibido', $data)) {
            $fields[] = 'recibido = :recibido';
            $params[':recibido'] = $data['recibido'] ? 1 : 0;
        }

        if (array_key_exists('ruta', $data)) {
            $fields[] = 'ruta = :ruta';
            $params[':ruta'] = $data['ruta'];
        }

        if ($fields === []) {
            return false;
        }

        $sql = 'UPDATE ss_doc SET ' . implode(', ', $fields) . ' WHERE id = :id';

        $statement = $this->pdo->prepare($sql);

        foreach ($params as $param => $value) {
            $type = PDO::PARAM_STR;

            if ($param === ':id' || $param === ':recibido') {
                $type = PDO::PARAM_INT;
            }

            if ($value === null) {
                $type = PDO::PARAM_NULL;
            }

            $statement->bindValue($param, $value, $type);
        }

        return $statement->execute();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchPeriodCatalog(): array
    {
        $sql = <<<'SQL'
            SELECT p.id          AS periodo_id,
                   p.numero      AS periodo_numero,
                   p.estatus     AS periodo_estatus,
                   p.servicio_id AS servicio_id,
                   est.id        AS estudiante_id,
                   est.nombre    AS estudiante_nombre,
                   est.matricula AS estudiante_matricula
            FROM ss_periodo AS p
            INNER JOIN ss_servicio AS srv ON srv.id = p.servicio_id
            INNER JOIN ss_estudiante AS est ON est.id = srv.estudiante_id
            ORDER BY est.nombre ASC, p.numero ASC, p.id ASC
        SQL;

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapPeriodRow'], $rows);
    }

    public function fetchPeriodById(int $periodoId): ?array
    {
        $sql = <<<'SQL'
            SELECT p.id          AS periodo_id,
                   p.numero      AS periodo_numero,
                   p.estatus     AS periodo_estatus,
                   p.servicio_id AS servicio_id,
                   est.id        AS estudiante_id,
                   est.nombre    AS estudiante_nombre,
                   est.matricula AS estudiante_matricula
            FROM ss_periodo AS p
            INNER JOIN ss_servicio AS srv ON srv.id = p.servicio_id
            INNER JOIN ss_estudiante AS est ON est.id = srv.estudiante_id
            WHERE p.id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $periodoId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapPeriodRow($row);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchDocumentTypeCatalog(): array
    {
        $sql = 'SELECT id, nombre FROM ss_doc_tipo ORDER BY nombre ASC, id ASC';

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapDocumentTypeRow'], $rows);
    }

    public function fetchDocumentTypeById(int $tipoId): ?array
    {
        $sql = 'SELECT id, nombre FROM ss_doc_tipo WHERE id = :id LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $tipoId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        return $this->mapDocumentTypeRow($row);
    }

    public function documentExistsForPeriodAndType(int $periodoId, int $tipoId): bool
    {
        $sql = 'SELECT id FROM ss_doc WHERE periodo_id = :periodo_id AND tipo_id = :tipo_id LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':periodo_id', $periodoId, PDO::PARAM_INT);
        $statement->bindValue(':tipo_id', $tipoId, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false;
    }

    public function createDocument(
        int $periodoId,
        int $tipoId,
        ?string $ruta,
        bool $recibido,
        ?string $observacion = null,
        string $estatus = 'pendiente'
    ): int {
        $sql = <<<'SQL'
            INSERT INTO ss_doc (periodo_id, tipo_id, ruta, recibido, estatus, observacion)
            VALUES (:periodo_id, :tipo_id, :ruta, :recibido, :estatus, :observacion)
        SQL;

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':periodo_id', $periodoId, PDO::PARAM_INT);
        $statement->bindValue(':tipo_id', $tipoId, PDO::PARAM_INT);

        if ($ruta === null) {
            $statement->bindValue(':ruta', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':ruta', $ruta, PDO::PARAM_STR);
        }

        $statement->bindValue(':recibido', $recibido ? 1 : 0, PDO::PARAM_INT);
        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);

        if ($observacion === null) {
            $statement->bindValue(':observacion', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':observacion', $observacion, PDO::PARAM_STR);
        }

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    public function deleteDocument(int $documentId): bool
    {
        $sql = 'DELETE FROM ss_doc WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    private function baseSelectQuery(): string
    {
        return <<<'SQL'
            SELECT d.id,
                   d.ruta,
                   d.recibido,
                   d.estatus,
                   d.observacion,
                   d.actualizado_en,
                   p.id          AS periodo_id,
                   p.numero      AS periodo_numero,
                   srv.id        AS servicio_id,
                   est.id        AS estudiante_id,
                   est.nombre    AS estudiante_nombre,
                   est.matricula AS estudiante_matricula,
                   t.id          AS tipo_id,
                   t.nombre      AS tipo_nombre
            FROM ss_doc AS d
            INNER JOIN ss_periodo AS p ON p.id = d.periodo_id
            INNER JOIN ss_servicio AS srv ON srv.id = p.servicio_id
            INNER JOIN ss_estudiante AS est ON est.id = srv.estudiante_id
            INNER JOIN ss_doc_tipo AS t ON t.id = d.tipo_id
        SQL;
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    private function mapDocumentRow(array $row): array
    {
        return [
            'id'             => (int) $row['id'],
            'ruta'           => $row['ruta'],
            'recibido'       => isset($row['recibido']) ? (bool) (int) $row['recibido'] : false,
            'estatus'        => $row['estatus'],
            'observacion'    => $row['observacion'],
            'actualizado_en' => $row['actualizado_en'],
            'periodo'        => [
                'id'     => (int) $row['periodo_id'],
                'numero' => $row['periodo_numero'] !== null ? (int) $row['periodo_numero'] : null,
            ],
            'servicio_id'    => (int) $row['servicio_id'],
            'estudiante'     => [
                'id'        => (int) $row['estudiante_id'],
                'nombre'    => $row['estudiante_nombre'] ?? '',
                'matricula' => $row['estudiante_matricula'] ?? '',
            ],
            'tipo'           => [
                'id'     => (int) $row['tipo_id'],
                'nombre' => $row['tipo_nombre'] ?? '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    private function mapPeriodRow(array $row): array
    {
        return [
            'id'          => (int) $row['periodo_id'],
            'numero'      => $row['periodo_numero'] !== null ? (int) $row['periodo_numero'] : null,
            'estatus'     => $row['periodo_estatus'],
            'servicio_id' => (int) $row['servicio_id'],
            'estudiante'  => [
                'id'        => (int) $row['estudiante_id'],
                'nombre'    => $row['estudiante_nombre'] ?? '',
                'matricula' => $row['estudiante_matricula'] ?? '',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $row
     *
     * @return array<string, mixed>
     */
    private function mapDocumentTypeRow(array $row): array
    {
        return [
            'id'          => (int) $row['id'],
            'nombre'      => $row['nombre'] ?? '',
            'descripcion' => $row['descripcion'] ?? null,
        ];
    }
}
