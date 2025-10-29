<?php

declare(strict_types=1);

namespace Residencia\Model\DocumentoTipo;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DocumentoTipoListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTipos(?string $search = null, ?string $tipoEmpresa = null): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   descripcion,
                   obligatorio,
                   tipo_empresa,
                   activo
              FROM rp_documento_tipo
        SQL;

        $conditions = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $conditions[] = '(nombre LIKE :search OR descripcion LIKE :search)';
            $params[':search'] = '%' . $search . '%';
        }

        if ($tipoEmpresa !== null && $tipoEmpresa !== '') {
            $conditions[] = 'tipo_empresa = :tipo_empresa';
            $params[':tipo_empresa'] = $tipoEmpresa;
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY nombre ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}
