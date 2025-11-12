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
        $sql = "INSERT INTO rp_machote_comentario (machote_id, usuario_id, clausula, comentario, estatus, creado_en)
                VALUES (:machote_id, :usuario_id, :clausula, :comentario, 'pendiente', NOW())";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':machote_id' => $machoteId,
            ':usuario_id' => $usuarioId,
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
