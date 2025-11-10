<?php
declare(strict_types=1);

require_once __DIR__ . '/../../model/Conexion.php';
require_once __DIR__ . '/../../model/MachoteGlobalModel.php';
require_once __DIR__ . '/../../model/ConvenioMachoteModel.php';

$empresaId  = $_GET['empresa_id'] ?? null;
$convenioId = $_GET['convenio_id'] ?? null;

if (!$empresaId || !$convenioId) {
    die('Faltan parámetros.');
}

// Obtener el machote global más reciente
$global = MachoteGlobalModel::getLatest();
if (!$global) {
    die('No existe machote global registrado.');
}

// Crear un machote hijo para el convenio
$newId = ConvenioMachoteModel::create([
    'convenio_id'      => (int)$convenioId,
    'machote_padre_id' => (int)$global['id'],
    'contenido_html'   => $global['contenido_html'],
    'version_local'    => 'v1.0',
]);

// Vincular el machote hijo al convenio
$pdo = Conexion::getConexion();
$stmt = $pdo->prepare("UPDATE rp_convenio SET machote_id = :machote_id WHERE id = :id");
$stmt->execute([
    ':machote_id' => $newId,
    ':id' => $convenioId
]);

// Redirigir al editor
header("Location: ../../view/machote/machote_edit.php?id=$newId");
exit;
