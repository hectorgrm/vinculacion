<?php
namespace Residencia\Controller\Machote;

require_once __DIR__ . '/../../model/machote/MachoteComentarioModel.php';
use Residencia\Model\Machote\MachoteComentarioModel;
use PDO;
use Exception;

class MachoteComentarioController {
    private MachoteComentarioModel $model;

    public function __construct() {
        require __DIR__ . '/../../common/db_connection.php'; // tu conexión PDO
        $this->model = new MachoteComentarioModel($pdo);
    }

    public function obtenerComentarios(int $machoteId): array {
        return $this->model->getComentariosConRespuestas($machoteId);
    }

    public function responderComentario(array $data): bool {
        try {
            return $this->model->insertRespuesta($data);
        } catch (Exception $e) {
            error_log("Error insertando respuesta: " . $e->getMessage());
            return false;
        }
    }
}
