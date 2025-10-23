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

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null, ?string $estatus = null): array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   e.nombre AS empresa_nombre,
                   c.folio,
                   c.estatus,
                   c.machote_version,
                   c.version_actual,
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
                OR c.folio LIKE :search
                OR c.version_actual LIKE :search
                OR c.machote_version LIKE :search
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
}
