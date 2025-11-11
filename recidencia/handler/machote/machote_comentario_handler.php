<?php
/**
 * ===============================================================
 * Handler: machote_comentario_handler.php
 * ---------------------------------------------------------------
 * Recibe peticiones AJAX (GET/POST) para gestionar los comentarios
 * sobre los machotes. Responde siempre en formato JSON.
 * 
 * Soporta acciones:
 *   - action=listar     → obtener comentarios
 *   - action=agregar    → agregar comentario nuevo
 *   - action=resuelto   → marcar comentario como resuelto
 *   - action=eliminar   → eliminar comentario
 * ===============================================================
 */

declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../../controller/machote/MachoteComentarioController.php';

use Residencia\Controller\Machote\MachoteComentarioController;

// ===============================================================
// 1️⃣ Instanciar controlador
// ===============================================================
$controller = new MachoteComentarioController();

// ===============================================================
// 2️⃣ Detectar método y acción
// ===============================================================
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? $_POST['action'] ?? 'listar';

// ===============================================================
// 3️⃣ Ejecutar acción correspondiente
// ===============================================================
try {
    switch ($action) {
        // -----------------------------------------------------------
        // 🔹 LISTAR COMENTARIOS DE UN MACHOTE
        // -----------------------------------------------------------
        case 'listar':
            $machoteId = filter_input(INPUT_GET, 'machote_id', FILTER_VALIDATE_INT);
            if (!$machoteId) {
                echo json_encode(['success' => false, 'error' => 'ID de machote inválido.']);
                exit;
            }

            $result = $controller->listarComentarios($machoteId);
            echo json_encode($result);
            break;

        // -----------------------------------------------------------
        // 🔹 AGREGAR NUEVO COMENTARIO
        // -----------------------------------------------------------
        case 'agregar':
            $machoteId = filter_input(INPUT_POST, 'machote_id', FILTER_VALIDATE_INT);
            $usuarioId = filter_input(INPUT_POST, 'usuario_id', FILTER_VALIDATE_INT);
            $clausula  = trim((string)($_POST['clausula'] ?? ''));
            $comentario = trim((string)($_POST['comentario'] ?? ''));

            if (!$machoteId || $comentario === '') {
                echo json_encode(['success' => false, 'error' => 'Datos incompletos para agregar comentario.']);
                exit;
            }

            $result = $controller->agregarComentario($machoteId, $usuarioId, $clausula, $comentario);
            echo json_encode($result);
            break;

        // -----------------------------------------------------------
        // 🔹 MARCAR COMO RESUELTO
        // -----------------------------------------------------------
        case 'resuelto':
            $comentarioId = filter_input(INPUT_POST, 'comentario_id', FILTER_VALIDATE_INT);
            if (!$comentarioId) {
                echo json_encode(['success' => false, 'error' => 'ID de comentario inválido.']);
                exit;
            }

            $result = $controller->marcarResuelto($comentarioId);
            echo json_encode($result);
            break;

        // -----------------------------------------------------------
        // 🔹 ELIMINAR COMENTARIO
        // -----------------------------------------------------------
        case 'eliminar':
            $comentarioId = filter_input(INPUT_POST, 'comentario_id', FILTER_VALIDATE_INT);
            if (!$comentarioId) {
                echo json_encode(['success' => false, 'error' => 'ID de comentario inválido.']);
                exit;
            }

            $result = $controller->eliminarComentario($comentarioId);
            echo json_encode($result);
            break;

        // -----------------------------------------------------------
        // 🔹 CONTAR PENDIENTES
        // -----------------------------------------------------------
        case 'contar':
            $machoteId = filter_input(INPUT_GET, 'machote_id', FILTER_VALIDATE_INT);
            if (!$machoteId) {
                echo json_encode(['success' => false, 'error' => 'ID de machote inválido.']);
                exit;
            }

            $result = $controller->contarPendientes($machoteId);
            echo json_encode($result);
            break;

        // -----------------------------------------------------------
        // 🚫 ACCIÓN DESCONOCIDA
        // -----------------------------------------------------------
        default:
            echo json_encode(['success' => false, 'error' => 'Acción no reconocida.']);
            break;
    }
} catch (Throwable $e) {
    error_log('Error en machote_comentario_handler.php: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Error interno del servidor.']);
}
