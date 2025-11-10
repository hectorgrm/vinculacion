<?php
declare(strict_types=1);

require_once __DIR__ . '/../../model/Conexion.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $contenido = $_POST['contenido'] ?? '';

    if ($id <= 0) {
        die('ID inválido.');
    }

    $guardado = ConvenioMachoteModel::updateContent($id, $contenido);

    if ($guardado) {
        // Redirigir de nuevo al editor con mensaje de éxito
        header("Location: ../../view/machote/machote_edit.php?id=$id&guardado=1");
        exit;
    } else {
        die('Error al guardar los cambios.');
    }
}
