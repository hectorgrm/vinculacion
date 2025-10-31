<?php

declare(strict_types=1);

namespace Residencia\Model\Empresadocumentotipo;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class EmpresaDocumentoTipoListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findEmpresaById(int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre,
                   rfc,
                   regimen_fiscal
              FROM rp_empresa
             WHERE id = :id
             LIMIT 1
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':id' => $empresaId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchGlobalDocumentos(int $empresaId, ?string $tipoEmpresa = null): array
    {
        $sql = <<<'SQL'
            SELECT t.id AS tipo_id,
                   t.nombre AS tipo_nombre,
                   t.descripcion AS tipo_descripcion,
                   t.obligatorio AS tipo_obligatorio,
                   t.tipo_empresa,
                   doc.id AS documento_id,
                   doc.ruta AS documento_ruta,
                   doc.estatus AS documento_estatus,
                   doc.observacion AS documento_observacion,
                   doc.creado_en AS documento_creado_en
              FROM rp_documento_tipo AS t
              LEFT JOIN (
                    SELECT ranked.*
                      FROM (
                            SELECT d.*,
                                   ROW_NUMBER() OVER (
                                       PARTITION BY d.tipo_id
                                       ORDER BY d.creado_en DESC, d.id DESC
                                   ) AS rn
                              FROM rp_empresa_doc AS d
                             WHERE d.empresa_id = :empresa_id
                         ) AS ranked
                     WHERE ranked.rn = 1
              ) AS doc ON doc.tipo_id = t.id
             WHERE t.activo = 1
        SQL;

        $params = [
            ':empresa_id' => $empresaId,
        ];

        if ($tipoEmpresa !== null) {
            $sql .= ' AND (t.tipo_empresa = :tipo_empresa OR t.tipo_empresa = \'ambas\')';
            $params[':tipo_empresa'] = $tipoEmpresa;
        }

        $sql .= ' ORDER BY t.obligatorio DESC, t.nombre ASC, t.id ASC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchCustomDocumentos(int $empresaId): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   empresa_id,
                   nombre,
                   descripcion,
                   obligatorio,
                   creado_en
              FROM rp_documento_tipo_empresa
             WHERE empresa_id = :empresa_id
             ORDER BY obligatorio DESC, nombre ASC, id ASC
        SQL;

        $statement = $this->pdo->prepare($sql);
        $statement->execute([':empresa_id' => $empresaId]);

        /** @var array<int, array<string, mixed>> $records */
        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }
}
