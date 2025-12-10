<?php
declare(strict_types=1);

namespace Residencia\Model\Machote;

use PDO;
use Common\Model\Database;

class ConvenioMachoteModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Devuelve lista de machotes con empresa y convenio
     * @param string|null $search texto de búsqueda opcional
     * @return array<int, array<string, mixed>>
     */
    public function getAll(?string $search = null): array
    {
        $sql = "
            SELECT
                m.id,
                e.id AS empresa_id,
                e.nombre AS empresa_nombre,
                m.version_local AS machote_version,
                DATE_FORMAT(m.creado_en, '%Y-%m-%d') AS fecha,
                m.estatus AS machote_estatus
            FROM rp_convenio_machote m
            INNER JOIN rp_convenio c ON c.id = m.convenio_id
            INNER JOIN rp_empresa e ON e.id = c.empresa_id
        ";

        if (!empty($search)) {
            $sql .= " WHERE e.nombre LIKE :search OR m.version_local LIKE :search ";
        }

        $sql .= " ORDER BY m.creado_en DESC ";

        $stmt = $this->db->prepare($sql);
        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Permite obtener una sola fila por ID (opcional)
     */
    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT
                m.*,
                e.nombre AS empresa_nombre,
                c.folio AS convenio_folio
            FROM rp_convenio_machote m
            INNER JOIN rp_convenio c ON c.id = m.convenio_id
            INNER JOIN rp_empresa e ON e.id = c.empresa_id
            WHERE m.id = :id
        ");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Instanciación rápida con conexión por defecto
     */
    public static function createWithDefaultConnection(): self
    {
        $db = Database::getConnection();
        return new self($db);
    }
}
