<?php
declare(strict_types=1);

require_once __DIR__ . '/Conexion.php';

class MachoteGlobalModel
{
    /**
     * Obtiene el último machote global (por id más alto o estado vigente)
     */
    public static function getLatest(): ?array
    {
        $pdo = Conexion::getConexion();

        // Prioriza el más reciente en estado 'vigente', si no hay, toma el último registrado
        $stmt = $pdo->query("
            SELECT * 
            FROM rp_machote
            WHERE estado = 'vigente'
            ORDER BY id DESC 
            LIMIT 1
        ");

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Si no hay vigente, trae el más reciente aunque esté en otro estado
            $stmt = $pdo->query("SELECT * FROM rp_machote ORDER BY id DESC LIMIT 1");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $result ?: null;
    }

    /**
     * Obtiene un machote global específico por ID
     */
    public static function getById(int $id): ?array
    {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM rp_machote WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
