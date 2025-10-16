<?php

declare(strict_types=1);

namespace Residencia\Model;

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
                   rfc,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   estatus,
                   creado_en
              FROM rp_empresa
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE (nombre LIKE :search'
                 . ' OR rfc LIKE :search'
                 . ' OR contacto_nombre LIKE :search'
                 . ' OR contacto_email LIKE :search'
                 . ' OR estatus LIKE :search)';
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
     * @param array<string, mixed> $data
     */
    public function insert(array $data): int
    {
        $sql = <<<'SQL'
            INSERT INTO rp_empresa (
                nombre,
                rfc,
                contacto_nombre,
                contacto_email,
                telefono,
                estado,
                municipio,
                cp,
                direccion,
                estatus
            ) VALUES (
                :nombre,
                :rfc,
                :contacto_nombre,
                :contacto_email,
                :telefono,
                :estado,
                :municipio,
                :cp,
                :direccion,
                :estatus
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $data['nombre'],
            ':rfc' => $data['rfc'],
            ':contacto_nombre' => $data['contacto_nombre'],
            ':contacto_email' => $data['contacto_email'],
            ':telefono' => $data['telefono'],
            ':estado' => $data['estado'],
            ':municipio' => $data['municipio'],
            ':cp' => $data['cp'],
            ':direccion' => $data['direccion'],
            ':estatus' => $data['estatus'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
