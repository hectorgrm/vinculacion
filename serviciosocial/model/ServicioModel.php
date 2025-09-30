<?php
declare(strict_types=1);

namespace Serviciosocial\Model;

use PDO;

class ServicioModel
{
    private PDO $pdo;

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
            FROM servicio AS s
            INNER JOIN estudiante AS e ON e.id = s.estudiante_id
            LEFT JOIN plaza AS p ON p.id = s.plaza_id
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
}
