<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class PeriodoModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $sql = 'SELECT id, servicio_id, numero, estatus, abierto_en, cerrado_en FROM ss_periodo ORDER BY abierto_en DESC, id DESC';
        $stmt = $this->pdo->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(string $term): array
    {
        $term = trim($term);
        if ($term === '') {
            return $this->getAll();
        }

        $likeTerm = '%' . strtolower($term) . '%';

        $sql = <<<SQL
            SELECT id, servicio_id, numero, estatus, abierto_en, cerrado_en
            FROM ss_periodo
            WHERE CAST(servicio_id AS CHAR) LIKE :numericTerm
               OR CAST(numero AS CHAR) LIKE :numericTerm
               OR LOWER(estatus) LIKE :statusTerm
            ORDER BY abierto_en DESC, id DESC
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':numericTerm' => $likeTerm,
            ':statusTerm'  => $likeTerm,
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, servicio_id, numero, estatus, abierto_en, cerrado_en FROM ss_periodo WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $sql = <<<'SQL'
            INSERT INTO ss_periodo (servicio_id, numero, estatus, abierto_en, cerrado_en)
            VALUES (:servicio_id, :numero, :estatus, :abierto_en, :cerrado_en)
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':servicio_id', (int) ($data['servicio_id'] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(':numero', (int) ($data['numero'] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(':estatus', (string) ($data['estatus'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':abierto_en', (string) ($data['abierto_en'] ?? ''), PDO::PARAM_STR);

        if (array_key_exists('cerrado_en', $data) && $data['cerrado_en'] !== null && $data['cerrado_en'] !== '') {
            $stmt->bindValue(':cerrado_en', (string) $data['cerrado_en'], PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':cerrado_en', null, PDO::PARAM_NULL);
        }

        $stmt->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $sql = <<<'SQL'
            UPDATE ss_periodo
               SET numero = :numero,
                   estatus = :estatus,
                   abierto_en = :abierto_en,
                   cerrado_en = :cerrado_en
             WHERE id = :id
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':numero', (int) ($data['numero'] ?? 0), PDO::PARAM_INT);
        $stmt->bindValue(':estatus', (string) ($data['estatus'] ?? ''), PDO::PARAM_STR);
        $stmt->bindValue(':abierto_en', (string) ($data['abierto_en'] ?? ''), PDO::PARAM_STR);

        if (array_key_exists('cerrado_en', $data) && $data['cerrado_en'] !== null && $data['cerrado_en'] !== '') {
            $stmt->bindValue(':cerrado_en', (string) $data['cerrado_en'], PDO::PARAM_STR);
        } else {
            $stmt->bindValue(':cerrado_en', null, PDO::PARAM_NULL);
        }

        $stmt->execute();
    }
}
