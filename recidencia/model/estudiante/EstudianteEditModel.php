<?php

declare(strict_types=1);

namespace Residencia\Model\Estudiante;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_edit_helper.php';

use Common\Model\Database;
use PDO;
use function estudianteEditPrepareForPersistence;

class EstudianteEditModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getEmpresas(): array
    {
        $sql = <<<'SQL'
            SELECT id, nombre
            FROM rp_empresa
            ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        if ($statement === false) {
            return [];
        }

        $empresas = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!is_array($row)) {
                continue;
            }

            $empresas[] = [
                'id' => (string) $row['id'],
                'nombre' => (string) $row['nombre'],
            ];
        }

        return $empresas;
    }

    /**
     * @return array<int, array<string, string>>
     */
    public function getConvenios(): array
    {
        $sql = <<<'SQL'
            SELECT
                c.id,
                c.folio,
                c.empresa_id,
                e.nombre AS empresa_nombre
            FROM rp_convenio AS c
            INNER JOIN rp_empresa AS e ON e.id = c.empresa_id
            ORDER BY c.id DESC
        SQL;

        $statement = $this->pdo->query($sql);

        if ($statement === false) {
            return [];
        }

        $convenios = [];

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!is_array($row)) {
                continue;
            }

            $convenios[] = [
                'id' => (string) $row['id'],
                'folio' => isset($row['folio']) ? (string) $row['folio'] : '',
                'empresa_id' => (string) $row['empresa_id'],
                'empresa_nombre' => isset($row['empresa_nombre']) ? (string) $row['empresa_nombre'] : '',
            ];
        }

        return $convenios;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $estudianteId): ?array
    {
        $sql = <<<'SQL'
            SELECT
                id,
                nombre,
                apellido_paterno,
                apellido_materno,
                matricula,
                carrera,
                correo_institucional,
                telefono,
                empresa_id,
                convenio_id,
                estatus
            FROM rp_estudiante
            WHERE id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $estudianteId, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    /**
     * @param array<string, string> $data
     */
    public function update(int $estudianteId, array $data): void
    {
        $payload = estudianteEditPrepareForPersistence($data);

        $sql = <<<'SQL'
            UPDATE rp_estudiante
            SET
                nombre = :nombre,
                apellido_paterno = :apellido_paterno,
                apellido_materno = :apellido_materno,
                matricula = :matricula,
                carrera = :carrera,
                correo_institucional = :correo_institucional,
                telefono = :telefono,
                empresa_id = :empresa_id,
                convenio_id = :convenio_id,
                estatus = :estatus
            WHERE id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $payload['nombre'],
            ':apellido_paterno' => $payload['apellido_paterno'],
            ':apellido_materno' => $payload['apellido_materno'],
            ':matricula' => $payload['matricula'],
            ':carrera' => $payload['carrera'],
            ':correo_institucional' => $payload['correo_institucional'],
            ':telefono' => $payload['telefono'],
            ':empresa_id' => $payload['empresa_id'],
            ':convenio_id' => $payload['convenio_id'],
            ':estatus' => $payload['estatus'],
            ':id' => $estudianteId,
        ]);
    }
}
