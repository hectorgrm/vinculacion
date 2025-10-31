<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

class DocumentoListModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchDocuments(
        ?string $search = null,
        ?int $empresaId = null,
        ?int $tipoId = null,
        ?string $estatus = null
    ): array {
        $sql = <<<'SQL'
            SELECT d.id,
                   d.empresa_id,
                   e.nombre AS empresa_nombre,
                   d.tipo_global_id,
                   d.tipo_personalizado_id,
                   COALESCE(tg.nombre, tp.nombre, CONCAT('Documento #', d.id)) AS tipo_nombre,
                   d.ruta,
                   d.estatus,
                   d.observacion,
                   d.creado_en
              FROM rp_empresa_doc AS d
              JOIN rp_empresa AS e ON e.id = d.empresa_id
              LEFT JOIN rp_documento_tipo AS tg ON tg.id = d.tipo_global_id
              LEFT JOIN rp_documento_tipo_empresa AS tp ON tp.id = d.tipo_personalizado_id
        SQL;

        $conditions = [];
        $params = [];

        if ($search !== null && $search !== '') {
            $conditions[] = '(
                e.nombre LIKE :search
                OR tg.nombre LIKE :search
                OR tp.nombre LIKE :search
                OR d.observacion LIKE :search
            )';
            $params[':search'] = '%' . $search . '%';
        }

        if ($empresaId !== null) {
            $conditions[] = 'd.empresa_id = :empresa_id';
            $params[':empresa_id'] = $empresaId;
        }

        if ($tipoId !== null) {
            $conditions[] = 'd.tipo_global_id = :tipo_id';
            $params[':tipo_id'] = $tipoId;
        }

        if ($estatus !== null && $estatus !== '') {
            $conditions[] = 'd.estatus = :estatus';
            $params[':estatus'] = $estatus;
        }

        if ($conditions !== []) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY d.creado_en DESC, d.id DESC';

        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchEmpresas(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre
              FROM rp_empresa
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetchTipos(): array
    {
        $sql = <<<'SQL'
            SELECT id,
                   nombre
              FROM rp_documento_tipo
             ORDER BY nombre ASC
        SQL;

        $statement = $this->pdo->query($sql);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

