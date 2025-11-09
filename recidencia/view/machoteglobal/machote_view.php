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
$descripcion = trim((string)($machote['descripcion'] ?? ''));
$estado = (string)($machote['estado'] ?? 'borrador');
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <style>
    body{background:#f6f7fb;color:#0f172a;}
    .viewer-card{
      background:#fff;
      border:1px solid #e2e8f0;
      border-radius:14px;
      padding:25px;
      margin-top:20px;
      box-shadow:0 5px 15px rgba(15,23,42,0.06);
    }
    .viewer-header{
      display:flex;
      justify-content:space-between;
      align-items:center;
      flex-wrap:wrap;
      gap:12px;
      padding:18px 22px;
      background:#fff;
      border:1px solid #e2e8f0;
      border-radius:14px;
      margin-bottom:12px;
    }
    .viewer-meta{margin:12px 0 0 0;padding:0;list-style:none;color:#475569;font-size:0.95rem;}
    .viewer-meta li{margin-bottom:4px;}
    .viewer-content{
      margin-top:24px;
      padding:32px;
      background:#f8fafc;
      border-radius:12px;
      min-height:60vh;
      overflow:auto;
    }
    .viewer-content :where(p,li){font-size:1rem;line-height:1.65;color:#1e293b;}
    .badge{border-radius:999px;padding:4px 12px;font-weight:600;font-size:0.85rem;display:inline-flex;align-items:center;gap:4px;}
    .badge.vigente{background:#dcfce7;color:#166534;}
    .badge.borrador{background:#fef9c3;color:#92400e;}
    .badge.archivado{background:#fee2e2;color:#991b1b;}
    .btn{background:#e2e8f0;color:#0f172a;padding:8px 16px;border-radius:9px;text-decoration:none;border:none;cursor:pointer;font-weight:600;display:inline-flex;align-items:center;gap:6px;}
    .btn.primary{background:#0d6efd;color:#fff;}
    .btn.print{background:#10b981;color:#fff;}
    .btn:focus-visible{outline:3px solid rgba(13,110,253,.35);outline-offset:2px;}
    .viewer-error{
      padding:18px;
      border-radius:10px;
      background:#fef2f2;
      border:1px solid #fecaca;
      color:#991b1b;
      font-weight:600;
    }
  </style>
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
              Versión: <strong><?= htmlspecialchars((string)$version, ENT_QUOTES, 'UTF-8') ?: '—'; ?></strong>
              · Estado:
              <span class="<?= htmlspecialchars($estadoBadge, ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($estadoLabel, ENT_QUOTES, 'UTF-8') ?>
              </span>
            </p>
            <ul class="viewer-meta">
              <li><strong>Descripción:</strong> <?= $descripcion !== '' ? nl2br(htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8')) : '<em>Sin descripción.</em>'; ?></li>
              <li><strong>Creado:</strong> <?= htmlspecialchars($creado, ENT_QUOTES, 'UTF-8'); ?></li>
              <li><strong>Actualizado:</strong> <?= htmlspecialchars($actualizado, ENT_QUOTES, 'UTF-8'); ?></li>
            </ul>
          <?php endif; ?>
        </div>
        <div class="actions" style="display:flex;gap:8px;flex-wrap:wrap;">
          <a href="../../view/machoteglobal/machote_global_list.php" class="btn">← Volver al listado</a>
          <?php if ($viewerError === null && $machote !== null): ?>
            <a href="../../view/machoteglobal/machote_edit.php?id=<?= (int)$machote['id'] ?>" class="btn primary">✏️ Editar</a>
            <button type="button" class="btn print" onclick="window.print()">🖨️ Imprimir</button>
            <a
              href="../../handler/machoteglobal/machote_export_pdf_handler.php?id=<?= (int)$machote['id'] ?>"
              class="btn primary"
              target="_blank"
              rel="noopener"
            >📄 Exportar a PDF</a>
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
