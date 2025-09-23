<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class PlazaAddModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
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
        $sql = 'INSERT INTO plaza (
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

}
