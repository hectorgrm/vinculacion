<?php
declare(strict_types=1);

require_once __DIR__ . '/../../handler/machote/machote_revisar_handler.php';

// Variables esperadas desde el handler:
// $machote, $empresa, $convenio, $comentarios, $progreso, $estado, $errorMessage, $totales, $currentUser

// Fallbacks por si algo viene vacío (para evitar notices)
$empresaNombre   = isset($empresa['nombre']) ? (string) $empresa['nombre'] : '—';
$empresaId       = isset($empresa['id']) ? (int) $empresa['id'] : 0;
$machoteId       = isset($machote['id']) ? (int) $machote['id'] : (int) ($_GET['id'] ?? 0);
$versionLocal    = isset($machote['version_local']) ? (string) $machote['version_local'] : 'v1.0';
$contenidoHtml   = isset($machote['contenido_html']) ? (string) $machote['contenido_html'] : '';
$comentarios     = is_array($comentarios ?? null) ? $comentarios : [];
$progreso        = isset($progreso) ? max(0, min(100, (int) $progreso)) : 0;
$estado          = $estado ?? 'En revisión';
$totales         = is_array($totales ?? null) ? $totales : ['pendientes' => 0, 'resueltos' => 0, 'total' => 0, 'progreso' => 0, 'estado' => 'En revisión'];

$currentUser     = is_array($currentUser ?? null) ? $currentUser : [];
$currentUserId   = isset($currentUser['id']) ? (int) $currentUser['id'] : 0;
$currentUserName = isset($currentUser['name']) ? (string) $currentUser['name'] : '';
$uploadsBasePath = '../../uploads/';
$machoteConfirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;
$machoteBloqueado = $machoteConfirmado;
$comentariosBloqueados = $machoteBloqueado;

// KPIs rápidos
$comentAbiertos  = (int) ($totales['pendientes'] ?? 0);
$comentResueltos = (int) ($totales['resueltos'] ?? 0);

// Helpers de UI
function badgeEstado(string $estado): string {
  $map = [
    'Aprobado'          => '<span class="badge aprobado">Aprobado</span>',
    'Con observaciones' => '<span class="badge con_observaciones">Con observaciones</span>',
    'En revisión'       => '<span class="badge en_revision">En revisión</span>',
  ];
  return $map[$estado] ?? '<span class="badge en_revision">En revisión</span>';
}

if (!function_exists('renderMachoteThreadMessage')) {
    function renderMachoteThreadMessage(array $mensaje, string $uploadsBasePath): void {
        $autorRol = (string) ($mensaje['autor_rol'] ?? 'empresa');
        $autorNombre = (string) ($mensaje['autor_nombre'] ?? ucfirst($autorRol));
        $fecha = (string) ($mensaje['creado_en'] ?? '');
        $comentario = (string) ($mensaje['comentario'] ?? '');
        $archivoPath = $mensaje['archivo_path'] ?? null;
        $archivoHref = $archivoPath !== null && $archivoPath !== ''
            ? rtrim($uploadsBasePath, '/') . '/' . ltrim((string) $archivoPath, '/\\')
            : null;
        ?>
        <div class="message">
          <div class="head">
            <span class="pill <?= htmlspecialchars($autorRol) ?>"><?= htmlspecialchars(ucfirst($autorRol)) ?></span>
            <strong><?= htmlspecialchars($autorNombre) ?></strong>
            <?php if ($fecha !== ''): ?>
              <time><?= htmlspecialchars($fecha) ?></time>
            <?php endif; ?>
          </div>
          <p><?= nl2br(htmlspecialchars($comentario)) ?></p>
          <?php if ($archivoHref !== null): ?>
            <div class="files">
              <a href="<?= htmlspecialchars($archivoHref) ?>" target="_blank" rel="noopener">📎 Ver archivo</a>
            </div>
          <?php endif; ?>
        </div>
        <?php if (!empty($mensaje['respuestas']) && is_array($mensaje['respuestas'])): ?>
          <div class="messages nested">
            <?php foreach ($mensaje['respuestas'] as $respuesta): ?>
              <?php renderMachoteThreadMessage($respuesta, $uploadsBasePath); ?>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <?php
    }
}

