<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class UserModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** Crear un nuevo usuario */
    public function create(array $data): int
    {
        $sql = "INSERT INTO usuario (nombre, email, password_hash, rol, estudiante_id) 
                VALUES (:nombre, :email, :password_hash, :rol, :estudiante_id)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nombre'        => $data['nombre'],
            ':email'         => $data['email'],
            ':password_hash' => $data['password_hash'],
            ':rol'           => $data['rol'],
            ':estudiante_id' => $data['estudiante_id']
        ]);
        return (int)$this->pdo->lastInsertId();
    }

    /** Buscar usuario activo por email */
    public function findActiveByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE email = :email AND activo = 1 LIMIT 1");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /** Actualizar datos básicos del usuario vinculado al estudiante */
    public function updateByEstudianteId(int $estudianteId, array $data): bool
    {
        $fields = [
            'nombre = :nombre',
            'email = :email',
        ];

        $params = [
            ':estudiante_id' => $estudianteId,
            ':nombre'        => $data['nombre'] ?? '',
            ':email'         => $data['email'] ?? '',
        ];

        if (isset($data['password_hash']) && $data['password_hash'] !== '') {
            $fields[] = 'password_hash = :password_hash';
            $params[':password_hash'] = $data['password_hash'];
        }

        $sql = 'UPDATE usuario SET ' . implode(', ', $fields) . ' WHERE estudiante_id = :estudiante_id';

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($params);
    }

    /** Eliminar usuarios asociados a un estudiante */
    public function deleteByEstudianteId(int $estudianteId): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM usuario WHERE estudiante_id = :estudiante_id');

        return $stmt->execute([':estudiante_id' => $estudianteId]);
    }
}
