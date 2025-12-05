<?php
declare(strict_types=1);

namespace Residencia\Model\Convenio;

use PDO;
use PDOException;
use RuntimeException;

class ConvenioArchivarModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getConvenioResumen(int $convenioId): ?array
    {
        $sql = <<<'SQL'
            SELECT c.id,
                   c.empresa_id,
                   c.estatus,
                   c.folio,
                   c.tipo_convenio,
                   e.nombre AS empresa_nombre,
                   e.estatus AS empresa_estatus
              FROM rp_convenio AS c
              JOIN rp_empresa AS e ON e.id = c.empresa_id
             WHERE c.id = :id
             LIMIT 1
        SQL;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $convenioId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row !== false ? $row : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSnapshot(int $convenioId, int $empresaId, ?string $motivo): array
    {
        $snapshot = [];

        $snapshot['convenio'] = $this->fetchAllRows('SELECT * FROM rp_convenio WHERE id = :id', [':id' => $convenioId]);
        $machotes = $this->fetchAllRows('SELECT * FROM rp_convenio_machote WHERE convenio_id = :id', [':id' => $convenioId]);
        $snapshot['machote'] = $machotes;
        $snapshot['machote_revisiones'] = $this->fetchAllRows(
            'SELECT * FROM rp_machote_revision WHERE empresa_id = :empresa_id',
            [':empresa_id' => $empresaId]
        );
        $snapshot['machote_revision_mensajes'] = $this->fetchRevisionMessages($snapshot['machote_revisiones']);
        $snapshot['machote_revision_archivos'] = $this->fetchRevisionFiles($snapshot['machote_revision_mensajes']);
        $snapshot['comentarios'] = $this->fetchComentariosPorMachote($machotes);
        $snapshot['documentos'] = $this->fetchAllRows('SELECT * FROM rp_empresa_doc WHERE empresa_id = :empresa_id', [':empresa_id' => $empresaId]);
        $snapshot['estudiantes'] = $this->fetchAllRows('SELECT * FROM rp_estudiante WHERE convenio_id = :convenio_id', [':convenio_id' => $convenioId]);
        $snapshot['asignaciones'] = $this->fetchAllRows('SELECT * FROM rp_asignacion WHERE empresa_id = :empresa_id', [':empresa_id' => $empresaId]);
        $snapshot['auditoria_detalle'] = $this->fetchAuditoriaDetalle($convenioId, $empresaId, $machotes, $snapshot['machote_revisiones']);
        $snapshot['metadata'] = [
            'motivo' => $motivo,
            'archivado_en' => date('Y-m-d H:i:s'),
        ];

        return $snapshot;
    }

    /**
     * @param array<string, mixed> $params
     * @return array<int, array<string, mixed>>
     */
    private function fetchAllRows(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array<int, array<string, mixed>> $machotes
     * @return array<int, array<string, mixed>>
     */
    private function fetchComentariosPorMachote(array $machotes): array
    {
        if ($machotes === []) {
            return [];
        }

        $ids = array_values(array_unique(array_column($machotes, 'id')));
        if ($ids === []) {
            return [];
        }

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $key = ':id' . $index;
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $sql = sprintf(
            'SELECT * FROM rp_machote_comentario WHERE machote_id IN (%s)',
            implode(',', $placeholders)
        );

        return $this->fetchAllRows($sql, $params);
    }

    /**
     * @param array<int, array<string, mixed>> $machotes
     * @return array<int, array<string, mixed>>
     */
    private function fetchRevisionMessages(array $revisiones): array
    {
        if ($revisiones === []) {
            return [];
        }

        $ids = array_values(array_unique(array_column($revisiones, 'id')));
        if ($ids === []) {
            return [];
        }

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $key = ':rev_' . $index;
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $sql = sprintf(
            'SELECT * FROM rp_machote_revision_msg WHERE revision_id IN (%s)',
            implode(',', $placeholders)
        );

        return $this->fetchAllRows($sql, $params);
    }

    /**
     * @param array<int, array<string, mixed>> $mensajes
     * @return array<int, array<string, mixed>>
     */
    private function fetchRevisionFiles(array $mensajes): array
    {
        if ($mensajes === []) {
            return [];
        }

        $ids = array_values(array_unique(array_column($mensajes, 'id')));
        if ($ids === []) {
            return [];
        }

        $placeholders = [];
        $params = [];
        foreach ($ids as $index => $id) {
            $key = ':msg_' . $index;
            $placeholders[] = $key;
            $params[$key] = $id;
        }

        $sql = sprintf(
            'SELECT * FROM rp_machote_revision_file WHERE msg_id IN (%s)',
            implode(',', $placeholders)
        );

        return $this->fetchAllRows($sql, $params);
    }

    private function fetchAuditoriaDetalle(int $convenioId, int $empresaId, array $machotes, array $revisiones): array
    {
        $conditions = ['(a.entidad = "rp_convenio" AND a.entidad_id = :convenio_id)'];
        $params = [':convenio_id' => $convenioId];

        $machoteIds = array_values(array_unique(array_column($machotes, 'id')));
        if ($machoteIds !== []) {
            $placeholders = [];
            foreach ($machoteIds as $index => $id) {
                $key = ':machote_id_' . $index;
                $placeholders[] = $key;
                $params[$key] = $id;
            }

            $conditions[] = sprintf('((a.entidad = "rp_convenio_machote") AND a.entidad_id IN (%s))', implode(',', $placeholders));
        }

        $revisionIds = array_values(array_unique(array_column($revisiones, 'id')));
        if ($revisionIds !== []) {
            $placeholders = [];
            foreach ($revisionIds as $index => $id) {
                $key = ':rev_id_' . $index;
                $placeholders[] = $key;
                $params[$key] = $id;
            }

            $conditions[] = sprintf('((a.entidad = "rp_machote_revision") AND a.entidad_id IN (%s))', implode(',', $placeholders));
        }

        $conditions[] = '(a.entidad = "rp_empresa_doc" AND a.entidad_id IN (SELECT id FROM rp_empresa_doc WHERE empresa_id = :empresa_id))';
        $params[':empresa_id'] = $empresaId;

        $sql = sprintf(
            'SELECT ad.id AS detalle_id,
                    ad.auditoria_id,
                    ad.campo,
                    ad.campo_label,
                    ad.valor_anterior,
                    ad.valor_nuevo,
                    ad.creado_en,
                    a.actor_tipo,
                    a.actor_id,
                    a.accion,
                    a.entidad,
                    a.entidad_id,
                    a.ip,
                    a.ts
               FROM auditoria_detalle AS ad
               JOIN auditoria AS a ON a.id = ad.auditoria_id
              WHERE %s
           ORDER BY ad.creado_en DESC, ad.id DESC',
            implode(' OR ', $conditions)
        );

        return $this->fetchAllRows($sql, $params);
    }

    public function archivarConvenio(int $convenioId, ?string $motivo = null): int
    {
        $resumen = $this->getConvenioResumen($convenioId);
        if ($resumen === null) {
            throw new RuntimeException('No se encontró el convenio a archivar.');
        }

        $empresaId = (int) $resumen['empresa_id'];
        $snapshot = $this->buildSnapshot($convenioId, $empresaId, $motivo);
        $json = json_encode($snapshot, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('No se pudo construir el snapshot del convenio.');
        }

        try {
            $this->pdo->beginTransaction();

            $insertSql = <<<'SQL'
                INSERT INTO rp_convenio_archivado (empresa_id, convenio_id_original, fecha_archivo, snapshot, motivo)
                VALUES (:empresa_id, :convenio_id_original, NOW(), :snapshot, :motivo)
            SQL;
            $insert = $this->pdo->prepare($insertSql);
            $insert->execute([
                ':empresa_id' => $empresaId,
                ':convenio_id_original' => $convenioId,
                ':snapshot' => $json,
                ':motivo' => $motivo,
            ]);

            $archivoId = (int) $this->pdo->lastInsertId();

            $this->updateEstados($convenioId, $empresaId);

            $this->pdo->commit();
        } catch (PDOException $exception) {
            $this->pdo->rollBack();
            throw new RuntimeException('No se pudo archivar el convenio. Intenta nuevamente.', 0, $exception);
        }

        return $archivoId;
    }

    private function updateEstados(int $convenioId, int $empresaId): void
    {
        $updateConvenio = $this->pdo->prepare('UPDATE rp_convenio SET estatus = "Inactiva" WHERE id = :id');
        $updateConvenio->execute([':id' => $convenioId]);

        $updateMachote = $this->pdo->prepare(
            'UPDATE rp_convenio_machote
                SET estatus_general = "Cerrado",
                    estatus = "Con observaciones"
              WHERE convenio_id = :id'
        );
        $updateMachote->execute([':id' => $convenioId]);

        $updateComentarios = $this->pdo->prepare(
            'UPDATE rp_machote_comentario
                SET estatus = "resuelto", estatus_general = "Cerrado"
              WHERE machote_id IN (SELECT id FROM rp_convenio_machote WHERE convenio_id = :id)'
        );
        $updateComentarios->execute([':id' => $convenioId]);

        $updateDocumentos = $this->pdo->prepare('UPDATE rp_empresa_doc SET estatus = "revision" WHERE empresa_id = :empresa_id');
        $updateDocumentos->execute([':empresa_id' => $empresaId]);

        $updateEstudiantes = $this->pdo->prepare('UPDATE rp_estudiante SET convenio_id = NULL WHERE convenio_id = :convenio_id');
        $updateEstudiantes->execute([':convenio_id' => $convenioId]);

        $updateAsignaciones = $this->pdo->prepare('UPDATE rp_asignacion SET estatus = "cancelado" WHERE empresa_id = :empresa_id');
        $updateAsignaciones->execute([':empresa_id' => $empresaId]);

        $updateRevisiones = $this->pdo->prepare(
            'UPDATE rp_machote_revision
                SET estado = "cancelado", cerrado_en = IFNULL(cerrado_en, NOW())
              WHERE empresa_id = :empresa_id AND estado <> "acordado"'
        );
        $updateRevisiones->execute([':empresa_id' => $empresaId]);

        $updateRevisionMensajes = $this->pdo->prepare(
            'UPDATE rp_machote_revision_msg
                SET estatus = "resuelto"
              WHERE revision_id IN (SELECT id FROM rp_machote_revision WHERE empresa_id = :empresa_id)'
        );
        $updateRevisionMensajes->execute([':empresa_id' => $empresaId]);
    }
}
