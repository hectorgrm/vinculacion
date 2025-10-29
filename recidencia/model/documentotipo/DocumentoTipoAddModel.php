<?php

declare(strict_types=1);

namespace Residencia\Model\DocumentoTipo;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_add.php';

use Common\Model\Database;
use PDO;
use function documentoTipoAddPrepareForPersistence;

class DocumentoTipoAddModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @param array<string, string> $data
     */
    public function create(array $data): int
    {
        $payload = documentoTipoAddPrepareForPersistence($data);

        $sql = <<<'SQL'
            INSERT INTO rp_documento_tipo (
                nombre,
                descripcion,
                obligatorio,
                tipo_empresa
            ) VALUES (
                :nombre,
                :descripcion,
                :obligatorio,
                :tipo_empresa
            )
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([
            ':nombre' => $payload['nombre'],
            ':descripcion' => $payload['descripcion'],
            ':obligatorio' => $payload['obligatorio'],
            ':tipo_empresa' => $payload['tipo_empresa'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}

