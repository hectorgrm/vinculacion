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
                   representante,
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
                 . ' OR representante LIKE :search'
                 . ' OR contacto_nombre LIKE :search'
                 . ' OR contacto_email LIKE :search'
                 . ' OR telefono LIKE :search'
                 . ' OR estatus LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY nombre ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

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
                representante,
                cargo_representante,
                sector,
                sitio_web,
                contacto_nombre,
                contacto_email,
                telefono,
                estado,
                municipio,
                cp,
                direccion,
                estatus,
                regimen_fiscal,
                notas
            ) VALUES (
                :nombre,
                :rfc,
                :representante,
                :cargo_representante,
                :sector,
                :sitio_web,
                :contacto_nombre,
                :contacto_email,
                :telefono,
                :estado,
                :municipio,
                :cp,
                :direccion,
                :estatus,
                :regimen_fiscal,
                :notas
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $data['nombre'],
            ':rfc' => $data['rfc'],
            ':representante' => $data['representante'],
            ':cargo_representante' => $data['cargo_representante'],
            ':sector' => $data['sector'],
            ':sitio_web' => $data['sitio_web'],
            ':contacto_nombre' => $data['contacto_nombre'],
            ':contacto_email' => $data['contacto_email'],
            ':telefono' => $data['telefono'],
            ':estado' => $data['estado'],
            ':municipio' => $data['municipio'],
            ':cp' => $data['cp'],
            ':direccion' => $data['direccion'],
            ':estatus' => $data['estatus'],
            ':regimen_fiscal' => $data['regimen_fiscal'],
            ':notas' => $data['notas'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    public function findById(int $id): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   rfc,
                   representante,
                   cargo_representante,
                   sector,
                   sitio_web,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   estado,
                   municipio,
                   cp,
                   direccion,
                   estatus,
                   regimen_fiscal,
                   notas,
                   creado_en,
                   actualizado_en
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $sql = <<<'SQL'
            UPDATE rp_empresa
               SET nombre = :nombre,
                   rfc = :rfc,
                   representante = :representante,
                   cargo_representante = :cargo_representante,
                   sector = :sector,
                   sitio_web = :sitio_web,
                   contacto_nombre = :contacto_nombre,
                   contacto_email = :contacto_email,
                   telefono = :telefono,
                   estado = :estado,
                   municipio = :municipio,
                   cp = :cp,
                   direccion = :direccion,
                   estatus = :estatus,
                   regimen_fiscal = :regimen_fiscal,
                   notas = :notas
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $data['nombre'],
            ':rfc' => $data['rfc'],
            ':representante' => $data['representante'],
            ':cargo_representante' => $data['cargo_representante'],
            ':sector' => $data['sector'],
            ':sitio_web' => $data['sitio_web'],
            ':contacto_nombre' => $data['contacto_nombre'],
            ':contacto_email' => $data['contacto_email'],
            ':telefono' => $data['telefono'],
            ':estado' => $data['estado'],
            ':municipio' => $data['municipio'],
            ':cp' => $data['cp'],
            ':direccion' => $data['direccion'],
            ':estatus' => $data['estatus'],
            ':regimen_fiscal' => $data['regimen_fiscal'],
            ':notas' => $data['notas'],
            ':id' => $id,
        ]);
    }
}
