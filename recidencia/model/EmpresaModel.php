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
                   numero_control,
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
            $sql .= ' WHERE (numero_control LIKE :search'
                 . ' OR nombre LIKE :search'
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
                numero_control,
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
                :numero_control,
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
            ':numero_control' => array_key_exists('numero_control', $data) ? $data['numero_control'] : null,
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
                   numero_control,
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
               SET numero_control = :numero_control,
                   nombre = :nombre,
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
            ':numero_control' => array_key_exists('numero_control', $data) ? $data['numero_control'] : null,
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

    public function disableWithCascade(int $empresaId, ?int $byUserId = null, ?string $reason = null): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlEmpresa = <<<'SQL'
                UPDATE rp_empresa
                   SET estatus = 'Inactiva',
                       actualizado_en = NOW()
                 WHERE id = :id
            SQL;
            $this->pdo->prepare($sqlEmpresa)->execute([':id' => $empresaId]);

            $sqlConvenios = <<<'SQL'
                UPDATE rp_convenio
                   SET estatus = CASE
                       WHEN estatus IN ('pendiente', 'en_revision', 'vigente') THEN 'vencido'
                       ELSE estatus
                   END
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlConvenios)->execute([':id' => $empresaId]);

            $sqlPortal = <<<'SQL'
                UPDATE rp_portal_acceso
                   SET activo = 0
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlPortal)->execute([':id' => $empresaId]);

            $sqlMachoteRevision = <<<'SQL'
                UPDATE rp_machote_revision
                   SET estado = 'cancelado'
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlMachoteRevision)->execute([':id' => $empresaId]);

            $sqlAsignaciones = <<<'SQL'
                UPDATE rp_asignacion
                   SET estatus = 'cancelado'
                 WHERE empresa_id = :id
                   AND estatus != 'concluido'
            SQL;
            $this->pdo->prepare($sqlAsignaciones)->execute([':id' => $empresaId]);

            $this->pdo->commit();

            return true;
        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }

    public function reactivateWithCascade(int $empresaId, ?int $byUserId = null, ?string $reason = null): bool
    {
        $this->pdo->beginTransaction();

        try {
            $sqlEmpresa = <<<'SQL'
                UPDATE rp_empresa
                   SET estatus = 'En revisión',
                       actualizado_en = NOW()
                 WHERE id = :id
            SQL;
            $this->pdo->prepare($sqlEmpresa)->execute([':id' => $empresaId]);

            $sqlConvenios = <<<'SQL'
                UPDATE rp_convenio
                   SET estatus = CASE
                       WHEN estatus = 'vencido' THEN 'en_revision'
                       ELSE estatus
                   END
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlConvenios)->execute([':id' => $empresaId]);

            $sqlPortal = <<<'SQL'
                UPDATE rp_portal_acceso
                   SET activo = 1
                 WHERE empresa_id = :id
            SQL;
            $this->pdo->prepare($sqlPortal)->execute([':id' => $empresaId]);

            $sqlMachoteRevision = <<<'SQL'
                UPDATE rp_machote_revision
                   SET estado = 'en_revision'
                 WHERE empresa_id = :id
                   AND estado = 'cancelado'
            SQL;
            $this->pdo->prepare($sqlMachoteRevision)->execute([':id' => $empresaId]);

            $sqlAsignaciones = <<<'SQL'
                UPDATE rp_asignacion
                   SET estatus = 'en_curso'
                 WHERE empresa_id = :id
                   AND estatus = 'cancelado'
            SQL;
            $this->pdo->prepare($sqlAsignaciones)->execute([':id' => $empresaId]);

            $this->pdo->commit();

            return true;
        } catch (\PDOException $exception) {
            $this->pdo->rollBack();
            throw $exception;
        }
    }
}
