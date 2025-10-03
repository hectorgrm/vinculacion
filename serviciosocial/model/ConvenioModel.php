<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class ConvenioModel
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
            SELECT c.id,
                   c.ss_empresa_id,
                   e.nombre AS empresa_nombre,
                   c.estatus,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.version_actual,
                   c.creado_en
              FROM ss_convenio AS c
              INNER JOIN ss_empresa AS e ON e.id = c.ss_empresa_id
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE (e.nombre LIKE :search OR c.estatus LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY c.creado_en DESC, c.id DESC';

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
            SELECT c.id,
                   c.ss_empresa_id,
                   e.nombre AS empresa_nombre,
                   c.estatus,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.version_actual,
                   c.creado_en
              FROM ss_convenio AS c
              INNER JOIN ss_empresa AS e ON e.id = c.ss_empresa_id
             WHERE c.id = :id
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
    public function create(array $data): int
    {
        $sql = <<<'SQL'
            INSERT INTO ss_convenio (
                ss_empresa_id,
                estatus,
                fecha_inicio,
                fecha_fin,
                version_actual
            ) VALUES (
                :ss_empresa_id,
                :estatus,
                :fecha_inicio,
                :fecha_fin,
                :version_actual
            )
        SQL;

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':ss_empresa_id', (int) $data['ss_empresa_id'], PDO::PARAM_INT);
        $statement->bindValue(':estatus', (string) $data['estatus'], PDO::PARAM_STR);

        $fechaInicio = $data['fecha_inicio'] ?? null;
        if ($fechaInicio === null || $fechaInicio === '') {
            $statement->bindValue(':fecha_inicio', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':fecha_inicio', (string) $fechaInicio, PDO::PARAM_STR);
        }

        $fechaFin = $data['fecha_fin'] ?? null;
        if ($fechaFin === null || $fechaFin === '') {
            $statement->bindValue(':fecha_fin', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':fecha_fin', (string) $fechaFin, PDO::PARAM_STR);
        }

        $versionActual = $data['version_actual'] ?? null;
        if ($versionActual === null || $versionActual === '') {
            $statement->bindValue(':version_actual', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':version_actual', (string) $versionActual, PDO::PARAM_STR);
        }

        $statement->execute();

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $sql = <<<'SQL'
            UPDATE ss_convenio
               SET ss_empresa_id = :ss_empresa_id,
                   estatus = :estatus,
                   fecha_inicio = :fecha_inicio,
                   fecha_fin = :fecha_fin,
                   version_actual = :version_actual
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':ss_empresa_id', (int) $data['ss_empresa_id'], PDO::PARAM_INT);
        $statement->bindValue(':estatus', (string) $data['estatus'], PDO::PARAM_STR);

        $fechaInicio = $data['fecha_inicio'] ?? null;
        if ($fechaInicio === null || $fechaInicio === '') {
            $statement->bindValue(':fecha_inicio', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':fecha_inicio', (string) $fechaInicio, PDO::PARAM_STR);
        }

        $fechaFin = $data['fecha_fin'] ?? null;
        if ($fechaFin === null || $fechaFin === '') {
            $statement->bindValue(':fecha_fin', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':fecha_fin', (string) $fechaFin, PDO::PARAM_STR);
        }

        $versionActual = $data['version_actual'] ?? null;
        if ($versionActual === null || $versionActual === '') {
            $statement->bindValue(':version_actual', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':version_actual', (string) $versionActual, PDO::PARAM_STR);
        }

        $statement->execute();
    }

    public function delete(int $id): void
    {
        $sql = 'DELETE FROM ss_convenio WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchEmpresas(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre
              FROM ss_empresa
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
