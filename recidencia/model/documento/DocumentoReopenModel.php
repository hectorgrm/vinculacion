<?php

declare(strict_types=1);

namespace Residencia\Model\Documento;

require_once __DIR__ . '/../../../common/model/db.php';

use Common\Model\Database;
use PDO;
use RuntimeException;
use Throwable;

class DocumentoReopenModel
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? Database::getConnection();
    }

    /**
     * @return array<string, mixed>
     */
    public function reopenDocument(int $documentId): array
    {
        $this->pdo->beginTransaction();

        try {
            $document = $this->fetchDocument($documentId, true);
            if ($document === null) {
                throw new RuntimeException('El documento solicitado no existe.', 404);
            }

            $estatusActual = isset($document['estatus']) ? (string) $document['estatus'] : '';
            $estatusActual = function_exists('mb_strtolower')
                ? mb_strtolower($estatusActual, 'UTF-8')
                : strtolower($estatusActual);

            $empresaEstatus = isset($document['empresa_estatus']) ? trim((string) $document['empresa_estatus']) : '';
            $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;

            if ($empresaIsCompletada) {
                throw new RuntimeException('No se pueden reabrir documentos de empresas en estatus Completada.', 403);
            }

            if ($estatusActual !== 'aprobado') {
                throw new RuntimeException('Solo se pueden reabrir documentos aprobados.', 400);
            }

            $updateSql = 'UPDATE rp_empresa_doc SET estatus = :estatus WHERE id = :id LIMIT 1';
            $statement = $this->pdo->prepare($updateSql);
            $statement->bindValue(':estatus', 'pendiente', PDO::PARAM_STR);
            $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
            $statement->execute();

            if ($statement->rowCount() !== 1) {
                throw new RuntimeException('No se pudo reabrir la revision del documento.', 500);
            }

            $this->pdo->commit();

            $updated = $this->fetchDocument($documentId, false);
            if ($updated !== null) {
                return $updated;
            }

            $document['estatus'] = 'pendiente';

            return $document;
        } catch (Throwable $throwable) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $throwable;
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    private function fetchDocument(int $documentId, bool $forUpdate): ?array
    {
        $sql = <<<'SQL'
            SELECT d.id,
                   d.empresa_id,
                   e.nombre AS empresa_nombre,
                   e.estatus AS empresa_estatus,
                   d.tipo_global_id,
                   d.tipo_personalizado_id,
                   d.ruta,
                   d.estatus,
                   d.observacion,
                   d.creado_en
              FROM rp_empresa_doc AS d
              JOIN rp_empresa AS e ON e.id = d.empresa_id
             WHERE d.id = :id
             LIMIT 1
        SQL;

        if ($forUpdate) {
            $sql .= ' FOR UPDATE';
        }

        $statement = $this->pdo->prepare($sql);
        $statement->bindValue(':id', $documentId, PDO::PARAM_INT);
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result !== false ? $result : null;
    }
}
