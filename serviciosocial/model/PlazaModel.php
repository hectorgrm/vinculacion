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
        $sql = 'SELECT p.id, p.nombre, p.direccion, p.cupo, p.activa, p.empresa_id, e.nombre AS empresa_nombre
                FROM plaza AS p
                LEFT JOIN empresa AS e ON e.id = p.empresa_id
                ORDER BY p.nombre ASC';

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
