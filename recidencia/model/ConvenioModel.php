<?php

declare(strict_types=1);

namespace Residencia\Model;

use PDO;

class ConvenioModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function fetchById(int $id): ?array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control,
                   c.folio,
                   c.estatus,
                   c.tipo_convenio,
                   c.responsable_academico,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.creado_en,
                   c.actualizado_en,
                   c.observaciones,
                   c.borrador_path,
                   c.firmado_path
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
             WHERE c.id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null, ?string $estatus = null): array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   e.nombre AS empresa_nombre,
                   e.numero_control AS empresa_numero_control,
                   c.folio,
                   c.estatus,
                   c.tipo_convenio,
                   c.responsable_academico,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.creado_en,
                   c.actualizado_en,
                   c.observaciones,
                   c.borrador_path,
                   c.firmado_path
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
        SQL;

        $conditions = [];
        $params = [];

        if ($estatus !== null && $estatus !== '') {
            $conditions[] = 'c.estatus = :estatus';
            $params[':estatus'] = $estatus;
        }

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                e.nombre LIKE :search
                OR e.numero_control LIKE :search
                OR c.folio LIKE :search
                OR c.responsable_academico LIKE :search
                OR c.tipo_convenio LIKE :search
            )';
            $params[':search'] = '%' . $search . '%';
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY c.actualizado_en DESC, c.id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchEmpresasForSelect(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   numero_control,
                   estatus
              FROM rp_empresa
             WHERE estatus IN ('Activa', 'En revisión')
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function insert(array $data): int
    {
        $sql = <<<'SQL'
            INSERT INTO rp_convenio (
                empresa_id,
                tipo_convenio,
                estatus,
                observaciones,
                fecha_inicio,
                fecha_fin,
                responsable_academico,
                folio,
                borrador_path,
                firmado_path
            ) VALUES (
                :empresa_id,
                :tipo_convenio,
                :estatus,
                :observaciones,
                :fecha_inicio,
                :fecha_fin,
                :responsable_academico,
                :folio,
                :borrador_path,
                :firmado_path
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':empresa_id' => isset($data['empresa_id']) ? (int) $data['empresa_id'] : 0,
            ':tipo_convenio' => $data['tipo_convenio'] ?? null,
            ':estatus' => $data['estatus'] ?? 'En revisión',
            ':observaciones' => $data['observaciones'] ?? null,
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
            ':responsable_academico' => $data['responsable_academico'] ?? null,
            ':folio' => $data['folio'] ?? null,
            ':borrador_path' => $data['borrador_path'] ?? null,
            ':firmado_path' => $data['firmado_path'] ?? null,
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): bool
    {
        $sql = <<<'SQL'
            UPDATE rp_convenio
               SET empresa_id = :empresa_id,
                   tipo_convenio = :tipo_convenio,
                   estatus = :estatus,
                   observaciones = :observaciones,
                   fecha_inicio = :fecha_inicio,
                   fecha_fin = :fecha_fin,
                   responsable_academico = :responsable_academico,
                   folio = :folio,
                   borrador_path = :borrador_path,
                   firmado_path = :firmado_path
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([
            ':empresa_id' => isset($data['empresa_id']) ? (int) $data['empresa_id'] : 0,
            ':tipo_convenio' => $data['tipo_convenio'] ?? null,
            ':estatus' => $data['estatus'] ?? 'En revisión',
            ':observaciones' => $data['observaciones'] ?? null,
            ':fecha_inicio' => $data['fecha_inicio'] ?? null,
            ':fecha_fin' => $data['fecha_fin'] ?? null,
            ':responsable_academico' => $data['responsable_academico'] ?? null,
            ':folio' => $data['folio'] ?? null,
            ':borrador_path' => $data['borrador_path'] ?? null,
            ':firmado_path' => $data['firmado_path'] ?? null,
            ':id' => $id,
        ]);
    }

    public function deactivate(int $id, ?string $motivo = null): bool
    {
        $motivo = $motivo !== null ? trim($motivo) : null;

        if ($motivo === '') {
            $motivo = null;
        }

        if ($motivo !== null) {
            $nota = sprintf('[%s] Motivo de desactivación: %s', date('Y-m-d H:i:s'), $motivo);

            $sql = <<<'SQL'
                UPDATE rp_convenio
                   SET estatus = 'Inactiva',
                       observaciones = CASE
                           WHEN observaciones IS NULL OR observaciones = '' THEN :observaciones
                           ELSE CONCAT(observaciones, '\n\n', :observaciones)
                       END,
                       actualizado_en = NOW()
                 WHERE id = :id
            SQL;

            $statement = $this->pdo->prepare($sql);

            return $statement->execute([
                ':id' => $id,
                ':observaciones' => $nota,
            ]);
        }

        $sql = <<<'SQL'
            UPDATE rp_convenio
               SET estatus = 'Inactiva',
                   actualizado_en = NOW()
             WHERE id = :id
        SQL;

        $statement = $this->pdo->prepare($sql);

        return $statement->execute([':id' => $id]);
    }
}
