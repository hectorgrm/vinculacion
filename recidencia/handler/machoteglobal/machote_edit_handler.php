<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

// Cargar el controlador
require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';

$controller = new MachoteGlobalController();
$machotesListado = $controller->listarMachotes();

// Inicializar variables
$machote = null;
$error = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ✅ Si hay ID, obtener datos del machote existente
if ($id > 0) {
    $machote = $controller->obtenerMachote($id);
}

// ✅ Si se envió el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => isset($_POST['id']) && $_POST['id'] !== '' ? (int)$_POST['id'] : null,
        'version' => trim($_POST['version'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'estado' => $_POST['estado'] ?? 'borrador',
        'contenido_html' => $_POST['contenido_html'] ?? ''
    ];

    $allowedStates = ['vigente', 'borrador', 'archivado'];
    if (!in_array($data['estado'], $allowedStates, true)) {
        $data['estado'] = 'borrador';
    }

    $validationErrors = [];
    if ($data['version'] === '') {
        $validationErrors[] = 'La versión es obligatoria.';
    }

    if (empty($validationErrors)) {
        if ($controller->guardarMachote($data)) {
            header('Location: /recidencia/view/machoteglobal/machote_global_list.php');
            exit;
        }

        $error = '❌ Error al guardar el machote. Intenta nuevamente.';
    } else {
        $error = '❌ ' . implode(' ', $validationErrors);
    }

    // Mantener los valores capturados en el formulario en caso de error
    $machote = $data;
    if (!empty($data['id'])) {
        $id = (int)$data['id'];
    }
}

// Cargar la vista
include __DIR__ . '/../../view/machoteglobal/machote_edit.php';
