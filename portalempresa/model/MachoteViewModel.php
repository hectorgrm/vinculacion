<?php
namespace PortalEmpresa\Model\Machote;

use PDO;
use Exception;

class MachoteViewModel
{
    private PDO $db;

    public function __construct()
    {
        require __DIR__ . '/../../../../config/database.php'; // tu conexión PDO
        $this->db = $pdo;
    }

    public function getMachoteById(int $id): ?array
    {
        $sql = "SELECT 
                    m.id, m.version_local, m.confirmacion_empresa,
                    c.estatus,
                    e.nombre AS empresa_nombre, e.logo_path AS empresa_logo
                FROM rp_convenio_machote m
                INNER JOIN rp_convenio c ON c.id = m.convenio_id
                INNER JOIN rp_empresa e ON e.id = c.empresa_id
                WHERE m.id = :id
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getComentariosByMachote(int $id): array
    {
        $sql = "SELECT id, clausula, comentario, estatus, creado_en 
                FROM rp_machote_comentario 
                WHERE machote_id = :id
                ORDER BY creado_en DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addComentario(int $machoteId, string $comentario, string $autorRol, ?int $usuarioId = null): bool
    {
        $sql = "INSERT INTO rp_machote_comentario (machote_id, usuario_id, clausula, comentario, estatus)
                VALUES (:m, :u, '', :c, 'pendiente')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'm' => $machoteId,
            'u' => $usuarioId,
            'c' => $comentario
        ]);
    }

    public function confirmarMachote(int $machoteId): bool
    {
        $sql = "UPDATE rp_convenio_machote SET confirmacion_empresa = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $machoteId]);
    }
}
