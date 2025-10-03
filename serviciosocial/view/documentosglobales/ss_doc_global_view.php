<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosGlobalController;

require_once __DIR__ . '/../../controller/DocumentosGlobalController.php';

/**
 * @param string|null $value
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function statusClass(?string $estatus): string
{
    return match (strtolower((string) $estatus)) {
        'inactivo' => 'status inactivo',
        default    => 'status activo',
    };
}

function statusLabel(?string $estatus): string
{
    return match (strtolower((string) $estatus)) {
        'inactivo' => 'Inactivo',
        default    => 'Activo',
    };
}

function formatDateTime(?string $value): string
{
    $value = $value !== null ? trim($value) : '';

    return $value === '' ? 'No disponible' : $value;
}

$documentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

try {
    if ($documentId <= 0) {
        throw new InvalidArgumentException('No se proporcionó un identificador de documento válido.');
    }

    $controller = new DocumentosGlobalController();
    $document = $controller->findOrFail($documentId);
    $errorMessage = null;
} catch (Throwable $exception) {
    $document = null;
    $errorMessage = $exception->getMessage();
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Detalle del Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="dg-card">
      <h2>⚠️ No se pudo cargar el documento</h2>
      <p class="error-message"><?= e($errorMessage) ?></p>
    </section>
<?php elseif ($document !== null): ?>
<?php
    $nombre = (string) ($document['nombre'] ?? 'Documento sin nombre');
    $descripcion = trim((string) ($document['descripcion'] ?? ''));
    $ruta = (string) ($document['ruta'] ?? '');
    $estatus = (string) ($document['estatus'] ?? 'activo');
    $tipoNombre = (string) ($document['tipo']['nombre'] ?? 'Sin tipo asignado');
    $tipoDescripcion = trim((string) ($document['tipo']['descripcion'] ?? ''));
    $creadoEn = formatDateTime($document['creado_en'] ?? null);
    $actualizadoEn = formatDateTime($document['actualizado_en'] ?? null);
?>
    <section class="dg-card">
      <h2>📄 Información del Documento</h2>

      <dl class="details-list">
        <dt>ID del Documento:</dt>
        <dd><?= e((string) $document['id']) ?></dd>

        <dt>Nombre:</dt>
        <dd><?= e($nombre) ?></dd>

        <dt>Tipo de Documento:</dt>
        <dd>
          <span class="doc-type"><?= e($tipoNombre) ?></span>
<?php if ($tipoDescripcion !== ''): ?>
          <div class="muted"><?= e($tipoDescripcion) ?></div>
<?php endif; ?>
        </dd>

        <dt>Descripción:</dt>
        <dd>
<?php if ($descripcion !== ''): ?>
          <?= nl2br(e($descripcion)) ?>
<?php else: ?>
          <span class="muted">Sin descripción proporcionada.</span>
<?php endif; ?>
        </dd>

        <dt>Estado:</dt>
        <dd><span class="<?= e(statusClass($estatus)) ?>"><?= e(statusLabel($estatus)) ?></span></dd>

        <dt>Fecha de creación:</dt>
        <dd><?= e($creadoEn) ?></dd>

        <dt>Última actualización:</dt>
        <dd><?= e($actualizadoEn) ?></dd>

        <dt>Archivo:</dt>
        <dd>
<?php if ($ruta !== ''): ?>
          <a href="<?= e($ruta) ?>" target="_blank" class="file-link" rel="noopener noreferrer">📥 Descargar archivo</a>
<?php else: ?>
          <span class="muted">No hay archivo disponible.</span>
<?php endif; ?>
        </dd>
      </dl>

      <div class="actions">
        <a href="ss_doc_global_edit.php?id=<?= e((string) $document['id']) ?>" class="btn btn-primary">✏️ Editar</a>
        <a href="ss_doc_global_delete.php?id=<?= e((string) $document['id']) ?>" class="btn btn-danger">🗑 Eliminar</a>
      </div>
    </section>
<?php endif; ?>
  </main>

</body>
</html>
