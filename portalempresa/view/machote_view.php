<?php
declare(strict_types=1);

require_once __DIR__ . '/../helpers/machote_view_helper.php';
require_once __DIR__ . '/../handler/machote_view_handler.php';

$stats = machoteViewNormalizeStats($stats ?? null);
$permisos = machoteViewNormalizePermisos($permisos ?? null);
$documento = machoteViewNormalizeDocumento($documento ?? null);
$machote = is_array($machote ?? null) ? $machote : [];
$comentarios = is_array($comentarios ?? null) ? $comentarios : [];
$empresaNombre = isset($empresaNombre) ? (string) $empresaNombre : 'Empresa';
$estatus = (string) ($stats['estado'] ?? 'En revisión');
$comentAbiertos = (int) ($stats['pendientes'] ?? 0);
$comentResueltos = (int) ($stats['resueltos'] ?? 0);
$avancePct = max(0, min(100, (int) ($stats['progreso'] ?? 0)));
$confirmado = (bool) ($machote['confirmado'] ?? false);
$versionMachote = (string) ($machote['version_local'] ?? 'v1.0');
$machoteId = (int) ($machoteActualId ?? ($machote['id'] ?? 0));
$uploadsBasePath = '../../uploads/';

$flashMessages = machoteViewBuildFlashMessages($_GET ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa - Revisión del Acuerdo</title>
  <link rel="stylesheet" href="../assets/css/modules/machoteview.css">
  

</head>
<body>
<?php include __DIR__ . '/../layout/portal_empresa_header.php'; ?>


<main class="layout">

  <!-- Columna izquierda -->
  <section class="left">

    <?php if (!empty($viewError)): ?>
      <div class="card">
        <header>Error</header>
        <div class="content">
          <p class="warn">Ups, <?= htmlspecialchars($viewError) ?></p>
        </div>
      </div>
    <?php endif; ?>

    <?php foreach ($flashMessages as $flash): ?>
      <div class="card flash <?= htmlspecialchars($flash['type']) ?>">
        <div class="content">
          <?= htmlspecialchars($flash['text']) ?>
        </div>
      </div>
    <?php endforeach; ?>

    <!-- Estado general -->
    <div class="card">
      <header>Estado general</header>
      <div class="content kpis">
        <div class="metric"><div class="num"><?= $comentAbiertos ?></div><div class="lbl">Pendientes</div></div>
        <div class="metric"><div class="num"><?= $comentResueltos ?></div><div class="lbl">Atendidos</div></div>
        <div class="metric"><div class="num"><?= $avancePct ?>%</div><div class="lbl">Avance</div></div>
        <div class="metric status">
          <?php $estatusLower = function_exists('mb_strtolower') ? mb_strtolower($estatus, 'UTF-8') : strtolower($estatus); ?>
          <?php if ($estatusLower === 'aprobado'): ?>
            <span class="badge ok">Aprobado</span>
          <?php elseif ($estatusLower === 'con observaciones'): ?>
            <span class="badge warn">Con observaciones</span>
          <?php else: ?>
            <span class="badge warn"><?= htmlspecialchars($estatus) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="progressbox">
        <div class="progress"><div class="bar" style="width:<?= $avancePct ?>%"></div></div>
      </div>
    </div>

    <!-- Documento -->
    <div class="card doc-card">
      <header>Documento a revisar - <?= htmlspecialchars($versionMachote) ?></header>
      <div class="content">
        <div class="doc-shell">
          <?php if ($documento['has_pdf'] && !empty($documento['pdf_embed_url'])): ?>
            <div class="doc-surface doc-surface-pdf">
              <iframe
                src="<?= htmlspecialchars((string) $documento['pdf_embed_url']) ?>"
                title="Machote PDF"
                class="doc-iframe"
              ></iframe>
            </div>

            <div class="doc-toolbar">
              <a class="btn" href="<?= htmlspecialchars((string) $documento['pdf_url']) ?>" target="_blank">Ver en pestaña</a>
              <a class="btn" download href="<?= htmlspecialchars((string) $documento['pdf_url']) ?>">Descargar PDF</a>
            </div>

            <?php if (!empty($documento['fuente'])): ?>
              <p class="doc-note">Fuente: <?= htmlspecialchars((string) $documento['fuente']) ?></p>
            <?php endif; ?>

          <?php elseif ($documento['has_html'] && !empty($documento['html'])): ?>
            <div class="doc-surface doc-surface-html">
              <div class="doc-html">
                <?= $documento['html'] ?>
              </div>
            </div>
            <p class="doc-note">
              Mostrando versión editable actual registrada por Vinculación.
            </p>

          <?php else: ?>
            <div class="doc-empty">
              <p>
                No hay documento disponible para mostrar.
                Es posible que Vinculación aún no haya generado el machote hijo.
              </p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <!-- Confirmación -->
    <div class="card">
      <header>Confirmación de Empresa</header>
      <div class="content approval">
        <form action="../handler/machote_confirm_handler.php" method="post">
          <input type="hidden" name="machote_id" value="<?= $machoteId ?>">
          <label class="switch">
            <input type="checkbox" name="confirmacion_empresa" value="1" <?= $confirmado ? 'checked disabled' : '' ?>>
            <span class="slider"></span>
            <span class="label">Estoy de acuerdo con el contenido del documento</span>
          </label>
          <?php if ($confirmado): ?>
            <p class="ok-note">Listo. Confirmación registrada. Si necesitas más ajustes, contacta a Vinculación para reabrir la revisión.</p>
          <?php else: ?>
            <div class="actions">
              <button type="submit" class="btn primary" <?= $permisos['puede_confirmar'] ? '' : 'disabled' ?>>Guardar confirmación</button>
            </div>
            <?php if (!$permisos['puede_confirmar']): ?>
              <p class="note">Resuelve primero los comentarios pendientes para habilitar la confirmación.</p>
            <?php endif; ?>
          <?php endif; ?>
        </form>
      </div>
    </div>

  </section>

  <!-- Columna derecha -->
  <section class="right">

    <!-- Nuevo comentario -->
    <div class="card">
      <header>Agregar comentario</header>
      <div class="content">
        <?php if ($permisos['puede_comentar']): ?>
        <form action="../handler/machote_comentario_add_handler.php" method="post">
          <input type="hidden" name="machote_id" value="<?= $machoteId ?>">
          <div class="field">
            <label for="asunto">Tema</label>
            <input type="text" id="asunto" name="asunto" required>
          </div>
          <div class="field">
            <label for="comentario">Descripción</label>
            <textarea id="comentario" name="comentario" rows="3" required></textarea>
          </div>
          <div class="actions">
            <button class="btn primary">Publicar comentario</button>
          </div>
        </form>
        <?php else: ?>
          <p class="note">Los comentarios están disponibles solo mientras el convenio está en revisión.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Lista de comentarios -->
    <div class="card">
      <header>Comentarios recientes</header>
      <div class="content threads">
        <?php if (count($comentarios) === 0): ?>
          <p class="empty">Aún no hay comentarios registrados.</p>
        <?php else: ?>
          <?php foreach ($comentarios as $comentario): ?>
            <?php
              $isAbierto = (($comentario['estatus'] ?? 'pendiente') === 'pendiente');
              $clausula = trim((string) ($comentario['clausula'] ?? ''));
              $creadoEn = (string) ($comentario['creado_en'] ?? '');
            ?>
            <article class="thread thread-detail">
              <div class="meta">
                <span class="badge <?= $isAbierto ? 'abierto' : 'resuelto' ?>">
                  <?= $isAbierto ? 'Abierto' : 'Resuelto' ?>
                </span>
                <span class="author <?= $comentario['autor_rol'] === 'admin' ? 'admin' : 'empresa' ?>">
                  <?= htmlspecialchars($comentario['autor_nombre'] ?? '') ?>
                </span>
                <?php if ($clausula !== ''): ?>
                  <span>- <?= htmlspecialchars($clausula) ?></span>
                <?php endif; ?>
                <?php if ($creadoEn !== ''): ?>
                  <span>- <?= htmlspecialchars($creadoEn) ?></span>
                <?php endif; ?>
              </div>

              <div class="messages conversation">
                <?php machoteViewRenderThreadMessage($comentario, $uploadsBasePath); ?>
              </div>

              <?php if ($permisos['puede_comentar'] && $isAbierto): ?>
                <form class="reply" action="../handler/machote_reply_handler.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="machote_id" value="<?= $machoteId ?>">
                  <input type="hidden" name="respuesta_a" value="<?= (int) ($comentario['id'] ?? 0) ?>">
                  <textarea name="comentario" rows="3" placeholder="Responder..." required></textarea>
                  <div class="row">
                    <input type="file" name="archivo">
                    <button class="btn primary" type="submit">Enviar</button>
                  </div>
                </form>
              <?php elseif (!$isAbierto): ?>
                <p class="note">Este comentario esta resuelto; las respuestas estan cerradas.</p>
              <?php else: ?>
                <p class="note">Las respuestas se encuentran cerradas.</p>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </section>
</main>

<footer class="portal-foot">
  <small>Portal de Empresa - Área de Vinculación</small>
</footer>
</body>
</html>
