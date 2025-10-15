<?php

declare(strict_types=1);

namespace Residencia\Model;

use PDO;

class EmpresaModel
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   rfc,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   estatus,
                   creado_en
              FROM rp_empresa
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE (nombre LIKE :search'
                 . ' OR rfc LIKE :search'
                 . ' OR contacto_nombre LIKE :search'
                 . ' OR contacto_email LIKE :search'
                 . ' OR estatus LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY nombre ASC';

        if ($params === []) {
            $statement = $this->pdo->query($sql);
        } else {
            $statement = $this->pdo->prepare($sql);
            $statement->execute($params);
        }

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
