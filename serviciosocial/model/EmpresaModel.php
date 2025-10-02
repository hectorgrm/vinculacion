<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class EmpresaModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   direccion,
                   estado,
                   creado_en
            FROM ss_empresa
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE (nombre LIKE :search OR contacto_nombre LIKE :search OR contacto_email LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY nombre ASC';

        if ($params === []) {
            $statement = $this->pdo->query($sql);
        } else {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   direccion,
                   estado,
                   creado_en
            FROM ss_empresa
            WHERE id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $sql = <<<'SQL'
            UPDATE ss_empresa
               SET nombre = :nombre,
                   contacto_nombre = :contacto_nombre,
                   contacto_email = :contacto_email,
                   telefono = :telefono,
                   direccion = :direccion,
                   estado = :estado
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':nombre', (string) $data['nombre'], PDO::PARAM_STR);

        $contactName = $data['contacto_nombre'] ?? null;
        if ($contactName === null || $contactName === '') {
            $statement->bindValue(':contacto_nombre', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':contacto_nombre', (string) $contactName, PDO::PARAM_STR);
        }

        $contactEmail = $data['contacto_email'] ?? null;
        if ($contactEmail === null || $contactEmail === '') {
            $statement->bindValue(':contacto_email', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':contacto_email', (string) $contactEmail, PDO::PARAM_STR);
        }

        $telefono = $data['telefono'] ?? null;
        if ($telefono === null || $telefono === '') {
            $statement->bindValue(':telefono', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':telefono', (string) $telefono, PDO::PARAM_STR);
        }

        $direccion = $data['direccion'] ?? null;
        if ($direccion === null || $direccion === '') {
            $statement->bindValue(':direccion', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':direccion', (string) $direccion, PDO::PARAM_STR);
        }

        $statement->bindValue(':estado', (string) $data['estado'], PDO::PARAM_STR);

        $statement->execute();
    }
}
