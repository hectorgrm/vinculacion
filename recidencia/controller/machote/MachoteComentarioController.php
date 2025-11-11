<?php
/**
 * ===============================================================
 * Controlador: MachoteComentarioController
 * ---------------------------------------------------------------
 * Gestiona las operaciones de los comentarios en los machotes:
 *  - Listar comentarios de un machote
 *  - Agregar nuevo comentario
 *  - Marcar como resuelto
 *  - Eliminar comentario
 * 
 * Responde a las solicitudes que llegan desde el handler.
 * ===============================================================
 */

declare(strict_types=1);

namespace Residencia\Controller\Machote;

use Common\Model\Database;
use Residencia\Model\Machote\MachoteComentarioModel;
use Throwable;

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../../model/machote/MachoteComentarioModel.php';

class MachoteComentarioController
{
    private MachoteComentarioModel $model;

    /**
     * Constructor: inicializa la conexión con la base de datos
     */
    public function __construct()
    {
        $connection = Database::getConnection();
        $this->model = new MachoteComentarioModel($connection);
    }

    // ===============================================================
    // 🔹 1. Obtener todos los comentarios de un machote
    // ===============================================================
    /**
     * @param int $machoteId
     * @return array<string, mixed>
     */
    public function listarComentarios(int $machoteId): array
    {
        try {
            $comentarios = $this->model->getByMachote($machoteId);
            return [
                'success' => true,
                'data' => $comentarios
            ];
        } catch (Throwable $e) {
            error_log('Error listarComentarios: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error al obtener los comentarios.'
            ];
        }
    }

    // ===============================================================
    // 🔹 2. Agregar nuevo comentario
    // ===============================================================
    /**
     * @param int $machoteId
     * @param int|null $usuarioId
     * @param string $clausula
     * @param string $comentario
     * @return array<string, mixed>
     */
    public function agregarComentario(int $machoteId, ?int $usuarioId, string $clausula, string $comentario): array
    {
        if (trim($comentario) === '') {
            return [
                'success' => false,
                'error' => 'El comentario no puede estar vacío.'
            ];
        }

        try {
            $ok = $this->model->addComentario($machoteId, $usuarioId, $clausula, $comentario);
            return [
                'success' => $ok,
                'message' => $ok ? 'Comentario agregado correctamente.' : 'No se pudo agregar el comentario.'
            ];
        } catch (Throwable $e) {
            error_log('Error agregarComentario: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error interno al agregar comentario.'
            ];
        }
    }

    // ===============================================================
    // 🔹 3. Marcar comentario como resuelto
    // ===============================================================
    /**
     * @param int $comentarioId
     * @return array<string, mixed>
     */
    public function marcarResuelto(int $comentarioId): array
    {
        try {
            $ok = $this->model->marcarResuelto($comentarioId);
            return [
                'success' => $ok,
                'message' => $ok ? 'Comentario marcado como resuelto.' : 'No se pudo actualizar el comentario.'
            ];
        } catch (Throwable $e) {
            error_log('Error marcarResuelto: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error interno al actualizar comentario.'
            ];
        }
    }

    // ===============================================================
    // 🔹 4. Eliminar comentario
    // ===============================================================
    /**
     * @param int $comentarioId
     * @return array<string, mixed>
     */
    public function eliminarComentario(int $comentarioId): array
    {
        try {
            $ok = $this->model->deleteComentario($comentarioId);
            return [
                'success' => $ok,
                'message' => $ok ? 'Comentario eliminado correctamente.' : 'No se pudo eliminar el comentario.'
            ];
        } catch (Throwable $e) {
            error_log('Error eliminarComentario: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error interno al eliminar comentario.'
            ];
        }
    }

    // ===============================================================
    // 🔹 5. Contar pendientes (útil para dashboard o alertas)
    // ===============================================================
    /**
     * @param int $machoteId
     * @return array<string, mixed>
     */
    public function contarPendientes(int $machoteId): array
    {
        try {
            $total = $this->model->contarPendientes($machoteId);
            return [
                'success' => true,
                'total' => $total
            ];
        } catch (Throwable $e) {
            error_log('Error contarPendientes: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Error al contar los comentarios pendientes.'
            ];
        }
    }
}
