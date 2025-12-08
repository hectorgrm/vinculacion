<?php

declare(strict_types=1);

namespace Residencia\Model\Machote;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

final class MachoteConfirmModel
{
    public function __construct(private PDO $connection)
    {
    }

    public static function createWithDefaultConnection(): self
    {
        return new self(Database::getConnection());
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findMachoteForEmpresa(int $machoteId, int $empresaId): ?array
    {
        $sql = <<<'SQL'
            SELECT
                m.id,
                m.convenio_id,
                m.confirmacion_empresa,
                m.estatus,
                m.version_local,
                m.contenido_html,
                m.actualizado_en,
                c.empresa_id,
                c.estatus AS convenio_estatus,
                e.nombre AS empresa_nombre,
                e.representante AS empresa_representante,
                e.cargo_representante AS empresa_cargo,
                e.direccion AS empresa_direccion,
                e.municipio AS empresa_municipio,
                e.estado AS empresa_estado,
                e.cp AS empresa_cp
            FROM rp_convenio_machote AS m
            INNER JOIN rp_convenio AS c ON c.id = m.convenio_id
            INNER JOIN rp_empresa AS e ON e.id = c.empresa_id
            WHERE m.id = :machote_id
              AND c.empresa_id = :empresa_id
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

    public function countComentariosPendientes(int $machoteId): int
    {
        $sql = <<<'SQL'
            SELECT COUNT(*)
            FROM rp_machote_comentario
            WHERE machote_id = :machote_id
              AND estatus = 'pendiente'
              AND (respuesta_a IS NULL OR respuesta_a = 0)
        SQL;

        $statement = $this->connection->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $count = $statement->fetchColumn();

        return $count !== false ? (int) $count : 0;
    }

    public function confirmarMachote(int $machoteId, string $contenidoHtml): bool
    {
        $sql = <<<'SQL'
            UPDATE rp_convenio_machote
            SET contenido_html = :contenido_html,
                confirmacion_empresa = 1,
                estatus = 'Aprobado',
                actualizado_en = NOW()
            WHERE id = :machote_id
        SQL;

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':machote_id' => $machoteId,
            ':contenido_html' => $contenidoHtml,
        ]);
    }
}
