<?php

declare(strict_types=1);

namespace PortalEmpresa\Model;

use Common\Model\Database;
use PDO;

require_once __DIR__ . '/../../common/model/db.php';

class EmpresaConvenioViewModel
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        if ($connection instanceof PDO) {
            $this->connection = $connection;
            return;
        }

        $this->connection = Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresaById(int $empresaId): ?array
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
             WHERE id = :empresa_id
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findLatestConvenioByEmpresaId(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   empresa_id,
                   tipo_convenio,
                   estatus,
                   observaciones,
                   fecha_inicio,
                   fecha_fin,
                   responsable_academico,
                   creado_en,
                   folio,
                   borrador_path,
                   firmado_path,
                   actualizado_en
              FROM rp_convenio
             WHERE empresa_id = :empresa_id
             ORDER BY actualizado_en DESC, creado_en DESC, id DESC
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findConveniosByEmpresaId(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   c.tipo_convenio,
                   c.estatus,
                   c.observaciones,
                   c.fecha_inicio,
                   c.fecha_fin,
                   c.responsable_academico,
                   c.creado_en,
                   c.folio,
                   c.borrador_path,
                   c.firmado_path,
                   c.actualizado_en,
                   c.renovado_de
              FROM rp_convenio AS c
             WHERE c.empresa_id = :empresa_id
             ORDER BY c.actualizado_en DESC, c.creado_en DESC, c.id DESC
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':empresa_id' => $empresaId,
        ]);

        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return is_array($records) ? $records : [];
    }
}
