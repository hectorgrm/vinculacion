<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../common/helpers/machoteglobal_helper.php';

if (!isset($machote)) {
  require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';

  $controller = new MachoteGlobalController();
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

  if (!isset($viewerError)) {
    $viewerError = null;
  }

  if ($id <= 0) {
    $viewerError = $viewerError ?? 'ID de machote no válido.';
  } else {
    $machote = $controller->obtenerMachote($id);
    if ($machote === null) {
      $viewerError = $viewerError ?? 'Machote no encontrado.';
    }
  }
}

/** @var array<string, mixed>|null $machote */
/** @var string|null $viewerError */
$machote ??= null;
$viewerError ??= null;

$version = $machote['version'] ?? null;
$descripcion = trim((string) ($machote['descripcion'] ?? ''));
$estado = (string) ($machote['estado'] ?? 'borrador');
$estadoBadge = machote_global_estado_badge($estado);
$estadoLabel = machote_global_estado_label($estado);
$contenidoHtml = $machote['contenido_html'] ?? '';
$creado = machote_global_format_datetime($machote['creado_en'] ?? null);
$actualizado = machote_global_format_datetime($machote['actualizado_en'] ?? null);

$pageTitle = 'Vista del Machote';
if ($version !== null && $version !== '') {
  $pageTitle .= ' · ' . $version;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="stylesheet" href="../../templates/machote_oficial_v1_content.css" media="print" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/machoteglobal.css" />

</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="viewer-header">
        <div>
          <h2><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h2>
          <?php if ($viewerError === null && $machote !== null): ?>
            <p class="subtitle">
              Versión: <strong><?= htmlspecialchars((string) $version, ENT_QUOTES, 'UTF-8') ?: '—'; ?></strong>
              · Estado:
              <span class="<?= htmlspecialchars($estadoBadge, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?>
              </span>
            </p>
            <ul class="viewer-meta">
              <li><strong>Descripción:</strong>
                <?= $descripcion !== '' ? nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8')) : '<em>Sin descripción.</em>'; ?>
              </li>
              <li><strong>Creado:</strong> <?= htmlspecialchars($creado, ENT_QUOTES, 'UTF-8'); ?></li>
              <li><strong>Actualizado:</strong> <?= htmlspecialchars($actualizado, ENT_QUOTES, 'UTF-8'); ?></li>
            </ul>
          <?php endif; ?>
        </div>
        <div class="actions" style="display:flex;gap:8px;flex-wrap:wrap;">
          <a href="../../view/machoteglobal/machote_global_list.php" class="btn">← Volver al listado</a>
          <?php if ($viewerError === null && $machote !== null): ?>
            <a href="../../view/machoteglobal/machote_edit.php?id=<?= (int) $machote['id'] ?>" class="btn primary">✏️
              Editar</a>
            <button type="button" class="btn print" onclick="window.print()">🖨️ Imprimir</button>
            <a href="../../handler/machoteglobal/machote_export_pdf_handler.php?id=<?= (int) $machote['id'] ?>"
              class="btn primary" target="_blank" rel="noopener">📄 Exportar a PDF</a>
          <?php endif; ?>
        </div>
      </header>

      <section class="viewer-card">
        <?php if ($viewerError !== null): ?>
          <p class="viewer-error"><?= htmlspecialchars($viewerError, ENT_QUOTES, 'UTF-8') ?></p>
        <?php elseif ($machote === null): ?>
          <p class="viewer-error">No hay información para mostrar.</p>
        <?php else: ?>
          <div class="viewer-content">
            <?= $contenidoHtml !== '' ? $contenidoHtml : '<em>Sin contenido.</em>' ?>
          </div>
        <?php endif; ?>
      </section>
    </main>
  </div>
</body>

</html>