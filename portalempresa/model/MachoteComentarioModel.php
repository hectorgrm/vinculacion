<?php
declare(strict_types=1);

namespace PortalEmpresa\Model\Machote;

use Common\Model\Database;
use PDO;
use RuntimeException;

require_once __DIR__ . '/../../common/model/db.php';

final class MachoteComentarioModel
{
    private PDO $db;

    public function __construct(?PDO $connection = null)
    {
        $this->db = $connection ?? Database::getConnection();
    }

    /**
     * Obtiene todos los comentarios de un machote y construye sus hilos de respuestas.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getComentariosConRespuestas(int $machoteId): array
    {
        $sql = <<<'SQL'
            SELECT c.*, u.nombre AS usuario_nombre
            FROM rp_machote_comentario AS c
            LEFT JOIN usuario AS u ON u.id = c.usuario_id
            WHERE c.machote_id = :machote_id
            ORDER BY c.creado_en ASC, c.id ASC
        SQL;

        $statement = $this->db->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $rows = $statement->fetchAll(PDO::FETCH_ASSOC) ?: [];

        return $this->buildThreads($rows);
    }

    /**
     * Registra una respuesta dentro de un hilo existente.
     *
     * @param array<string, mixed> $data
     */
        public function insertRespuesta(array $data): bool
    {
        $machoteId  = (int) ($data['machote_id'] ?? 0);
        $respuestaA = (int) ($data['respuesta_a'] ?? 0);
        $autorRol   = (string) ($data['autor_rol'] ?? 'empresa');
        $comentario = trim((string) ($data['comentario'] ?? ''));
        $usuarioId  = isset($data['usuario_id']) && (int) $data['usuario_id'] > 0
            ? (int) $data['usuario_id']
            : null;
        $archivo    = $data['archivo'] ?? null;

        if ($machoteId <= 0 || $respuestaA <= 0 || $comentario === '') {
            throw new RuntimeException('Datos insuficientes para registrar la respuesta.');
        }

        if (!in_array($autorRol, ['admin', 'empresa'], true)) {
            $autorRol = 'empresa';
        }

        if (!$this->comentarioPerteneceAlMachote($respuestaA, $machoteId)) {
            throw new RuntimeException('El comentario seleccionado no pertenece al machote indicado.');
        }

        $contexto = $this->fetchMachoteContext($machoteId);

        if ($contexto === null) {
            throw new RuntimeException('El machote no estA? disponible.');
        }

        if ((int) ($contexto['confirmacion_empresa'] ?? 0) === 1) {
            throw new RuntimeException('El machote estA? bloqueado por el estatus del convenio.');
        }

        if ($this->isEmpresaInactiva($contexto['empresa_estatus'] ?? null)) {
            throw new RuntimeException('Convenio ya no activo');
        }

        if (!$this->estatusConvenioActivo($contexto['convenio_estatus'] ?? null)) {
            throw new RuntimeException('El machote estA? bloqueado por el estatus del convenio.');
        }

        $archivoPath = $this->saveUploadedFile($archivo);

        $sql = <<<'SQL'
            INSERT INTO rp_machote_comentario
                (machote_id, respuesta_a, usuario_id, autor_rol, clausula, comentario, archivo_path, estatus, creado_en)
            VALUES
                (:machote_id, :respuesta_a, :usuario_id, :autor_rol, '', :comentario, :archivo_path, 'pendiente', NOW())
        SQL;

        $statement = $this->db->prepare($sql);

        return $statement->execute([
            ':machote_id'  => $machoteId,
            ':respuesta_a' => $respuestaA,
            ':usuario_id'  => $usuarioId,
            ':autor_rol'   => $autorRol,
            ':comentario'  => $comentario,
            ':archivo_path'=> $archivoPath,
        ]);
    }

/**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function buildThreads(array $rows): array
    {
        $map = [];
        foreach ($rows as $row) {
            $comment = $this->mapRow($row);
            $map[$comment['id']] = $comment;
        }

        $roots = [];
        foreach ($map as $id => &$comment) {
            $parentId = $comment['respuesta_a'];
            if ($parentId !== null && isset($map[$parentId])) {
                $map[$parentId]['respuestas'][] =& $comment;
            } else {
                $roots[] =& $comment;
            }
        }
        unset($comment);

        return array_map(static fn ($item) => $item, $roots);
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function mapRow(array $row): array
    {
        $respuestaA = $row['respuesta_a'] !== null ? (int) $row['respuesta_a'] : null;
        $autorRol = (string) ($row['autor_rol'] ?? 'empresa');
        $autorNombre = $autorRol === 'admin'
            ? ((string) ($row['usuario_nombre'] ?? 'Vinculación'))
            : 'Empresa';

        return [
            'id'           => (int) ($row['id'] ?? 0),
            'machote_id'   => (int) ($row['machote_id'] ?? 0),
            'respuesta_a'  => $respuestaA,
            'usuario_id'   => $row['usuario_id'] !== null ? (int) $row['usuario_id'] : null,
            'autor_rol'    => $autorRol,
            'autor_nombre' => $autorNombre,
            'clausula'     => (string) ($row['clausula'] ?? ''),
            'comentario'   => (string) ($row['comentario'] ?? ''),
            'estatus'      => (string) ($row['estatus'] ?? 'pendiente'),
            'archivo_path' => $row['archivo_path'] ?? null,
            'creado_en'    => (string) ($row['creado_en'] ?? ''),
            'respuestas'   => [],
        ];
    }

    /**
     * @param array<string, mixed>|null $archivo
     */
    private function saveUploadedFile(?array $archivo): ?string
    {
        if ($archivo === null || ($archivo['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadsDir = dirname(__DIR__, 2) . '/uploads/machote_respuestas';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0775, true);
        }

        $originalName = (string) ($archivo['name'] ?? 'archivo');
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $baseName = pathinfo($originalName, PATHINFO_FILENAME);
        $safeBase = preg_replace('/[^A-Za-z0-9_-]/', '_', $baseName) ?: 'adjunto';

        try {
            $token = bin2hex(random_bytes(5));
        } catch (\Throwable) {
            $token = (string) mt_rand();
        }

        $fileName = sprintf('%s_%s', $safeBase, $token);
        if ($extension !== '') {
            $fileName .= '.' . strtolower($extension);
        }

        $destino = rtrim($uploadsDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;

        if (!move_uploaded_file($archivo['tmp_name'], $destino)) {
            return null;
        }

        return 'machote_respuestas/' . $fileName;
    }

    private function comentarioPerteneceAlMachote(int $comentarioId, int $machoteId): bool
    {
        $sql = 'SELECT 1 FROM rp_machote_comentario WHERE id = :id AND machote_id = :machote_id LIMIT 1';
        $statement = $this->db->prepare($sql);
        $statement->execute([
            ':id' => $comentarioId,
            ':machote_id' => $machoteId,
        ]);

        return $statement->fetchColumn() !== false;
    }

    private function fetchMachoteContext(int $machoteId): ?array
    {
        $sql = 'SELECT m.confirmacion_empresa, c.estatus AS convenio_estatus, e.estatus AS empresa_estatus'
            . ' FROM rp_convenio_machote AS m'
            . ' INNER JOIN rp_convenio AS c ON c.id = m.convenio_id'
            . ' INNER JOIN rp_empresa AS e ON e.id = c.empresa_id'
            . ' WHERE m.id = :machote_id'
            . ' LIMIT 1';

        $statement = $this->db->prepare($sql);
        $statement->execute([':machote_id' => $machoteId]);

        $record = $statement->fetch(PDO::FETCH_ASSOC);

        return $record !== false ? $record : null;
    }

    private function estatusConvenioActivo(?string $estatus): bool
    {
        $normalizado = $this->normalizarEstatus($estatus);

        if ($normalizado === '') {
            return false;
        }

        if (str_contains($normalizado, 'activa')) {
            return true;
        }

        return str_contains($normalizado, 'revisi');
    }

    private function isEmpresaInactiva(?string $estatus): bool
    {
        $normalizado = $this->normalizarEstatus($estatus);

        return $normalizado === 'inactiva';
    }

    private function normalizarEstatus(?string $estatus): string
    {
        if ($estatus === null) {
            return '';
        }

        $clean = str_replace(['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'], ['a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'], (string) $estatus);
        $clean = function_exists('mb_strtolower') ? mb_strtolower($clean, 'UTF-8') : strtolower($clean);

        return trim($clean);
    }
}
