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
}
