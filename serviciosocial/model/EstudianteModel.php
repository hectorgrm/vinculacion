<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class EstudianteModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Obtener todos los estudiantes */
    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM ss_estudiante ORDER BY creado_en DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /** Crear un nuevo estudiante y devolver su ID */
    public function create(array $data): int
    {
        $sql = "INSERT INTO ss_estudiante (nombre, matricula, carrera, email, telefono)
                VALUES (:nombre, :matricula, :carrera, :email, :telefono)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre'    => $data['nombre'],
            ':matricula' => $data['matricula'],
            ':carrera'   => $data['carrera'] ?? null,
            ':email'     => $data['email'],
            ':telefono'  => $data['telefono'] ?? null
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /** Buscar estudiante por ID */
    public function findById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM ss_estudiante WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Actualizar la información de un estudiante */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE ss_estudiante
                SET nombre = :nombre,
                    matricula = :matricula,
                    carrera = :carrera,
                    email = :email,
                    telefono = :telefono
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id'        => $id,
            ':nombre'    => $data['nombre'],
            ':matricula' => $data['matricula'],
            ':carrera'   => $data['carrera'] !== '' ? $data['carrera'] : null,
            ':email'     => $data['email'],
            ':telefono'  => $data['telefono'] !== '' ? $data['telefono'] : null,
        ]);
    }

    /** Eliminar un estudiante por su ID */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM ss_estudiante WHERE id = :id');

        return $stmt->execute([':id' => $id]);
    }
}
