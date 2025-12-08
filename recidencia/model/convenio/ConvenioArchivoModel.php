<?php
declare(strict_types=1);

namespace Residencia\Model\Convenio;

use PDO;

class ConvenioArchivoModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getArchivo(int $archivoId): ?array
    {
        $sql = <<<'SQL'
            SELECT id,
                   empresa_id,
                   convenio_id_original,
                   fecha_archivo,
                   snapshot,
                   motivo
              FROM rp_convenio_archivado
             WHERE id = :id
             LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $archivoId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row === false) {
            return null;
        }

        $row['snapshot_data'] = json_decode((string) $row['snapshot'], true);

        return $row;
    }
}
