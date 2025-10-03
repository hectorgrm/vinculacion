<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class ServicioModel
{
    private PDO $pdo;
    private ?bool $hasObservacionesColumn = null;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Obtener todos los servicios registrados con información relacionada.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null): array
    {
        $baseQuery = <<<'SQL'
            SELECT s.id,
                   s.estatus,
                   s.horas_acumuladas,
                   s.creado_en,
                   e.nombre AS estudiante_nombre,
                   e.matricula AS estudiante_matricula,
                   p.nombre AS plaza_nombre
            FROM ss_servicio AS s
            INNER JOIN ss_estudiante AS e ON e.id = s.estudiante_id
            LEFT JOIN ss_plaza AS p ON p.id = s.plaza_id
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $baseQuery .= ' WHERE (e.nombre LIKE :search OR e.matricula LIKE :search OR p.nombre LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $baseQuery .= ' ORDER BY s.creado_en DESC, s.id DESC';

        if ($params === []) {
            $statement = $this->pdo->query($baseQuery);
        } else {
            $statement = $this->pdo->prepare($baseQuery);
            $statement->execute($params);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener la información detallada de un servicio.
     *
     * @return array<string, mixed>|null
     */
    public function findById(int $id): ?array
    {
        $observacionesSelect = $this->supportsObservacionesColumn() ? ', s.observaciones' : '';

        $sql = <<<SQL
            SELECT s.id,
                   s.estatus,
                   s.horas_acumuladas,
                   s.creado_en,
                   s.estudiante_id,
                   s.plaza_id{$observacionesSelect},
                   e.nombre         AS estudiante_nombre,
                   e.matricula      AS estudiante_matricula,
                   e.carrera        AS estudiante_carrera,
                   e.email          AS estudiante_email,
                   e.telefono       AS estudiante_telefono,
                   e.horas_requeridas AS estudiante_horas_requeridas,
                   p.nombre         AS plaza_nombre,
                   p.modalidad      AS plaza_modalidad,
                   p.cupo           AS plaza_cupo,
                   p.periodo_inicio AS plaza_periodo_inicio,
                   p.periodo_fin    AS plaza_periodo_fin,
                   emp.nombre       AS plaza_empresa
            FROM ss_servicio AS s
            INNER JOIN ss_estudiante AS e ON e.id = s.estudiante_id
            LEFT JOIN ss_plaza AS p ON p.id = s.plaza_id
            LEFT JOIN ss_empresa AS emp ON emp.id = p.ss_empresa_id
            WHERE s.id = :id
            LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $id]);

        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $servicio = [
            'id'               => (int) $row['id'],
            'estatus'          => $row['estatus'],
            'horas_acumuladas' => $row['horas_acumuladas'] !== null ? (int) $row['horas_acumuladas'] : null,
            'creado_en'        => $row['creado_en'],
            'observaciones'    => $this->supportsObservacionesColumn() ? ($row['observaciones'] ?? null) : null,
            'estudiante'       => [
                'id'               => (int) $row['estudiante_id'],
                'nombre'           => $row['estudiante_nombre'] ?? '',
                'matricula'        => $row['estudiante_matricula'] ?? '',
                'carrera'          => $row['estudiante_carrera'] ?? null,
                'email'            => $row['estudiante_email'] ?? null,
                'telefono'         => $row['estudiante_telefono'] ?? null,
                'horas_requeridas' => $row['estudiante_horas_requeridas'] !== null
                    ? (int) $row['estudiante_horas_requeridas']
                    : null,
            ],
            'plaza'            => null,
        ];

        if ($row['plaza_id'] !== null) {
            $servicio['plaza'] = [
                'id'            => (int) $row['plaza_id'],
                'nombre'        => $row['plaza_nombre'] ?? null,
                'empresa'       => $row['plaza_empresa'] ?? null,
                'modalidad'     => $row['plaza_modalidad'] ?? null,
                'cupo'          => $row['plaza_cupo'] !== null ? (int) $row['plaza_cupo'] : null,
                'periodo_inicio'=> $row['plaza_periodo_inicio'] ?? null,
                'periodo_fin'   => $row['plaza_periodo_fin'] ?? null,
            ];
        }

        $servicio['periodos'] = $this->fetchPeriodosByServicio($servicio['id']);

        return $servicio;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchPlazasCatalog(): array
    {
        $sql = <<<'SQL'
            SELECT p.id,
                   p.nombre,
                   emp.nombre AS empresa
            FROM ss_plaza AS p
            LEFT JOIN ss_empresa AS emp ON emp.id = p.ss_empresa_id
            ORDER BY p.nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): void
    {
        $supportsObservaciones = $this->supportsObservacionesColumn();

        $setClauses = [
            'plaza_id = :plaza_id',
            'estatus = :estatus',
            'horas_acumuladas = :horas_acumuladas',
        ];

        if ($supportsObservaciones) {
            $setClauses[] = 'observaciones = :observaciones';
        }

        $sql = sprintf(
            'UPDATE ss_servicio SET %s WHERE id = :id',
            implode(', ', $setClauses)
        );

        $statement = $this->pdo->prepare($sql);

        $plazaId = $data['plaza_id'] ?? null;
        if ($plazaId === null) {
            $statement->bindValue(':plaza_id', null, PDO::PARAM_NULL);
        } else {
            $statement->bindValue(':plaza_id', $plazaId, PDO::PARAM_INT);
        }

        $estatus = $data['estatus'] ?? 'prealta';
        $horasAcumuladas = $data['horas_acumuladas'] ?? 0;

        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);
        $statement->bindValue(':horas_acumuladas', $horasAcumuladas, PDO::PARAM_INT);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        if ($supportsObservaciones) {
            $observaciones = $data['observaciones'] ?? null;
            if ($observaciones === null) {
                $statement->bindValue(':observaciones', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':observaciones', $observaciones, PDO::PARAM_STR);
            }
        }

        $statement->execute();
    }

    /**
     * Cerrar un servicio actualizando su estatus y observaciones.
     */
    public function close(int $id, string $estatus, ?string $observaciones = null): void
    {
        $supportsObservaciones = $this->supportsObservacionesColumn();

        $setClauses = ['estatus = :estatus'];

        if ($supportsObservaciones) {
            $setClauses[] = 'observaciones = :observaciones';
        }

        $sql = sprintf(
            'UPDATE ss_servicio SET %s WHERE id = :id',
            implode(', ', $setClauses)
        );

        $statement = $this->pdo->prepare($sql);

        $statement->bindValue(':estatus', $estatus, PDO::PARAM_STR);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        if ($supportsObservaciones) {
            if ($observaciones === null || $observaciones === '') {
                $statement->bindValue(':observaciones', null, PDO::PARAM_NULL);
            } else {
                $statement->bindValue(':observaciones', $observaciones, PDO::PARAM_STR);
            }
        }

        $statement->execute();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fetchPeriodosByServicio(int $servicioId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   numero,
                   estatus,
                   abierto_en,
                   cerrado_en
            FROM ss_periodo
            WHERE servicio_id = :servicio_id
            ORDER BY numero ASC, id ASC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':servicio_id' => $servicioId]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    private function supportsObservacionesColumn(): bool
    {
        if ($this->hasObservacionesColumn !== null) {
            return $this->hasObservacionesColumn;
        }

        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM INFORMATION_SCHEMA.COLUMNS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'ss_servicio'
              AND COLUMN_NAME = 'observaciones'
        SQL;

        $statement = $this->pdo->query($sql);
        $count = $statement !== false ? (int) $statement->fetchColumn() : 0;

        $this->hasObservacionesColumn = $count > 0;

        return $this->hasObservacionesColumn;
    }
}
