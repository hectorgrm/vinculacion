<?php

declare(strict_types=1);

namespace Residencia\Model\Estudiante;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use InvalidArgumentException;
use PDO;

class EstudianteDeactivateModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findByIdWithRelations(int $estudianteId): ?array
    {
        $sql = <<<'SQL'
            SELECT
                e.id AS estudiante_id,
                e.nombre AS estudiante_nombre,
                e.apellido_paterno AS estudiante_apellido_paterno,
                e.apellido_materno AS estudiante_apellido_materno,
                e.matricula AS estudiante_matricula,
                e.carrera AS estudiante_carrera,
                e.estatus AS estudiante_estatus,
                emp.id AS empresa_id,
                emp.nombre AS empresa_nombre,
                c.id AS convenio_id,
                c.folio AS convenio_folio,
                c.estatus AS convenio_estatus
            FROM rp_estudiante AS e
            INNER JOIN rp_empresa AS emp ON emp.id = e.empresa_id
            LEFT JOIN rp_convenio AS c ON c.id = e.convenio_id
            WHERE e.id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $estudianteId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    public function updateStatus(int $estudianteId, string $estatus): bool
    {
        $allowed = ['Activo', 'Finalizado', 'Inactivo'];

        if (!in_array($estatus, $allowed, true)) {
            throw new InvalidArgumentException('El estatus proporcionado no es válido.');
        }

        $sql = <<<'SQL'
            UPDATE rp_estudiante
            SET estatus = :estatus
            WHERE id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);
        $statement->bindValue(':id', $estudianteId, PDO::PARAM_INT);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            return true;
        }

        $current = $this->findByIdWithRelations($estudianteId);

        if ($current !== null && isset($current['estudiante_estatus']) && $current['estudiante_estatus'] === $estatus) {
            return true;
        }

        return false;
    }
}
