<?php
namespace PortalEmpresa\Model\Machote;

use Common\Model\Database;
use PDO;
use RuntimeException;

require_once __DIR__ . '/../../common/model/db.php';

class MachoteViewModel
{
    private PDO $connection;

    public function __construct(?PDO $connection = null)
    {
        if ($connection instanceof PDO) {
            $this->connection = $connection;
            return;
        }

        $this->connection = Database::getConnection();
    }

    /**
     * Obtiene un machote verificando que pertenezca a la empresa dada.
     *
     * @return array<string, mixed>|null
     */
    public function getMachoteForEmpresa(int $machoteId, int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT
                m.id                       AS machote_id,
                m.convenio_id              AS convenio_id,
                m.version_local            AS version_local,
                m.contenido_html           AS contenido_html,
                m.archivo_pdf              AS machote_pdf_path,
                COALESCE(m.confirmacion_empresa, 0) AS confirmacion_empresa,
                m.creado_en                AS machote_creado_en,
                m.actualizado_en           AS machote_actualizado_en,
                c.estatus                  AS convenio_estatus,
                c.folio                    AS convenio_folio,
                c.fecha_inicio             AS convenio_fecha_inicio,
                c.fecha_fin                AS convenio_fecha_fin,
                c.observaciones            AS convenio_observaciones,
                e.id                       AS empresa_id,
                e.nombre                   AS empresa_nombre,
                e.logo_path                AS empresa_logo
            FROM rp_convenio_machote AS m
            INNER JOIN rp_convenio AS c ON c.id = m.convenio_id
            INNER JOIN rp_empresa  AS e ON e.id = c.empresa_id
            WHERE m.id = :machote_id
              AND e.id = :empresa_id
            LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':machote_id' => $machoteId,
            ':empresa_id' => $empresaId,
        ]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getComentariosByMachote(int $machoteId): array
    {
        $sql = <<<'SQL'
            SELECT
                c.id,
                c.machote_id,
                c.usuario_id,
                c.clausula,
                c.comentario,
                c.estatus,
                c.creado_en,
                u.nombre AS usuario_nombre
            FROM rp_machote_comentario AS c
            LEFT JOIN usuario AS u ON u.id = c.usuario_id
            WHERE c.machote_id = :machote_id
            ORDER BY c.creado_en DESC
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $records = $statement->fetchAll(PDO::FETCH_ASSOC);

        return $records !== false ? $records : [];
    }

    /**
     * @return array{total: int, pendientes: int, resueltos: int}
     */
    public function getComentarioStats(int $machoteId): array
    {
        $sql = <<<'SQL'
            SELECT
                COUNT(*) AS total,
                SUM(CASE WHEN estatus = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
                SUM(CASE WHEN estatus = 'resuelto' THEN 1 ELSE 0 END) AS resueltos
            FROM rp_machote_comentario
            WHERE machote_id = :machote_id
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return [
            'total' => isset($record['total']) ? (int) $record['total'] : 0,
            'pendientes' => isset($record['pendientes']) ? (int) $record['pendientes'] : 0,
            'resueltos' => isset($record['resueltos']) ? (int) $record['resueltos'] : 0,
        ];
    }

    public function addComentario(int $machoteId, int $empresaId, string $clausula, string $comentario, ?int $usuarioId = null): bool
    {
        if (!$this->machoteBelongsToEmpresa($machoteId, $empresaId)) {
            throw new RuntimeException('No es posible agregar comentarios a este machote.');
        }

        $sql = <<<'SQL'
            INSERT INTO rp_machote_comentario (machote_id, usuario_id, autor_rol, clausula, comentario, estatus, creado_en)
            VALUES (:machote_id, :usuario_id, :autor_rol, :clausula, :comentario, 'pendiente', NOW())
        SQL;

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':machote_id' => $machoteId,
            ':usuario_id' => $usuarioId,
            ':autor_rol'  => 'empresa',
            ':clausula'   => $clausula,
            ':comentario' => $comentario,
        ]);
    }

    public function confirmarMachote(int $machoteId, int $empresaId): bool
    {
        if (!$this->machoteBelongsToEmpresa($machoteId, $empresaId)) {
            throw new RuntimeException('No puedes confirmar este machote.');
        }

        $sql = 'UPDATE rp_convenio_machote SET confirmacion_empresa = 1, actualizado_en = NOW() WHERE id = :machote_id';

        $statement = $this->connection->prepare($sql);

        return $statement->execute([':machote_id' => $machoteId]);
    }

    private function machoteBelongsToEmpresa(int $machoteId, int $empresaId): bool
    {
        $sql = <<<'SQL'
            SELECT 1
            FROM rp_convenio_machote AS m
            INNER JOIN rp_convenio AS c ON c.id = m.convenio_id
            WHERE m.id = :machote_id AND c.empresa_id = :empresa_id
            LIMIT 1
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([
            ':machote_id' => $machoteId,
            ':empresa_id' => $empresaId,
        ]);

        return $statement->fetchColumn() !== false;
    }
}
