<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosController;

require_once __DIR__ . '/../../controller/DocumentosController.php';

$idParam = isset($_GET['id']) ? (string) $_GET['id'] : '';
$documentId = filter_var($idParam, FILTER_VALIDATE_INT);

$document = null;
$errorMessage = null;

if ($documentId === false || $documentId === null || $documentId <= 0) {
    $errorMessage = 'El identificador del documento es inválido.';
} else {
    try {
        $controller = new DocumentosController();
        $document = $controller->find((int) $documentId);

        if ($document === null) {
            $errorMessage = 'No se encontró el documento solicitado.';
        }
    } catch (\Throwable $exception) {
        $errorMessage = 'Ocurrió un error al cargar el documento: ' . $exception->getMessage();
    }
}

/**
 * @param string $value
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * @param array<string, mixed> $document
 */
function statusClass(array $document): string
{
    $status = strtolower((string) ($document['estatus'] ?? 'pendiente'));

    return match ($status) {
        'aprobado'  => 'status aprobado',
        'rechazado' => 'status rechazado',
        default     => 'status pendiente',
    };
}

/**
 * @param array<string, mixed> $document
 */
function statusLabel(array $document): string
{
    $status = strtolower((string) ($document['estatus'] ?? 'pendiente'));

    return match ($status) {
        'aprobado'  => 'Aprobado',
        'rechazado' => 'Rechazado',
        default     => 'Pendiente',
    };
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Detalle del Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Ver</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="card" style="margin-top:20px;">
      <p style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></p>
    </section>
<?php elseif ($document !== null): ?>
<?php
$estudianteNombre = $document['estudiante']['nombre'] ?? '';
$matricula = $document['estudiante']['matricula'] ?? '';
$tipoNombre = $document['tipo']['nombre'] ?? '';
$periodoNumero = $document['periodo']['numero'] ?? null;
$periodoLabel = $periodoNumero !== null ? 'Periodo ' . $periodoNumero : 'Sin periodo';
$actualizadoEn = $document['actualizado_en'] ?? '';
$observacion = $document['observacion'] ?? '';
$ruta = $document['ruta'] ?? '';
$recibido = isset($document['recibido']) ? (bool) $document['recibido'] : false;
?>
    <!-- 📄 Información del documento -->
    <section class="card">
      <h2>Información General</h2>

      <dl class="details-list">
        <dt>ID del Documento:</dt>
        <dd><?= e((string) ($document['id'] ?? '')) ?></dd>

        <dt>Tipo de Documento:</dt>
        <dd><?= e($tipoNombre !== '' ? $tipoNombre : 'Tipo desconocido') ?></dd>

        <dt>Estudiante:</dt>
        <dd>
          <?= e($estudianteNombre !== '' ? $estudianteNombre : 'Sin nombre') ?>
<?php if ($matricula !== ''): ?>
          <div class="muted">Matrícula: <?= e($matricula) ?></div>
<?php endif; ?>
        </dd>

        <dt>Periodo:</dt>
        <dd><?= e($periodoLabel) ?></dd>

        <dt>Última actualización:</dt>
        <dd><?= e($actualizadoEn) ?></dd>

        <dt>Recibido:</dt>
        <dd><?= $recibido ? 'Sí' : 'No' ?></dd>

        <dt>Estado:</dt>
        <dd><span class="<?= e(statusClass($document)) ?>"><?= e(statusLabel($document)) ?></span></dd>

        <dt>Archivo:</dt>
        <dd>
<?php if ($ruta !== null && $ruta !== ''): ?>
          <a href="<?= e($ruta) ?>" target="_blank" class="btn btn-primary">📄 Ver documento</a>
<?php else: ?>
          <span class="muted">Sin archivo adjunto</span>
<?php endif; ?>
        </dd>
      </dl>
    </section>

    <!-- 📝 Observaciones -->
    <section class="card">
      <h2>Observaciones</h2>
<?php if (trim((string) $observacion) !== ''): ?>
      <p><?= nl2br(e((string) $observacion)) ?></p>
<?php else: ?>
      <p class="muted">Sin observaciones registradas.</p>
<?php endif; ?>
    </section>

    <!-- ✅ Acciones -->
    <div class="actions">
      <a href="ss_doc_edit.php?id=<?= e((string) ($document['id'] ?? '')) ?>" class="btn btn-secondary">✏️ Editar Documento</a>
      <a href="ss_doc_delete.php?id=<?= e((string) ($document['id'] ?? '')) ?>" class="btn btn-danger">🗑️ Eliminar</a>
    </div>
<?php endif; ?>

  </main>

</body>
</html>
