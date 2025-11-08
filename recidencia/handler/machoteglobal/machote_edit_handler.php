<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

// Cargar el controlador
require_once __DIR__ . '/../../../controller/machoteglobal/MachoteGlobalController.php';

$controller = new MachoteGlobalController();

// Inicializar variables
$machote = null;
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ✅ Si hay ID, obtener datos del machote existente
if ($id > 0) {
    $machote = $controller->obtenerMachote($id);
}

// ✅ Si se envió el formulario (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'id' => $_POST['id'] ?? null,
        'version' => trim($_POST['version'] ?? ''),
        'descripcion' => trim($_POST['descripcion'] ?? ''),
        'estado' => $_POST['estado'] ?? 'borrador',
        'contenido_html' => $_POST['contenido_html'] ?? ''
    ];

    if ($controller->guardarMachote($data)) {
        header('Location: machote_global_list_handler.php');
        exit;
    } else {
        $error = '❌ Error al guardar el machote. Intenta nuevamente.';
    }
}

// Cargar la vista
include __DIR__ . '/../../../view/machoteglobal/machote_edit.php';
