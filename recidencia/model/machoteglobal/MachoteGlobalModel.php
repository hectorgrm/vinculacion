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
     * Obtiene el machote institucional más reciente.
     */
    public function getLatest(): ?array
    {
        try {
            $vigenteSql = "SELECT * FROM rp_machote WHERE estado = 'vigente' ORDER BY id DESC LIMIT 1";
            $statement = $this->connection->query($vigenteSql);
            $result = $statement !== false ? $statement->fetch(PDO::FETCH_ASSOC) : false;

            if ($result === false) {
                $fallbackSql = 'SELECT * FROM rp_machote ORDER BY id DESC LIMIT 1';
                $statement = $this->connection->query($fallbackSql);
                $result = $statement !== false ? $statement->fetch(PDO::FETCH_ASSOC) : false;
            }

            return $result !== false ? $result : null;
        } catch (PDOException) {
            return null;
        }
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
            $stmt->execute([':id' => $id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function createWithDefaultConnection(): self
    {
        require_once __DIR__ . '/../../common/model/db.php';

        return new self(\Common\Model\Database::getConnection());
    }

    /**
     * Inserta un nuevo machote institucional.
     *
     * @param array<string, mixed> $data
     */
    public function insertMachote(array $data): bool
    {
        try {
            $sql = "INSERT INTO rp_machote (version, descripcion, estado, contenido_html)
                    VALUES (:version, :descripcion, :estado, :contenido_html)";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                'version' => $data['version'],
                'descripcion' => $data['descripcion'] !== '' ? $data['descripcion'] : null,
                'estado' => $data['estado'],
                'contenido_html' => $data['contenido_html'] !== '' ? $data['contenido_html'] : null,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Actualiza un machote existente.
     *
     * @param array<string, mixed> $data
     */
    public function updateMachote(array $data): bool
    {
        try {
            $sql = "UPDATE rp_machote
                    SET version = :version,
                        descripcion = :descripcion,
                        estado = :estado,
                        contenido_html = :contenido_html
                    WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute([
                'id' => $data['id'],
                'version' => $data['version'],
                'descripcion' => $data['descripcion'] !== '' ? $data['descripcion'] : null,
                'estado' => $data['estado'],
                'contenido_html' => $data['contenido_html'] !== '' ? $data['contenido_html'] : null,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Marca un machote como archivado.
     */
    public function deleteMachote(int $id): bool
    {
        try {
            $sql = "UPDATE rp_machote
                    SET estado = 'archivado'
                    WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
