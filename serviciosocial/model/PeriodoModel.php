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
        $sql = 'SELECT id, servicio_id, numero, estatus, abierto_en, cerrado_en FROM periodo ORDER BY abierto_en DESC, id DESC';
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
            FROM periodo
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
        $stmt = $this->pdo->prepare('SELECT id, servicio_id, numero, estatus, abierto_en, cerrado_en FROM periodo WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }
}
