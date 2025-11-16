<?php

declare(strict_types=1);

namespace PortalEmpresa\Model;

require_once __DIR__ . '/../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EstudianteModel
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        $this->connection = $connection ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function findByEmpresaId(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   apellido_paterno,
                   apellido_materno,
                   matricula,
                   carrera,
                   correo_institucional,
                   telefono,
                   empresa_id,
                   convenio_id,
                   estatus,
                   creado_en
              FROM rp_estudiante
             WHERE empresa_id = :empresa_id
             ORDER BY creado_en DESC, id DESC
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findOneByEmpresa(int $estudianteId, int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   apellido_paterno,
                   apellido_materno,
                   matricula,
                   carrera,
                   correo_institucional,
                   telefono,
                   empresa_id,
                   convenio_id,
                   estatus,
                   creado_en
              FROM rp_estudiante
             WHERE id = :id
               AND empresa_id = :empresa_id
             LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':id' => $estudianteId,
            ':empresa_id' => $empresaId,
        ]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }
}
