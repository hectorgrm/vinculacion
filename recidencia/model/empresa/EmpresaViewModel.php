<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaViewModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $empresaId): ?array
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
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findActiveConveniosByEmpresaId(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   empresa_id,
                   machote_version,
                   estatus,
                   observaciones,
                   fecha_inicio,
                   fecha_fin,
                   version_actual,
                   folio,
                   borrador_path,
                   firmado_path,
                   creado_en,
                   actualizado_en
              FROM rp_convenio
             WHERE empresa_id = :empresa_id
               AND estatus = 'Activa'
             ORDER BY fecha_fin DESC,
                      fecha_inicio DESC,
                      id DESC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }
}
