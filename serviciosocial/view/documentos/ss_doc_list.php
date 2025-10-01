<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosController;

require_once __DIR__ . '/../../controller/DocumentosController.php';

$searchTerm = isset($_GET['q']) ? (string) $_GET['q'] : '';
$estatusFilter = isset($_GET['estatus']) ? (string) $_GET['estatus'] : '';

try {
    $controller = new DocumentosController();
    $documents = $controller->list($searchTerm, $estatusFilter);
    $errorMessage = null;
} catch (\Throwable $exception) {
    $documents = [];
    $errorMessage = 'Ocurrió un error al cargar los documentos: ' . $exception->getMessage();
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
  <title>Gestión de Documentos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Gestión de Documentos</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Documentos</span>
    </nav>
  </header>

  <main>

    <!-- 🔍 Barra de búsqueda -->
    <div class="search-bar">
      <form action="" method="get" style="display:flex; gap:10px; align-items:center;">
        <input type="text" name="q" placeholder="Buscar por estudiante o tipo..." value="<?= e($searchTerm) ?>" />
        <select name="estatus">
          <option value="">-- Filtrar por estado --</option>
          <option value="pendiente" <?= $estatusFilter === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
          <option value="aprobado" <?= $estatusFilter === 'aprobado' ? 'selected' : '' ?>>Aprobado</option>
          <option value="rechazado" <?= $estatusFilter === 'rechazado' ? 'selected' : '' ?>>Rechazado</option>
        </select>
        <button type="submit" class="btn btn-primary">🔎 Buscar</button>
      </form>

      <a href="ss_doc_add.php" class="btn btn-success">📤 Subir nuevo documento</a>
    </div>

    <!-- 📋 Lista de documentos -->
    <section class="card">
      <h2>Documentos Recibidos</h2>
      <p>Consulta todos los documentos subidos por los estudiantes o administradores del Servicio Social.</p>

      <table class="styled-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Estudiante</th>
            <th>Tipo de Documento</th>
            <th>Periodo</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
<?php if ($errorMessage !== null): ?>
          <tr>
            <td colspan="7" class="text-center" style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></td>
          </tr>
<?php elseif ($documents === []): ?>
          <tr>
            <td colspan="7" class="text-center">No se encontraron documentos con los filtros seleccionados.</td>
          </tr>
<?php else: ?>
<?php foreach ($documents as $document): ?>
<?php
    $estudiante = $document['estudiante']['nombre'] ?? 'Estudiante sin nombre';
    $matricula = $document['estudiante']['matricula'] ?? '';
    $tipoNombre = $document['tipo']['nombre'] ?? 'Tipo desconocido';
    $periodoNumero = $document['periodo']['numero'] ?? null;
    $periodoLabel = $periodoNumero !== null ? 'Periodo ' . $periodoNumero : 'Sin periodo';
    $fecha = $document['actualizado_en'] ?? '';
?>
          <tr>
            <td><?= e((string) $document['id']) ?></td>
            <td>
              <?= e($estudiante) ?>
<?php if ($matricula !== ''): ?>
              <div class="muted">Matrícula: <?= e($matricula) ?></div>
<?php endif; ?>
            </td>
            <td><?= e($tipoNombre) ?></td>
            <td><?= e($periodoLabel) ?></td>
            <td><span class="<?= e(statusClass($document)) ?>"><?= e(statusLabel($document)) ?></span></td>
            <td><?= e($fecha) ?></td>
            <td>
              <a href="ss_doc_view.php?id=<?= e((string) $document['id']) ?>" class="btn btn-primary btn-sm">👁️ Ver</a>
              <a href="ss_doc_edit.php?id=<?= e((string) $document['id']) ?>" class="btn btn-secondary btn-sm">✏️ Editar</a>
              <a href="ss_doc_delete.php?id=<?= e((string) $document['id']) ?>" class="btn btn-danger btn-sm">🗑️ Eliminar</a>
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
