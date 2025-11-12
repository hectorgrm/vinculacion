<?php
require_once __DIR__ . '/../../../controller/machote/MachoteViewController.php';

use PortalEmpresa\Controller\Machote\MachoteViewController;

try {
    // 1️⃣ Obtener el ID
    $machoteId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

    // 2️⃣ Inicializar controlador
    $controller = new MachoteViewController();

    // 3️⃣ Obtener datos
    $data = $controller->handleView($machoteId);

    // 4️⃣ Pasar a vista
    extract($data); // crea variables $empresa, $machote, $comentarios...
    require __DIR__ . '/../../../view/machote_view.php';

} catch (Throwable $e) {
    echo "<h2>Error al cargar el machote: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
