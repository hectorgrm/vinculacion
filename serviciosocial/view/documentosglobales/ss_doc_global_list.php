<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosGlobalController;

require_once __DIR__ . '/../../controller/DocumentosGlobalController.php';

$searchTerm = isset($_GET['q']) ? (string) $_GET['q'] : '';
$estatusFilter = isset($_GET['estatus']) ? (string) $_GET['estatus'] : '';

try {
    $controller = new DocumentosGlobalController();
    $documents = $controller->list($searchTerm, $estatusFilter);
    $errorMessage = null;
} catch (\Throwable $exception) {
    $documents = [];
    $errorMessage = 'Ocurrió un error al cargar los documentos: ' . $exception->getMessage();
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function statusClass(string $estatus): string
{
    return match (strtolower($estatus)) {
        'inactivo' => 'status inactivo',
        default    => 'status activo',
    };
}

function statusLabel(string $estatus): string
{
    return match (strtolower($estatus)) {
        'inactivo' => 'Inactivo',
        default    => 'Activo',
    };
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Documentos Globales · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Documentos Globales</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Documentos Globales</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_add.php" class="btn btn-primary">➕ Subir Nuevo Documento Global</a>

    <section class="dg-card">
      <h2>Listado de Documentos Globales</h2>

      <div class="search-bar">
        <form action="" method="get">
          <input type="text" name="q" placeholder="Buscar por nombre o tipo..." value="<?= e($searchTerm) ?>" />
          <select name="estatus">
            <option value="">-- Filtrar por estado --</option>
            <option value="activo" <?= strtolower($estatusFilter) === 'activo' ? 'selected' : '' ?>>Activo</option>
            <option value="inactivo" <?= strtolower($estatusFilter) === 'inactivo' ? 'selected' : '' ?>>Inactivo</option>
          </select>
          <button type="submit" class="btn btn-secondary">🔍 Buscar</button>
        </form>
      </div>

      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tipo</th>
            <th>Nombre del Archivo</th>
            <th>Fecha de Subida</th>
            <th>Estatus</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
<?php if ($errorMessage !== null): ?>
          <tr>
            <td colspan="6" class="text-center" style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></td>
          </tr>
<?php elseif ($documents === []): ?>
          <tr>
            <td colspan="6" class="text-center">No se encontraron documentos globales con los filtros seleccionados.</td>
          </tr>
<?php else: ?>
<?php foreach ($documents as $document): ?>
<?php
    $tipoNombre = (string) ($document['tipo']['nombre'] ?? 'Tipo desconocido');
    $descripcion = (string) ($document['descripcion'] ?? '');
    $ruta = (string) ($document['ruta'] ?? '');
    $creadoEn = (string) ($document['creado_en'] ?? '');
    $estatus = (string) ($document['estatus'] ?? 'activo');
?>
          <tr>
            <td><?= e((string) $document['id']) ?></td>
            <td><span class="doc-type"><?= e($tipoNombre) ?></span></td>
            <td>
              <?= e((string) ($document['nombre'] ?? 'Documento sin nombre')) ?>
<?php if ($descripcion !== ''): ?>
              <div class="muted"><?= e($descripcion) ?></div>
<?php endif; ?>
            </td>
            <td><?= e($creadoEn) ?></td>
            <td><span class="<?= e(statusClass($estatus)) ?>"><?= e(statusLabel($estatus)) ?></span></td>
            <td>
<?php if ($ruta !== ''): ?>
              <a href="<?= e($ruta) ?>" class="file-link" target="_blank" rel="noopener noreferrer">📥 Descargar</a>
<?php else: ?>
              <span class="muted">Sin archivo</span>
<?php endif; ?>
              <a href="ss_doc_global_view.php?id=<?= e((string) $document['id']) ?>" class="btn btn-success">👁️ Ver</a>
              <a href="ss_doc_global_edit.php?id=<?= e((string) $document['id']) ?>" class="btn btn-primary">✏️ Editar</a>
              <a href="ss_doc_global_delete.php?id=<?= e((string) $document['id']) ?>" class="btn btn-danger">🗑️ Eliminar</a>
            </td>
          </tr>
<?php endforeach; ?>
<?php endif; ?>
        </tbody>
      </table>
    </section>
  </main>

</body>
</html>
