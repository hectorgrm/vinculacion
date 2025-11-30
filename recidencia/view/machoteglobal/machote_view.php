<?php
declare(strict_types=1);

use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

require_once __DIR__ . '/../../common/helpers/machoteglobal_helper.php';

if (!isset($machote)) {
  require_once __DIR__ . '/../../controller/machoteglobal/MachoteGlobalController.php';

  $controller = new MachoteGlobalController();
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;

  $viewerError = $viewerError ?? null;

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
  <link rel="stylesheet" href="../../assets/css/modules/machoteglobal/machoteglobalview.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main machote-view">
      <header class="hero">
        <div class="hero__text">
          <p class="eyebrow">Machote global</p>
          <div class="hero__headline">
            <h1><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></h1>
            <?php if ($viewerError === null && $machote !== null): ?>
              <span class="<?= htmlspecialchars($estadoBadge, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?>
              </span>
            <?php endif; ?>
          </div>
          <p class="subtitle">
            Consulta el contenido oficial del machote, su versión y metadatos clave. Imprime o exporta a PDF según sea necesario.
          </p>
          <?php if ($viewerError === null && $machote !== null): ?>
            <div class="hero__chips">
              <div class="chip">
                <span class="chip-label">Versión</span>
                <span class="chip-value"><?= htmlspecialchars((string) $version, ENT_QUOTES, 'UTF-8') ?: '—'; ?></span>
              </div>
              <div class="chip">
                <span class="chip-label">Creado</span>
                <span class="chip-value"><?= htmlspecialchars($creado, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <div class="chip">
                <span class="chip-label">Actualizado</span>
                <span class="chip-value"><?= htmlspecialchars($actualizado, ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="hero__actions">
          <a href="../../view/machoteglobal/machote_global_list.php" class="btn secondary">&larr; Volver al listado</a>
          <?php if ($viewerError === null && $machote !== null): ?>
            <a href="../../view/machoteglobal/machote_edit.php?id=<?= (int) $machote['id'] ?>" class="btn primary">Editar</a>
            <button type="button" class="btn" onclick="window.print()">Imprimir</button>
            <a href="../../handler/machoteglobal/machote_export_pdf_handler.php?id=<?= (int) $machote['id'] ?>" class="btn primary" target="_blank" rel="noopener">Exportar PDF</a>
          <?php endif; ?>
        </div>
      </header>

      <section class="card">
        <header>Detalle</header>
        <div class="content meta-grid">
          <?php if ($viewerError !== null): ?>
            <div class="alert alert-error"><?= htmlspecialchars($viewerError, ENT_QUOTES, 'UTF-8') ?></div>
          <?php elseif ($machote === null): ?>
            <div class="alert alert-error">No hay información para mostrar.</div>
          <?php else: ?>
            <div class="meta-item">
              <p class="meta-label">Versión</p>
              <p class="meta-value"><?= htmlspecialchars((string) $version, ENT_QUOTES, 'UTF-8') ?: '—'; ?></p>
            </div>
            <div class="meta-item">
              <p class="meta-label">Estado</p>
              <p class="meta-value"><?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="meta-item">
              <p class="meta-label">Descripción</p>
              <p class="meta-value"><?= $descripcion !== '' ? nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8')) : 'Sin descripción.'; ?></p>
            </div>
            <div class="meta-item">
              <p class="meta-label">Creado</p>
              <p class="meta-value"><?= htmlspecialchars($creado, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
            <div class="meta-item">
              <p class="meta-label">Actualizado</p>
              <p class="meta-value"><?= htmlspecialchars($actualizado, ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <?php if ($viewerError === null && $machote !== null): ?>
        <section class="card">
          <header>Contenido del machote</header>
          <div class="content viewer-card">
            <div class="viewer-content">
              <?= $contenidoHtml !== '' ? $contenidoHtml : '<em>Sin contenido.</em>' ?>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>

</html>