$flashMessages = [];
if (isset($_GET['machote_status']) && $_GET['machote_status'] === 'saved') {
    $flashMessages[] = ['type' => 'success', 'text' => 'Cambios guardados correctamente.'];
}
if (!empty($_GET['machote_error'])) {
    $errorKey = (string) $_GET['machote_error'];
    $messages = [
        'locked' => 'El documento ya fue aprobado por la empresa. Reabre la revisión para continuar editando.',
        'invalid_id' => 'No se pudo identificar el machote solicitado.',
    ];
    $flashMessages[] = ['type' => 'error', 'text' => $messages[$errorKey] ?? 'No se pudieron guardar los cambios.'];
}
if (!empty($_GET['comentario_status'])) {
    $status = (string) $_GET['comentario_status'];
    $messages = [
        'added'    => 'Comentario agregado correctamente.',
        'resolved' => 'Comentario marcado como resuelto.',
        'reopened' => 'Comentario reabierto.',
    ];
    if (isset($messages[$status])) {
        $flashMessages[] = ['type' => 'success', 'text' => $messages[$status]];
    }
}
if (!empty($_GET['comentario_error'])) {
    $errorKey = (string) $_GET['comentario_error'];
    $messages = [
        'invalid'   => 'Datos incompletos para procesar el comentario.',
        'not_found' => 'El comentario solicitado no existe.',
        'internal'  => 'Ocurrió un error al actualizar los comentarios.',
        'locked'    => 'El documento aprobado mantiene los comentarios bloqueados. Reabre la revisión para continuar gestionándolos.',
    ];
    $flashMessages[] = ['type' => 'error', 'text' => $messages[$errorKey] ?? 'Ocurrió un error al gestionar el comentario.'];
}
if (!empty($_GET['reabrir_status'])) {
    $status = (string) $_GET['reabrir_status'];
    $messages = [
        'reopened' => 'La revisión se reabrió correctamente. El documento volvió al estado "En revisión".',
        'already_open' => 'El machote ya se encontraba en modo revisión.',
    ];
    if (isset($messages[$status])) {
        $flashMessages[] = ['type' => 'success', 'text' => $messages[$status]];
    }
}
if (!empty($_GET['reabrir_error'])) {
    $errorKey = (string) $_GET['reabrir_error'];
    $messages = [
        'invalid' => 'No se pudo identificar el machote a reabrir.',
        'internal' => 'Ocurrió un problema al reabrir la revisión. Intenta nuevamente.',
    ];
    $flashMessages[] = ['type' => 'error', 'text' => $messages[$errorKey] ?? 'No se pudo reabrir la revisión.'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📝 Revisión de Machote · Residencias</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/css/machote/revisar.css">
  <link rel="stylesheet" href="../../assets/css/machote/machote_revisar.css">

  <style>
    /* —— Estilos de apoyo (puedes moverlos a tu CSS del módulo) —— */
    body{background:#f6f7fb;color:#0f172a;}
    .kpis.card{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;align-items:center}
    .kpi{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px}
    .kpi h4{margin:0 0 6px 0;font-size:14px;color:#334155}
    .kpi-num{font-size:22px;font-weight:800}
    .kpi.wide{grid-column:span 2}
    .progress{width:100%;height:10px;background:#e5e7eb;border-radius:6px;overflow:hidden}
    .progress .bar{height:100%;background:#16a34a}

    .badge{border-radius:999px;font-weight:700;padding:4px 10px}
    .badge.en_revision{background:#fff3cd;color:#7a5c00}
    .badge.con_observaciones{background:#fee2e2;color:#991b1b}
    .badge.aprobado{background:#dcfce7;color:#166534}

    .flash{border-radius:12px;padding:12px;margin-bottom:12px;font-weight:600}
    .flash.success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
    .flash.error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}

    .split{display:grid;grid-template-columns:1.15fr 0.85fr;gap:14px}
    @media (max-width: 1100px){.split{grid-template-columns:1fr}}

    .editor-card .content{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px}
    .readonly-note{background:#fef3c7;border:1px solid #fde68a;color:#92400e;border-radius:10px;padding:10px;margin-bottom:12px;font-weight:600}
    .editor-actions{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end;margin-top:10px}
    .inline-form{display:inline-flex;gap:8px;align-items:center;margin:0}

    .threads .thread{border-bottom:1px solid #e5e7eb;padding:12px 8px}
    .threads .meta{display:flex;gap:8px;align-items:center;margin-bottom:6px;color:#64748b;font-size:12px;flex-wrap:wrap}
    .threads .badge.abierto{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;border-radius:999px;padding:2px 8px;font-weight:700}
    .threads .badge.resuelto{background:#dcfce7;color:#166534;border-radius:999px;padding:2px 8px;font-weight:700}
    .threads h4{margin:0 0 4px 0}
    .messages{display:flex;flex-direction:column;gap:12px;margin:12px 0}
    .messages.nested{margin-left:24px;padding-left:12px;border-left:2px solid #e5e7eb}
    .messages .message{border:1px solid #e5e7eb;border-radius:12px;padding:10px;background:#fff}
    .messages .message + .message{margin-top:0}
    .thread-detail .head{display:flex;gap:12px;align-items:center;justify-content:space-between;margin-bottom:6px}
    .pill{border-radius:999px;padding:2px 8px;font-size:12px;font-weight:700}
    .pill.admin{background:#e2e8f0;color:#0f172a}
    .pill.empresa{background:#dbeafe;color:#1e40af}
    .files a{font-size:13px}
    .reply{border-top:1px solid #e5e7eb;margin-top:10px;padding-top:10px;display:flex;flex-direction:column;gap:8px}
    .reply textarea{width:100%;border:1px solid #cbd5e1;border-radius:10px;padding:8px;font-family:inherit}
    .reply .row{display:flex;gap:10px;align-items:center;flex-wrap:wrap}

    .viewer-error{padding:14px;border:1px solid #fecaca;background:#fef2f2;color:#991b1b;border-radius:10px;font-weight:700}
  </style>
</head>
<body data-machote-bloqueado="<?= $machoteBloqueado ? '1' : '0' ?>">
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main machote-revisar">
      <header class="topbar">
        <div>
          <h2>📝 Revisión de Machote</h2>
          <p class="subtitle">
            Empresa: <strong><?= htmlspecialchars($empresaNombre) ?></strong>
            · Machote <strong>#<?= (int) $machoteId ?></strong>
            · Versión local: <strong><?= htmlspecialchars($versionLocal) ?></strong>
            · Estado: <?= badgeEstado($estado) ?>
          </p>
        </div>
        <div class="actions">
          <a
            class="btn primary"
            href="../../handler/machote/machote_generate_pdf.php?id=<?= (int) $machoteId ?>"
            target="_blank" rel="noopener"
            title="Generar vista PDF desde el HTML actual"
          >📄 Ver PDF</a>

          <?php if ($empresaId > 0): ?>
            <a href="../empresa/empresa_view.php?id=<?= (int) $empresaId ?>" class="btn secondary">⬅ Volver a la empresa</a>
          <?php endif; ?>

          <?php if ($machoteBloqueado): ?>
            <form
              class="inline-form"
              action="../../handler/machote/machote_reabrir_handler.php"
              method="post"
              onsubmit="return confirm('¿Reabrir la revisión para que la empresa vuelva a comentar y confirmar?');"
            >
              <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
              <button class="btn danger" type="submit">↺ Reabrir revisión</button>
            </form>
          <?php endif; ?>
        </div>
      </header>

      <?php foreach ($flashMessages as $flash): ?>
        <section class="flash <?= htmlspecialchars($flash['type']) ?>">
          <?= htmlspecialchars($flash['text']) ?>
        </section>
      <?php endforeach; ?>

      <?php if (!empty($errorMessage)): ?>
        <section class="viewer-error"><?= htmlspecialchars($errorMessage) ?></section>
      <?php endif; ?>

      <!-- KPIs -->
      <section class="kpis card">
        <div class="kpi">
          <h4>Comentarios abiertos</h4>
          <div class="kpi-num"><?= (int) $comentAbiertos ?></div>
        </div>
        <div class="kpi">
          <h4>Comentarios resueltos</h4>
          <div class="kpi-num"><?= (int) $comentResueltos ?></div>
        </div>
        <div class="kpi wide">
          <h4>Avance de la revisión</h4>
          <div class="progress"><div class="bar" style="width: <?= (int) $progreso ?>%"></div></div>
          <small><?= (int) $progreso ?>% completado</small>
        </div>

        <div class="kpi">
          <h4>Estado</h4>
          <div><?= badgeEstado($estado) ?></div>
        </div>
      </section>

      <!-- Split: Editor + Comentarios -->
      <section class="split">
        <!-- 📄 Editor (CKEditor) -->
        <div class="card editor-card">
          <header>Documento a revisar (HTML editable)</header>
          <div class="content">
            <?php if ($machoteBloqueado): ?>
              <p class="readonly-note">Este documento fue confirmado por la empresa. Reabre la revisión para habilitar nuevamente la edición.</p>
            <?php endif; ?>
            <form method="POST" action="../../handler/machote/machote_update_handler.php">
              <input type="hidden" name="id" value="<?= (int) $machoteId ?>">
              <input type="hidden" name="redirect" value="machote_revisar">
              <textarea id="editor" name="contenido" rows="24"><?= $contenidoHtml ?></textarea>

              <div class="editor-actions">
                <button class="btn" type="button" onclick="togglePreview()">👁️ Vista limpia</button>
                <button class="btn primary" type="submit" <?= $machoteBloqueado ? 'disabled' : '' ?>>💾 Guardar cambios</button>
                <a class="btn" target="_blank" rel="noopener"
                   href="../../handler/machote/machote_generate_pdf.php?id=<?= (int) $machoteId ?>">📄 Previsualizar PDF</a>
              </div>
            </form>
          </div>
        </div>

        <!-- 💬 Comentarios -->
        <div class="card comments-card">
          <header style="display:flex;justify-content:space-between;align-items:center;">
            <span>💬 Comentarios</span>
            <div class="filters">
              <span class="chip">Abiertos (<?= (int) $comentAbiertos ?>)</span>
              <span class="chip">Resueltos (<?= (int) $comentResueltos ?>)</span>
              <span class="chip">Todos (<?= (int) ($totales['total'] ?? ($comentAbiertos + $comentResueltos)) ?>)</span>
            </div>
          </header>

          <div class="content comments-content">
            <?php if ($comentariosBloqueados): ?>
              <p class="readonly-note" style="margin:0">Los comentarios están bloqueados porque la empresa confirmó el documento. Reabre la revisión para habilitarlos nuevamente.</p>
            <?php endif; ?>
            <!-- Crear nuevo comentario -->
            <details class="card comment-card" open>
              <summary class="comment-summary">➕ Nuevo comentario</summary>
              <div class="content comment-content">
                <form class="comment-form" action="../../handler/machote/machote_comentario_add_handler.php" method="post">
                  <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                  <?php if ($currentUserId > 0): ?>
                    <input type="hidden" name="usuario_id" value="<?= (int) $currentUserId ?>">
                  <?php endif; ?>
                  <fieldset class="comment-fields" <?= $comentariosBloqueados ? 'disabled' : '' ?>>
                    <div class="comment-field">
                      <label for="clausula">Sección / cláusula (opcional)</label>
                      <input class="comment-input" type="text" id="clausula" name="clausula" placeholder="Ej. CLÁUSULA PRIMERA · Vigencia" />
                    </div>
                    <div class="comment-field">
                      <label for="comentario">Comentario</label>
                      <textarea class="comment-textarea" id="comentario" name="comentario" rows="3" required placeholder="Describe el ajuste, duda o cambio que solicitas…"></textarea>
                    </div>
                    <div class="comment-actions">
                      <button class="btn primary" <?= $comentariosBloqueados ? 'disabled' : '' ?>>Publicar</button>
                    </div>
                  </fieldset>
                </form>
              </div>
            </details>

            <!-- Lista simple de comentarios -->
            <div class="threads">
              <?php if (empty($comentarios)): ?>
                <p style="color:#64748b">Sin comentarios aún.</p>
              <?php else: ?>
                <?php foreach ($comentarios as $c): ?>
                  <?php
                    $isAbierto = (($c['estatus'] ?? 'pendiente') === 'pendiente');
                    $autor = trim((string) ($c['autor_nombre'] ?? ''));
                    $clausula = trim((string) ($c['clausula'] ?? ''));
                    $creadoEn = (string) ($c['creado_en'] ?? '');
                    $respuestasBloqueadas = $comentariosBloqueados || !$isAbierto;
                  ?>
                  <article class="thread thread-detail">
                    <div class="meta">
                      <span class="badge <?= $isAbierto ? 'abierto' : 'resuelto' ?>">
                        <?= $isAbierto ? 'Abierto' : 'Resuelto' ?>
                      </span>
                      <?php if ($autor !== ''): ?>
                        <span>· <?= htmlspecialchars($autor) ?></span>
                      <?php endif; ?>
                      <?php if ($clausula !== ''): ?>
                        <span>· <?= htmlspecialchars($clausula) ?></span>
                      <?php endif; ?>
                      <?php if ($creadoEn !== ''): ?>
                        <span>· <?= htmlspecialchars($creadoEn) ?></span>
                      <?php endif; ?>
                    </div>

                    <div class="messages conversation">
                      <?php renderMachoteThreadMessage($c, $uploadsBasePath); ?>
                    </div>

                    <div class="row-actions" style="display:flex;gap:8px;flex-wrap:wrap;margin-top:6px">
                      <?php if ($isAbierto): ?>
                        <form action="../../handler/machote/machote_comentario_resolver_handler.php" method="post" onsubmit="return confirm('¿Marcar como resuelto?')">
                          <input type="hidden" name="id" value="<?= (int) ($c['id'] ?? 0) ?>">
                          <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                          <button class="btn small danger" <?= $comentariosBloqueados ? 'disabled' : '' ?>>✓ Marcar como resuelto</button>
                        </form>
                      <?php else: ?>
                        <form action="../../handler/machote/machote_comentario_reabrir_handler.php" method="post">
                          <input type="hidden" name="id" value="<?= (int) ($c['id'] ?? 0) ?>">
                          <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                          <button class="btn small" <?= $comentariosBloqueados ? 'disabled' : '' ?>>↺ Reabrir</button>
                        </form>
                      <?php endif; ?>
                    </div>

                    <form class="reply" action="../../handler/machote/machote_reply_handler.php" method="post" enctype="multipart/form-data">
                      <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                      <input type="hidden" name="respuesta_a" value="<?= (int) ($c['id'] ?? 0) ?>">
                      <fieldset style="border:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px" <?= $respuestasBloqueadas ? 'disabled' : '' ?>>
                        <textarea name="comentario" rows="3" placeholder="Responder…" required></textarea>
                        <div class="row">
                          <input type="file" name="archivo">
                          <button class="btn primary" type="submit" <?= $respuestasBloqueadas ? 'disabled' : '' ?>>Enviar</button>
                        </div>
                      </fieldset>
                    </form>
                    <?php if (!$isAbierto): ?>
                      <p style="color:#64748b;font-size:13px;margin:6px 0 0;">Este comentario esta resuelto; las respuestas estan deshabilitadas.</p>
                    <?php endif; ?>
                  </article>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </section>

      <footer class="hint" style="margin-top:10px;color:#64748b">
        <small>Consejo: guarda con frecuencia. El PDF refleja los cambios del HTML actual.</small>
      </footer>
    </main>
  </div>

<!-- CKEditor 5 (desde CDN) -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
  let editor;
  const isReadOnly = document.body.dataset.machoteBloqueado === '1';

  ClassicEditor
    .create(document.querySelector('#editor'), {
      toolbar: [
        'undo', 'redo', '|',
        'heading', '|',
        'bold', 'italic', 'link', '|',
        'numberedList', 'bulletedList', '|',
        'insertTable', 'blockQuote', '|',
        'alignment:left', 'alignment:center', 'alignment:right', '|',
        'fontColor', 'fontBackgroundColor'
      ],
      language: 'es',
      fontSize: { options: [ 'default', 11, 12, 13, 14, 16, 18 ] },
      htmlSupport: {
        allow: [ {
          name: /.*/,
          attributes: true,
          classes: true,
          styles: true
        } ]
      }
    })
    .then(newEditor => {
      editor = newEditor;
      if (isReadOnly) {
        editor.enableReadOnlyMode('machote-readonly');
      }
    })
    .catch(console.error);

  // Actualiza el contenido antes de guardar
  const editorForm = document.querySelector('.editor-card form');
  if (editorForm) {
    editorForm.addEventListener('submit', (event) => {
      if (isReadOnly) {
        event.preventDefault();
        return false;
      }

      if (editor) {
        editorForm.querySelector('#editor').value = editor.getData();
      }

      return true;
    });
  }
</script>

  <style>
    /* Reduce hints visuales de CKEditor cuando activas "Vista limpia" */
    .cke_editable.preview-clean *{ outline: none !important; }
  </style>
</body>
</html>
