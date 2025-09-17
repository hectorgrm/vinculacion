<?php

declare(strict_types=1);

namespace Common\Model;

use PDO;
use PDOException;

final class UserModel
{
    public function __construct(private PDO $connection)
    {
    }

    public function findActiveByEmail(string $email): ?array
    {
        $query = 'SELECT id, nombre, email, password_hash, rol, activo FROM usuario WHERE email = :email LIMIT 1';

        $statement = $this->connection->prepare($query);
        if ($statement === false) {
            throw new PDOException('Unable to prepare the user lookup statement.');
        }

        $statement->execute([':email' => $email]);
        $user = $statement->fetch();

        if (!is_array($user) || (int)$user['activo'] !== 1) {
            return null;
        }

        return $user;
    }
}
