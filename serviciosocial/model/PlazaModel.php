<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class PlazaModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtener todas las plazas registradas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $sql = 'SELECT p.id,
                       p.nombre,
                       p.direccion,
                       p.cupo,
                       p.estado,
                       p.ss_empresa_id,
                       e.nombre AS empresa_nombre
                FROM ss_plaza AS p
                LEFT JOIN ss_empresa AS e ON e.id = p.ss_empresa_id
                ORDER BY p.creado_en DESC';

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener una plaza específica por su identificador.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $sql = 'SELECT p.*, e.nombre AS empresa_nombre
                FROM ss_plaza AS p
                LEFT JOIN ss_empresa AS e ON e.id = p.ss_empresa_id
                WHERE p.id = :id
                LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * Obtener todas las empresas/dependencias disponibles.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchEmpresas(): array
    {
        $sql = 'SELECT id, nombre FROM ss_empresa ORDER BY nombre ASC';

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener todos los convenios disponibles.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchConvenios(): array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   TRIM(CONCAT_WS(' · ',
                       e.nombre,
                       CONCAT('Convenio #', c.id),
                       CONCAT('Estatus: ', c.estatus),
                       NULLIF(c.version_actual, ''),
                       DATE_FORMAT(c.fecha_inicio, '%d/%m/%Y'),
                       DATE_FORMAT(c.fecha_fin, '%d/%m/%Y')
                   )) AS nombre
            FROM ss_convenio AS c
            INNER JOIN ss_empresa AS e ON e.id = c.ss_empresa_id
            ORDER BY e.nombre ASC, c.id ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crear una nueva plaza en la base de datos.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO ss_plaza (
                    nombre,
                    ss_empresa_id,
                    ss_convenio_id,
                    direccion,
                    cupo,
                    periodo_inicio,
                    periodo_fin,
                    modalidad,
                    actividades,
                    requisitos,
                    responsable_nombre,
                    responsable_puesto,
                    responsable_email,
                    responsable_tel,
                    ubicacion,
                    estado,
                    observaciones
                ) VALUES (
                    :nombre,
                    :ss_empresa_id,
                    :ss_convenio_id,
                    :direccion,
                    :cupo,
                    :periodo_inicio,
                    :periodo_fin,
                    :modalidad,
                    :actividades,
                    :requisitos,
                    :responsable_nombre,
                    :responsable_puesto,
                    :responsable_email,
                    :responsable_tel,
                    :ubicacion,
                    :estado,
                    :observaciones
                )';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            ':nombre'             => $data['nombre'],
            ':ss_empresa_id'      => $data['ss_empresa_id'],
            ':ss_convenio_id'     => $data['ss_convenio_id'],
            ':direccion'          => $data['direccion'],
            ':cupo'               => $data['cupo'],
            ':periodo_inicio'     => $data['periodo_inicio'],
            ':periodo_fin'        => $data['periodo_fin'],
            ':modalidad'          => $data['modalidad'],
            ':actividades'        => $data['actividades'],
            ':requisitos'         => $data['requisitos'],
            ':responsable_nombre' => $data['responsable_nombre'],
            ':responsable_puesto' => $data['responsable_puesto'],
            ':responsable_email'  => $data['responsable_email'],
            ':responsable_tel'    => $data['responsable_tel'],
            ':ubicacion'          => $data['ubicacion'],
            ':estado'             => $data['estado'],
            ':observaciones'      => $data['observaciones'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /**
     * Actualizar la información de una plaza existente.
     *
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $sql = 'UPDATE ss_plaza
                SET nombre = :nombre,
                    ss_empresa_id = :ss_empresa_id,
                    ss_convenio_id = :ss_convenio_id,
                    direccion = :direccion,
                    cupo = :cupo,
                    periodo_inicio = :periodo_inicio,
                    periodo_fin = :periodo_fin,
                    modalidad = :modalidad,
                    actividades = :actividades,
                    requisitos = :requisitos,
                    responsable_nombre = :responsable_nombre,
                    responsable_puesto = :responsable_puesto,
                    responsable_email = :responsable_email,
                    responsable_tel = :responsable_tel,
                    ubicacion = :ubicacion,
                    estado = :estado,
                    observaciones = :observaciones
                WHERE id = :id';

        $statement = $this->pdo->prepare($sql);

        $statement->execute([
            ':id'                 => $id,
            ':nombre'             => $data['nombre'],
            ':ss_empresa_id'      => $data['ss_empresa_id'],
            ':ss_convenio_id'     => $data['ss_convenio_id'],
            ':direccion'          => $data['direccion'],
            ':cupo'               => $data['cupo'],
            ':periodo_inicio'     => $data['periodo_inicio'],
            ':periodo_fin'        => $data['periodo_fin'],
            ':modalidad'          => $data['modalidad'],
            ':actividades'        => $data['actividades'],
            ':requisitos'         => $data['requisitos'],
            ':responsable_nombre' => $data['responsable_nombre'],
            ':responsable_puesto' => $data['responsable_puesto'],
            ':responsable_email'  => $data['responsable_email'],
            ':responsable_tel'    => $data['responsable_tel'],
            ':ubicacion'          => $data['ubicacion'],
            ':estado'             => $data['estado'],
            ':observaciones'      => $data['observaciones'],
        ]);
    }

    /**
     * Eliminar una plaza de la base de datos.
     */
    public function delete(int $id): void
    {
        $sql = 'DELETE FROM ss_plaza WHERE id = :id';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);
    }
}
