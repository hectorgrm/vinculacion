<?php

declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class GestEstudianteModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAll(): array
    {
        $sql = $this->baseSelectQuery() . ' ORDER BY e.creado_en DESC';
        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(string $term): array
    {
        $sql = $this->baseSelectQuery()
            . ' WHERE e.nombre LIKE :term'
            . ' OR e.matricula LIKE :term'
            . ' OR e.carrera LIKE :term'
            . ' ORDER BY e.creado_en DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':term' => '%' . $term . '%']);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function baseSelectQuery(): string
    {
        return 'SELECT
                    e.id,
                    e.nombre,
                    e.matricula,
                    e.carrera,
                    e.semestre,
                    e.email,
                    e.telefono,
                    e.plaza_id,
                    e.dependencia_asignada,
                    e.proyecto,
                    e.periodo_inicio,
                    e.periodo_fin,
                    e.horas_acumuladas,
                    e.horas_requeridas,
                    e.estado_servicio,
                    e.asesor_interno,
                    e.documentos_entregados,
                    e.observaciones,
                    p.nombre AS plaza_nombre
                FROM estudiante e
                LEFT JOIN plaza p ON e.plaza_id = p.id';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $sql = $this->baseSelectQuery() . ' WHERE e.id = :id LIMIT 1';

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result === false ? null : $result;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function updateServicioSocial(int $id, array $data): void
    {
        $sql = 'UPDATE estudiante
                SET nombre = :nombre,
                    matricula = :matricula,
                    carrera = :carrera,
                    semestre = :semestre,
                    plaza_id = :plaza_id,
                    dependencia_asignada = :dependencia_asignada,
                    proyecto = :proyecto,
                    periodo_inicio = :periodo_inicio,
                    periodo_fin = :periodo_fin,
                    horas_acumuladas = :horas_acumuladas,
                    horas_requeridas = :horas_requeridas,
                    estado_servicio = :estado_servicio,
                    asesor_interno = :asesor_interno,
                    documentos_entregados = :documentos_entregados,
                    observaciones = :observaciones,
                    email = :email,
                    telefono = :telefono
                WHERE id = :id';

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->bindValue(':nombre', $data['nombre'], PDO::PARAM_STR);
        $statement->bindValue(':matricula', $data['matricula'], PDO::PARAM_STR);
        $this->bindNullableString($statement, ':carrera', $data['carrera']);
        $this->bindNullableInt($statement, ':semestre', $data['semestre']);
        $statement->bindValue(':plaza_id', $data['plaza_id'], $data['plaza_id'] === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $this->bindNullableString($statement, ':dependencia_asignada', $data['dependencia_asignada']);
        $this->bindNullableString($statement, ':proyecto', $data['proyecto']);
        $statement->bindValue(':periodo_inicio', $data['periodo_inicio'], $data['periodo_inicio'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':periodo_fin', $data['periodo_fin'], $data['periodo_fin'] === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $statement->bindValue(':horas_acumuladas', $data['horas_acumuladas'], PDO::PARAM_INT);
        $statement->bindValue(':horas_requeridas', $data['horas_requeridas'], PDO::PARAM_INT);
        $statement->bindValue(':estado_servicio', $data['estado_servicio'], PDO::PARAM_STR);
        $this->bindNullableString($statement, ':asesor_interno', $data['asesor_interno']);
        $this->bindNullableString($statement, ':documentos_entregados', $data['documentos_entregados']);
        $this->bindNullableString($statement, ':observaciones', $data['observaciones']);
        $this->bindNullableString($statement, ':email', $data['email']);
        $this->bindNullableString($statement, ':telefono', $data['telefono']);

        $statement->execute();
    }

    private function bindNullableString(\PDOStatement $statement, string $parameter, ?string $value): void
    {
        if ($value === null) {
            $statement->bindValue($parameter, null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue($parameter, $value, PDO::PARAM_STR);
        }
    }

    private function bindNullableInt(\PDOStatement $statement, string $parameter, ?int $value): void
    {
        if ($value === null) {
            $statement->bindValue($parameter, null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue($parameter, $value, PDO::PARAM_INT);
        }
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchPlazas(): array
    {
        $sql = 'SELECT id, nombre FROM plaza ORDER BY nombre ASC';

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
