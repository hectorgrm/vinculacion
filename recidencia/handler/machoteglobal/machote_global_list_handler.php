<?php
declare(strict_types=1);

// ===============================================
// 🧭 HANDLER: Machote Global List
// -----------------------------------------------
// Este archivo conecta la vista (machote_global_list.php)
// con el controlador MachoteGlobalController.php
// para mostrar todos los machotes institucionales.
// ===============================================

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

// Cargar el autoload o rutas necesarias
require_once __DIR__ . '/../../../controller/machoteglobal/MachoteGlobalController.php';

// Crear instancia del controlador
$controller = new MachoteGlobalController();

// Llamar al método para obtener los machotes
$machotes = $controller->listarMachotes();

// Puedes agregar aquí lógica adicional de filtrado (si usas GET):
// Ejemplo:
// $search = $_GET['search'] ?? '';
// $estado = $_GET['estado'] ?? '';
// if ($search !== '' || $estado !== '') {
//     $machotes = array_filter($machotes, function ($item) use ($search, $estado) {
//         return (stripos($item['version'], $search) !== false)
//             && ($estado === '' || $item['estado'] === $estado);
//     });
// }

// Cargar la vista y pasarle los datos
include __DIR__ . '/../../../view/machoteglobal/machote_global_list.php';
