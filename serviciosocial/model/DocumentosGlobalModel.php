<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class DocumentosGlobalModel
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
            $conditions[] = 'g.estatus = :estatus';
            $params[':estatus'] = $estatus;
        }

        if ($searchTerm !== null) {
            $conditions[] = '(
                g.nombre LIKE :search
                OR g.descripcion LIKE :search
                OR t.nombre LIKE :search
            )';
            $params[':search'] = '%' . $searchTerm . '%';
        }

        if ($conditions !== []) {
            $sql .= "\nWHERE " . implode("\n  AND ", $conditions);
        }

        $sql .= "\nORDER BY g.actualizado_en DESC, g.id DESC";

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapDocumentRow'], $rows);
    }

    public function fetchDocumentById(int $documentId): ?array
    {
        $sql = $this->baseSelectQuery() . "\nWHERE g.id = :id\nLIMIT 1";

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
     * @return array<int, array<string, mixed>>
     */
    public function fetchDocumentTypeCatalog(): array
    {
        $sql = 'SELECT id, nombre, descripcion FROM ss_doc_tipo ORDER BY nombre ASC, id ASC';

        $statement = $this->pdo->query($sql);
        $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

        return array_map([$this, 'mapDocumentTypeRow'], $rows);
    }

    public function createDocument(int $tipoId, string $nombre, ?string $descripcion, string $ruta, string $estatus): int
    {
        $sql = <<<'SQL'
            INSERT INTO ss_doc_global (tipo_id, nombre, descripcion, ruta, estatus)
            VALUES (:tipo_id, :nombre, :descripcion, :ruta, :estatus)
        SQL;

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':tipo_id', $tipoId, PDO::PARAM_INT);
        $statement->bindValue(':nombre', $nombre, PDO::PARAM_STR);

        if ($descripcion === null) {
            $statement->bindValue(':descripcion', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        }

        $statement->bindValue(':ruta', $ruta, PDO::PARAM_STR);
        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateDocument(int $documentId, array $data): bool
    {
        $fields = [];
        $params = [':id' => $documentId];

        if (array_key_exists('tipo_id', $data)) {
            $fields[] = 'tipo_id = :tipo_id';
            $params[':tipo_id'] = (int) $data['tipo_id'];
        }

        if (array_key_exists('nombre', $data)) {
            $fields[] = 'nombre = :nombre';
            $params[':nombre'] = (string) $data['nombre'];
        }

        if (array_key_exists('descripcion', $data)) {
            $fields[] = 'descripcion = :descripcion';
            $params[':descripcion'] = $data['descripcion'];
        }

        if (array_key_exists('ruta', $data)) {
            $fields[] = 'ruta = :ruta';
            $params[':ruta'] = (string) $data['ruta'];
        }

        if (array_key_exists('estatus', $data)) {
            $fields[] = 'estatus = :estatus';
            $params[':estatus'] = (string) $data['estatus'];
        }

        if ($fields === []) {
            return false;
        }

        $sql = 'UPDATE ss_doc_global SET ' . implode(', ', $fields) . ' WHERE id = :id';
        $statement = $this->pdo->prepare($sql);

        foreach ($params as $param => $value) {
            $type = PDO::PARAM_STR;

            if ($param === ':id' || $param === ':tipo_id') {
                $type = PDO::PARAM_INT;
            }

            if ($param === ':descripcion' && $value === null) {
                $type = PDO::PARAM_NULL;
            }

            $statement->bindValue($param, $value, $type);
        }

        return $statement->execute();
    }

    public function deleteDocument(int $documentId): bool
    {
        $sql = 'DELETE FROM ss_doc_global WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();

        return $statement->rowCount() > 0;
    }

    private function baseSelectQuery(): string
    {
        return <<<'SQL'
            SELECT g.id,
                   g.tipo_id,
                   g.nombre,
                   g.descripcion,
                   g.ruta,
                   g.estatus,
                   g.creado_en,
                   g.actualizado_en,
                   t.nombre AS tipo_nombre,
                   t.descripcion AS tipo_descripcion
            FROM ss_doc_global AS g
            LEFT JOIN ss_doc_tipo AS t ON t.id = g.tipo_id
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
            'nombre'         => $row['nombre'] ?? '',
            'descripcion'    => $row['descripcion'],
            'ruta'           => $row['ruta'] ?? '',
            'estatus'        => $row['estatus'] ?? 'activo',
            'creado_en'      => $row['creado_en'],
            'actualizado_en' => $row['actualizado_en'],
            'tipo'           => [
                'id'     => isset($row['tipo_id']) ? (int) $row['tipo_id'] : null,
                'nombre' => $row['tipo_nombre'] ?? null,
                'descripcion' => $row['tipo_descripcion'] ?? null,
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
