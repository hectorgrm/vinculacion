<?php
/**
 * ===============================================================
 *  Modelo: MachoteComentarioModel
 * ---------------------------------------------------------------
 *  Gestiona los comentarios asociados a los machotes (documentos hijos)
 *  vinculados a los convenios. Permite agregar, listar, resolver y
 *  eliminar comentarios de revisión.
 * ===============================================================
 */

declare(strict_types=1);

namespace Residencia\Model\Machote;

use PDO;

class MachoteComentarioModel
{
    private PDO $db;

    /**
     * Constructor
     */
    public function __construct(PDO $connection)
    {
        $this->db = $connection;
    }

    // ===============================================================
    // 🔹 1. Obtener comentarios por machote
    // ===============================================================
    /**
     * Devuelve todos los comentarios asociados a un machote.
     *
     * @param int $machoteId
     * @return array<int, array<string, mixed>>
     */
    public function getByMachote(int $machoteId): array
    {
        $sql = "SELECT c.id, c.machote_id, c.usuario_id, u.nombre AS usuario_nombre,
                       c.clausula, c.comentario, c.estatus, c.creado_en
                FROM rp_machote_comentario c
                LEFT JOIN usuario u ON c.usuario_id = u.id
                WHERE c.machote_id = :machote_id
                ORDER BY c.creado_en DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':machote_id' => $machoteId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    }

    // ===============================================================
    // 🔹 2. Agregar nuevo comentario
    // ===============================================================
    /**
     * Inserta un nuevo comentario vinculado a un machote.
     *
     * @param int $machoteId
     * @param int|null $usuarioId
     * @param string $clausula
     * @param string $comentario
     * @return bool
     */
    public function addComentario(int $machoteId, ?int $usuarioId, string $clausula, string $comentario): bool
    {
        $sql = "INSERT INTO rp_machote_comentario (machote_id, usuario_id, autor_rol, clausula, comentario, estatus, creado_en)
                VALUES (:machote_id, :usuario_id, :autor_rol, :clausula, :comentario, 'pendiente', NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':machote_id' => $machoteId,
            ':usuario_id' => $usuarioId,
            ':autor_rol'  => $usuarioId !== null ? 'admin' : 'empresa',
            ':clausula'   => trim($clausula),
            ':comentario' => trim($comentario),
        ]);
    }

    // ===============================================================
    // 🔹 3. Marcar comentario como resuelto
    // ===============================================================
    /**
     * Cambia el estatus de un comentario a “resuelto”.
     *
     * @param int $comentarioId
     * @return bool
     */
    public function marcarResuelto(int $comentarioId): bool
    {
        $sql = "UPDATE rp_machote_comentario
                SET estatus = 'resuelto'
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $comentarioId]);
    }

    // ===============================================================
    // 🔹 4. Reabrir comentario
    // ===============================================================
    /**
     * Restablece el estatus de un comentario a “pendiente”.
     */
    public function reabrirComentario(int $comentarioId): bool
    {
        $sql = "UPDATE rp_machote_comentario
                SET estatus = 'pendiente'
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $comentarioId]);
    }

    // ===============================================================
    // 🔹 5. Eliminar comentario
    // ===============================================================
    /**
     * Elimina un comentario por su ID.
     *
     * @param int $comentarioId
     * @return bool
     */
    public function deleteComentario(int $comentarioId): bool
    {
        $sql = "DELETE FROM rp_machote_comentario WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $comentarioId]);
    }

    public function machoteEstaBloqueado(int $machoteId): bool
    {
        $sql = 'SELECT m.confirmacion_empresa, c.estatus AS convenio_estatus
                FROM rp_convenio_machote AS m
                INNER JOIN rp_convenio AS c ON c.id = m.convenio_id
                WHERE m.id = :machote_id
                LIMIT 1';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':machote_id' => $machoteId]);

        $record = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($record === false) {
            return true;
        }

        if ((int) ($record['confirmacion_empresa'] ?? 0) === 1) {
            return true;
        }

        $estatusConvenio = $record['convenio_estatus'] ?? null;
        $estatusNormalizado = $this->normalizeEstatus($estatusConvenio);

        if ($estatusNormalizado === null) {
            return true;
        }

        return str_contains($estatusNormalizado, 'suspend')
            || str_contains($estatusNormalizado, 'inactiv')
            || str_contains($estatusNormalizado, 'complet');
    }

    private function normalizeEstatus(?string $estatus): ?string
    {
        if ($estatus === null) {
            return null;
        }

        $estatus = trim((string) $estatus);

        if ($estatus === '') {
            return null;
        }

        $estatus = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $estatus);

        return function_exists('mb_strtolower')
            ? mb_strtolower($estatus, 'UTF-8')
            : strtolower($estatus);
    }

    // ===============================================================
    // 🔹 6. Contar pendientes por machote (opcional para dashboard)
    // ===============================================================
    /**
     * Devuelve cuántos comentarios pendientes tiene un machote.
     *
     * @param int $machoteId
     * @return int
     */
    public function contarPendientes(int $machoteId): int
    {
        $sql = "SELECT COUNT(*) FROM rp_machote_comentario
                WHERE machote_id = :machote_id AND estatus = 'pendiente'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':machote_id' => $machoteId]);
        return (int) $stmt->fetchColumn();
    }
}
