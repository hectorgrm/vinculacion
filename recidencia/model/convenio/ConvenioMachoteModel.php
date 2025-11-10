<?php

declare(strict_types=1);

namespace Residencia\Model\Convenio;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use PDOException;

final class ConvenioMachoteModel
{
    public function __construct(private PDO $connection)
    {
    }

    /**
     * Crea un machote hijo (copiado desde el global)
     *
     * @throws PDOException
     */
    public function create(int $convenioId, int $machotePadreId, string $contenidoHtml, string $versionLocal = 'v1.0'): int
    {
        $statement = $this->connection->prepare(
            'INSERT INTO rp_convenio_machote (convenio_id, machote_padre_id, contenido_html, version_local, creado_en)
             VALUES (:convenio_id, :machote_padre_id, :contenido_html, :version_local, NOW())'
        );

        $statement->execute([
            ':convenio_id' => $convenioId,
            ':machote_padre_id' => $machotePadreId,
            ':contenido_html' => $contenidoHtml,
            ':version_local' => $versionLocal,
        ]);

        return (int) $this->connection->lastInsertId();
    }

    /**
     * Obtiene el machote hijo vinculado a un convenio.
     *
     * @return array<string, mixed>|null
     */
    public function getByConvenio(int $convenioId): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM rp_convenio_machote WHERE convenio_id = :convenio_id LIMIT 1');
        $statement->execute([':convenio_id' => $convenioId]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * Obtiene un machote hijo por su identificador.
     *
     * @return array<string, mixed>|null
     */
    public function getById(int $id): ?array
    {
        $statement = $this->connection->prepare('SELECT * FROM rp_convenio_machote WHERE id = :id LIMIT 1');
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }

    /**
     * Actualiza el contenido HTML del machote hijo.
     */
    public function updateContent(int $id, string $contenido): bool
    {
        $statement = $this->connection->prepare(
            'UPDATE rp_convenio_machote
             SET contenido_html = :contenido_html, actualizado_en = NOW()
             WHERE id = :id'
        );

        return $statement->execute([
            ':contenido_html' => $contenido,
            ':id' => $id,
        ]);
    }

    public static function createWithDefaultConnection(): self
    {
        return new self(Database::getConnection());
    }
}
