<?php

declare(strict_types=1);

namespace Residencia\Model\Machote;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;

final class MachoteReabrirModel
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
    public function findMachoteById(int $machoteId): ?array
    {
        $sql = 'SELECT id, convenio_id, confirmacion_empresa, estatus FROM rp_convenio_machote WHERE id = :machote_id LIMIT 1';

        $statement = $this->connection->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    public function reabrirMachote(int $machoteId): bool
    {
        $sql = <<<'SQL'
            UPDATE rp_convenio_machote
            SET confirmacion_empresa = 0,
                estatus = 'En revisión',
                actualizado_en = NOW()
            WHERE id = :machote_id
        SQL;

        $statement = $this->connection->prepare($sql);

        return $statement->execute([':machote_id' => $machoteId]);
    }
}
