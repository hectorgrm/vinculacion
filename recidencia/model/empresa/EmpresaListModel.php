<?php

declare(strict_types=1);

namespace Residencia\Model\Empresa;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchAll(?string $search = null): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   numero_control,
                   nombre,
                   rfc,
                   representante,
                   contacto_nombre,
                   contacto_email,
                   telefono,
                   estatus,
                   creado_en
              FROM rp_empresa
        SQL;

        $params = [];

        if ($search !== null && $search !== '') {
            $sql .= ' WHERE (numero_control LIKE :search'
                 . ' OR nombre LIKE :search'
                 . ' OR rfc LIKE :search'
                 . ' OR representante LIKE :search'
                 . ' OR contacto_nombre LIKE :search'
                 . ' OR contacto_email LIKE :search'
                 . ' OR telefono LIKE :search'
                 . ' OR estatus LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        $sql .= ' ORDER BY nombre ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
