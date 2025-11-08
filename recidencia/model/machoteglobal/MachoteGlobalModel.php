<?php

declare(strict_types=1);

namespace Residencia\Model\MachoteGlobal;

use PDO;
use PDOException;

final class MachoteGlobalModel
{
    public function __construct(private PDO $connection)
    {
    }

    /**
     * Obtener todos los machotes institucionales
     * @return array<int, array<string, mixed>>
     */
    public function getAllMachotes(): array
    {
        try {
            $sql = "SELECT id, version, descripcion, estado, creado_en, actualizado_en 
                    FROM rp_machote
                    ORDER BY creado_en DESC";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // En producción podrías registrar el error
            return [];
        }
    }

    /**
     * Obtener un machote por ID
     */
    public function getMachoteById(int $id): ?array
    {
        try {
            $sql = "SELECT * FROM rp_machote WHERE id = :id LIMIT 1";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}
