<?php
// Vista de detalle de machote (sin handler dedicado aún).
$machoteId = isset($_GET['id']) ? (int) $_GET['id'] : 1;

// Datos de ejemplo (mantén/ sustituye por datos reales cuando se integre handler).
$machote = [
    'nombre' => 'Machote Institucional Base',
    'version' => 'v1.2',
    'empresa' => 'Casa del Barrio',
    'empresa_id' => 45,
    'estatus_badge' => '<span class="badge en_revision">En revisión</span>',
    'creado' => '2025-09-28',
    'actualizado' => '2025-10-08',
    'archivo' => 'machote_v12_base.pdf',
    'revision_id' => 123,
    'comentarios_abiertos' => 1,
    'avance' => '75%',
    'revision_estado' => '<span class="badge en_revision">En revisión</span>',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Machote #<?= htmlspecialchars((string) $machoteId, ENT_QUOTES, 'UTF-8'); ?> | Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/css/modules/machote/machoteview.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main machote-view">

      <header class="hero">
        <div class="hero__text">
          <p class="eyebrow">Plantillas y revisiones</p>
          <div class="hero__headline">
            <h1>Machote #<?= htmlspecialchars((string) $machoteId, ENT_QUOTES, 'UTF-8'); ?></h1>
            <?= $machote['estatus_badge']; ?>
          </div>
          <p class="subtitle">
            Detalle completo del machote institucional, archivo original y estado de la revisión activa con la empresa.
          </p>
          <div class="hero__chips">
            <div class="chip">
              <span class="chip-label">Versión</span>
              <span class="chip-value"><?= htmlspecialchars($machote['version'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="chip">
              <span class="chip-label">Actualizado</span>
              <span class="chip-value"><?= htmlspecialchars($machote['actualizado'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="chip">
              <span class="chip-label">Empresa</span>
              <span class="chip-value"><?= htmlspecialchars($machote['empresa'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
          </div>
        </div>
        <div class="hero__actions">
          <a href="machote_edit.php?id=<?= urlencode((string) $machoteId); ?>" class="btn primary">Editar machote</a>
          <a href="machote_list.php" class="btn secondary">Volver</a>
        </div>
      </header>

      <section class="card">
        <header>Información general</header>
        <div class="content meta-grid">
          <div class="meta-item">
            <p class="meta-label">Nombre</p>
            <p class="meta-value"><?= htmlspecialchars($machote['nombre'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="meta-item">
            <p class="meta-label">Versión</p>
            <p class="meta-value"><?= htmlspecialchars($machote['version'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="meta-item">
            <p class="meta-label">Empresa asociada</p>
            <p class="meta-value">
              <a href="../empresa/empresa_view.php?id=<?= urlencode((string) $machote['empresa_id']); ?>" class="btn small">Ver empresa</a>
              <span class="meta-hint"><?= htmlspecialchars($machote['empresa'], ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
          </div>
          <div class="meta-item">
            <p class="meta-label">Fecha de creación</p>
            <p class="meta-value"><?= htmlspecialchars($machote['creado'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="meta-item">
            <p class="meta-label">Última actualización</p>
            <p class="meta-value"><?= htmlspecialchars($machote['actualizado'], ENT_QUOTES, 'UTF-8'); ?></p>
          </div>
          <div class="meta-item">
            <p class="meta-label">Estatus</p>
            <p class="meta-value"><?= $machote['estatus_badge']; ?></p>
          </div>
        </div>
      </section>

      <section class="card">
        <header>Archivo del machote</header>
        <div class="content file-card">
          <div class="file-info">
            <p class="file-title">Archivo original</p>
            <p class="file-meta">PDF o DOCX · Máx. 10 MB</p>
          </div>
          <ul class="file-list">
            <li>
              <a href="../../uploads/<?= htmlspecialchars($machote['archivo'], ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="file-link">
                Archivo: <?= htmlspecialchars($machote['archivo'], ENT_QUOTES, 'UTF-8'); ?>
              </a>
            </li>
          </ul>
        </div>
      </section>

      <section class="card">
        <header>Revisión activa</header>
        <div class="content review-card">
          <p class="text-muted">Existe una revisión asociada a este machote.</p>
          <div class="stat-grid">
            <div class="stat">
              <span class="stat-label">ID Revisión</span>
              <span class="stat-value">#<?= htmlspecialchars((string) $machote['revision_id'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Empresa</span>
              <span class="stat-value"><?= htmlspecialchars($machote['empresa'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Comentarios abiertos</span>
              <span class="stat-value"><?= htmlspecialchars((string) $machote['comentarios_abiertos'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <div class="stat">
              <span class="stat-label">Avance</span>
              <span class="stat-value"><?= htmlspecialchars($machote['avance'], ENT_QUOTES, 'UTF-8'); ?></span>
              <div class="progress-track">
                <div class="progress-fill" style="width: <?= htmlspecialchars($machote['avance'], ENT_QUOTES, 'UTF-8'); ?>;"></div>
              </div>
            </div>
            <div class="stat">
              <span class="stat-label">Estado</span>
              <span class="stat-value"><?= $machote['revision_estado']; ?></span>
            </div>
          </div>
          <div class="actions">
            <a href="machote_revisar.php?id=<?= urlencode((string) $machote['revision_id']); ?>" class="btn primary">Abrir revisión</a>
          </div>
        </div>
      </section>

      <section class="card">
        <header>Historial de cambios</header>
        <div class="content">
          <ul class="timeline">
            <li>
              <div class="dot"></div>
              <div>
                <strong>08/10/2025</strong> — Se inició la revisión con la empresa Casa del Barrio.
              </div>
            </li>
            <li>
              <div class="dot"></div>
              <div>
                <strong>01/10/2025</strong> — Se cargó la versión v1.2 del machote.
              </div>
            </li>
            <li>
              <div class="dot"></div>
              <div>
                <strong>25/09/2025</strong> — Versión anterior v1.1 archivada.
              </div>
            </li>
          </ul>
        </div>
      </section>

      <div class="action-bar">
        <a href="machote_edit.php?id=<?= urlencode((string) $machoteId); ?>" class="btn primary">Editar machote</a>
        <a href="machote_delete.php?id=<?= urlencode((string) $machoteId); ?>" class="btn danger" onclick="return confirm('¿Eliminar este machote?');">Eliminar machote</a>
      </div>

    </main>
  </div>
</body>
</html>
