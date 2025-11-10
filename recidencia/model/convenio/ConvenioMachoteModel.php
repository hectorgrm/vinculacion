<?php
declare(strict_types=1);

require_once __DIR__ . '/Conexion.php';

class ConvenioMachoteModel
{
    /**
     * Crea un machote hijo (copiado desde el global)
     */
    public static function create(array $data): int
    {
        $pdo = Conexion::getConexion();

        $stmt = $pdo->prepare("
            INSERT INTO rp_convenio_machote
                (convenio_id, machote_padre_id, contenido_html, version_local, creado_en)
            VALUES
                (:convenio_id, :machote_padre_id, :contenido_html, :version_local, NOW())
        ");

        $stmt->execute([
            ':convenio_id'      => $data['convenio_id'],
            ':machote_padre_id' => $data['machote_padre_id'],
            ':contenido_html'   => $data['contenido_html'],
            ':version_local'    => $data['version_local'] ?? 'v1.0',
        ]);

        return (int)$pdo->lastInsertId();
    }

    /**
     * Obtiene el machote hijo vinculado a un convenio
     */
    public static function getByConvenio(int $convenioId): ?array
    {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM rp_convenio_machote WHERE convenio_id = ?");
        $stmt->execute([$convenioId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Obtiene un machote hijo por ID
     */
    public static function getById(int $id): ?array
    {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("SELECT * FROM rp_convenio_machote WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Actualiza el contenido HTML del machote hijo
     */
    public static function updateContent(int $id, string $contenido): bool
    {
        $pdo = Conexion::getConexion();
        $stmt = $pdo->prepare("
            UPDATE rp_convenio_machote 
            SET contenido_html = :contenido_html, actualizado_en = NOW()
            WHERE id = :id
        ");
        return $stmt->execute([
            ':contenido_html' => $contenido,
            ':id' => $id
        ]);
    }
}
