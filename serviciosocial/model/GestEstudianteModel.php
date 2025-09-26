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
                    e.dependencia_asignada,
                    e.periodo_inicio,
                    e.periodo_fin,
                    e.horas_acumuladas,
                    e.horas_requeridas,
                    e.estado_servicio,
                    p.nombre AS plaza_nombre
                FROM estudiante e
                LEFT JOIN plaza p ON e.plaza_id = p.id';
    }
}
