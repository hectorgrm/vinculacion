<?php
namespace Residencia\Model\Machote;

use PDO;
use Exception;

class MachoteComentarioModel {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    // Obtener comentarios principales y respuestas
    public function getComentariosConRespuestas(int $machoteId): array {
        $sql = "SELECT * FROM rp_machote_comentario WHERE machote_id = :id ORDER BY creado_en ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $machoteId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comentarios = [];
        foreach ($rows as $row) {
            if ($row['respuesta_a'] === null) {
                $comentarios[$row['id']] = $row;
                $comentarios[$row['id']]['respuestas'] = [];
            }
        }

        foreach ($rows as $row) {
            if ($row['respuesta_a'] !== null && isset($comentarios[$row['respuesta_a']])) {
                $comentarios[$row['respuesta_a']]['respuestas'][] = $row;
            }
        }

        return $comentarios;
    }

    // Insertar una nueva respuesta
    public function insertRespuesta(array $data): bool {
        $archivoPath = null;
        if (!empty($data['archivo']) && $data['archivo']['error'] === UPLOAD_ERR_OK) {
            $nombreArchivo = time() . '_' . basename($data['archivo']['name']);
            $destino = __DIR__ . '/../../../uploads/' . $nombreArchivo;
            move_uploaded_file($data['archivo']['tmp_name'], $destino);
            $archivoPath = $nombreArchivo;
        }

        $sql = "INSERT INTO rp_machote_comentario 
                (machote_id, respuesta_a, usuario_id, autor_rol, clausula, comentario, archivo_path)
                VALUES (:machote_id, :respuesta_a, :usuario_id, :autor_rol, '', :comentario, :archivo_path)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':machote_id' => $data['machote_id'],
            ':respuesta_a' => $data['respuesta_a'],
            ':usuario_id' => $data['usuario_id'] ?? null,
            ':autor_rol' => $data['autor_rol'],
            ':comentario' => $data['comentario'],
            ':archivo_path' => $archivoPath,
        ]);
    }
}
