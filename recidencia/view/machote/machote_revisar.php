<?php
declare(strict_types=1);

require_once __DIR__ . '/../../handler/machote/machote_revisar_handler.php';

// Variables esperadas desde el handler:
// $machote, $empresa, $convenio, $comentarios, $progreso, $estado, $errorMessage, $totales, $currentUser

// Fallbacks
$empresaNombre   = isset($empresa['nombre']) ? (string) $empresa['nombre'] : '-';
$empresaId       = isset($empresa['id']) ? (int) $empresa['id'] : 0;
$empresaEstatus  = isset($empresa['estatus']) ? (string) $empresa['estatus'] : '';
$empresaCompletada = strcasecmp(trim($empresaEstatus), 'Completada') === 0;
$machoteId       = isset($machote['id']) ? (int) $machote['id'] : (int) ($_GET['id'] ?? 0);
$empresaInactiva = strcasecmp(trim($empresaEstatus), 'Inactiva') === 0;
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
$machoteEstatus   = isset($machote['estatus']) ? (string) $machote['estatus'] : '';
$machoteArchivado = strcasecmp(trim($machoteEstatus !== '' ? $machoteEstatus : (string) $estado), 'Archivado') === 0;
$machoteConfirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;
$machoteBloqueado = $machoteConfirmado || $empresaCompletada || $empresaInactiva || $machoteArchivado;
$comentariosBloqueados = $machoteBloqueado;
$bloqueoMensaje = $machoteArchivado
    ? 'El machote estA? archivado; solo lectura.'
    : ($empresaCompletada
        ? 'La empresa estA? en estatus Completada; la ediciA3n y los comentarios estA?n bloqueados.'
        : ($empresaInactiva
            ? 'Machote invalidado por empresa Inactiva; solo lectura.'
            : 'Este documento fue confirmado por la empresa. Reabre la revisiA3n para habilitar nuevamente la ediciA3n.'));

// KPIs
$comentAbiertos  = (int) ($totales['pendientes'] ?? 0);
$comentResueltos = (int) ($totales['resueltos'] ?? 0);

// Helpers de UI
function badgeEstado(string $estado): string {
  $map = [
    'Aprobado'          => '<span class="badge aprobado">Aprobado</span>',
    'Con observaciones' => '<span class="badge con_observaciones">Con observaciones</span>',
    'En revisión'       => '<span class="badge en_revision">En revisión</span>',
    'Archivado'         => '<span class="badge archivado">Archivado</span>',
  ];
  return $map[$estado] ?? '<span class="badge en_revision">En revisión</span>';
}

if (!function_exists('renderMachoteThreadMessage')) {
    function renderMachoteThreadMessage(array $mensaje, string $uploadsBasePath): void {
        $autorRol = (string) ($mensaje['autor_rol'] ?? 'empresa');
        $rolLabel = ucfirst($autorRol);
        $autorNombre = trim((string) ($mensaje['autor_nombre'] ?? $rolLabel));
        $mostrarNombre = strcasecmp($autorNombre, $rolLabel) !== 0;
        $fecha = (string) ($mensaje['creado_en'] ?? '');
        $comentario = (string) ($mensaje['comentario'] ?? '');
        $archivoPath = $mensaje['archivo_path'] ?? null;
        $archivoHref = $archivoPath !== null && $archivoPath !== ''
            ? rtrim($uploadsBasePath, '/') . '/' . ltrim((string) $archivoPath, '/\\')
            : null;
        ?>
        <div class="message">
          <div class="head">
            <span class="pill <?= htmlspecialchars($autorRol) ?>"><?= htmlspecialchars($rolLabel) ?></span>
            <?php if ($mostrarNombre): ?>
              <strong><?= htmlspecialchars($autorNombre) ?></strong>
            <?php endif; ?>
            <?php if ($fecha !== ''): ?>
              <time><?= htmlspecialchars($fecha) ?></time>
            <?php endif; ?>
          </div>
          <p><?= nl2br(htmlspecialchars($comentario)) ?></p>
          <?php if ($archivoHref !== null): ?>
            <div class="files">
              <a href="<?= htmlspecialchars($archivoHref) ?>" target="_blank" rel="noopener">Ver archivo</a>
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
        'locked' => 'El documento ya fue aprobado por la empresa. Reabre la revisiA3n para continuar editando.',
        'invalid_id' => 'No se pudo identificar el machote solicitado.',
        'empresa_inactiva' => 'Machote invalidado por empresa inactiva; solo lectura.',
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
        'locked'    => 'El documento aprobado mantiene los comentarios bloqueados. Reabre la revisión para gestionarlos.',
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
        'empresa_completada' => 'La empresa estA? en estatus Completada; no se puede reabrir la revisiA3n.',
        'empresa_inactiva' => 'RevisiA3n cancelada porque la empresa estA? Inactiva.',
        'internal' => 'OcurriA3 un problema al reabrir la revisiA3n. Intenta nuevamente.',
    ];

    $flashMessages[] = ['type' => 'error', 'text' => $messages[$errorKey] ?? 'No se pudo reabrir la revisión.'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Revisión de Machote | Residencias</title>

  <link rel="stylesheet" href="../../assets/css/modules/machote/machoterevisar.css" />
</head>
<body data-machote-bloqueado="<?= $machoteBloqueado ? '1' : '0' ?>">
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main machote-revisar">
      <header class="topbar">
        <div>
          <h2>Revisión de Machote</h2>
          <p class="subtitle">
            Empresa: <strong><?= htmlspecialchars($empresaNombre) ?></strong>
            · Machote <strong>#<?= (int) $machoteId ?></strong>
            · Versión local: <strong><?= htmlspecialchars($versionLocal) ?></strong>
            · Estado: <?= badgeEstado($estado) ?>
          </p>
        </div>
        <div class="actions">
          <button
            type="submit"
            form="machote-editor-form"
            class="btn primary"
            <?= $machoteBloqueado ? 'disabled' : '' ?>
          >Guardar machote</button>
          <a
            class="btn primary"
            href="../../handler/machote/machote_generate_pdf.php?id=<?= (int) $machoteId ?>"
            target="_blank" rel="noopener"
            title="Generar vista PDF desde el HTML actual"
          >Ver PDF</a>

          <?php if ($empresaId > 0): ?>
            <a href="../empresa/empresa_view.php?id=<?= (int) $empresaId ?>" class="btn secondary">Volver a la empresa</a>
          <?php endif; ?>

          <?php if ($machoteBloqueado && !$empresaCompletada && !$empresaInactiva && !$machoteArchivado): ?>
            <form
              class="inline-form"
              action="../../handler/machote/machote_reabrir_handler.php"
              method="post"
              onsubmit="return confirm('Reabrir la revisión para que la empresa vuelva a comentar y confirmar?');"
            >
              <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
              <button class="btn danger" type="submit">Reabrir revisión</button>
            </form>
          <?php elseif ($empresaCompletada || $empresaInactiva || $machoteArchivado): ?>
            <span class="btn danger" style="pointer-events:none;opacity:0.6;">Reabrir revisión</span>
          <?php endif; ?>
        </div>
      </header>

      <?php foreach ($flashMessages as $flash): ?>
        <section class="flash <?= htmlspecialchars($flash['type']) ?>">
          <?= htmlspecialchars($flash['text']) ?>
        </section>
      <?php endforeach; ?>

      <?php if (!empty($errorMessage)): ?>
        <section class="flash error"><?= htmlspecialchars($errorMessage) ?></section>
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

      <!-- Split: Editor + Previsualización + Comentarios -->
      <section class="split" id="machote-workspace">

              <!-- Comentarios -->
        <div class="card comments-card panel">
          <header style="display:flex;justify-content:space-between;align-items:center;">
            <span>Comentarios</span>
            <div class="filters">
              <span class="chip">Abiertos (<?= (int) $comentAbiertos ?>)</span>
              <span class="chip">Resueltos (<?= (int) $comentResueltos ?>)</span>
              <span class="chip">Todos (<?= (int) ($totales['total'] ?? ($comentAbiertos + $comentResueltos)) ?>)</span>
            </div>
          </header>

          <div class="content comments-content">
            <?php if ($comentariosBloqueados): ?>
              <p class="readonly-note"><?= htmlspecialchars($bloqueoMensaje) ?></p>
            <?php endif; ?>
            <!-- Crear nuevo comentario -->
            <details class="card comment-card" open>
              <summary class="comment-summary">Nuevo comentario</summary>
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
                      <textarea class="comment-textarea" id="comentario" name="comentario" rows="3" required placeholder="Describe el ajuste, duda o cambio que solicitas..."></textarea>
                    </div>
                    <div class="comment-actions">
                      <button class="btn primary" <?= $comentariosBloqueados ? 'disabled' : '' ?>>Publicar</button>
                    </div>
                  </fieldset>
                </form>
              </div>
            </details>

            <!-- Lista de comentarios -->
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
                        <form action="../../handler/machote/machote_comentario_resolver_handler.php" method="post" onsubmit="return confirm('Marcar como resuelto?')">
                          <input type="hidden" name="id" value="<?= (int) ($c['id'] ?? 0) ?>">
                          <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                          <button class="btn small danger" <?= $comentariosBloqueados ? 'disabled' : '' ?>>Marcar como resuelto</button>
                        </form>
                      <?php else: ?>
                        <form action="../../handler/machote/machote_comentario_reabrir_handler.php" method="post">
                          <input type="hidden" name="id" value="<?= (int) ($c['id'] ?? 0) ?>">
                          <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                          <button class="btn small" <?= $comentariosBloqueados ? 'disabled' : '' ?>>Reabrir</button>
                        </form>
                      <?php endif; ?>
                    </div>

                    <?php if ($isAbierto): ?>
                      <form class="reply" action="../../handler/machote/machote_reply_handler.php" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="machote_id" value="<?= (int) $machoteId ?>">
                        <input type="hidden" name="respuesta_a" value="<?= (int) ($c['id'] ?? 0) ?>">
                        <fieldset style="border:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px" <?= $respuestasBloqueadas ? 'disabled' : '' ?>>
                          <textarea name="comentario" rows="3" placeholder="Responder" required></textarea>
                          <div class="row">
                            <input type="file" name="archivo">
                            <button class="btn primary" type="submit" <?= $respuestasBloqueadas ? 'disabled' : '' ?>>Enviar</button>
                          </div>
                        </fieldset>
                      </form>
                    <?php else: ?>
                      <p style="color:#64748b;font-size:13px;margin:6px 0 0;">Este comentario está resuelto; las respuestas están deshabilitadas.</p>
                    <?php endif; ?>
                  </article>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>


        <!-- Editor -->
        <div class="card editor-card panel">
          <header>Documento a revisar (HTML editable)</header>
          <div class="content">
            <?php if ($machoteBloqueado): ?>
              <p class="readonly-note"><?= htmlspecialchars($bloqueoMensaje) ?></p>
            <?php endif; ?>
            <form id="machote-editor-form" method="POST" action="../../handler/machote/machote_update_handler.php">
              <input type="hidden" name="id" value="<?= (int) $machoteId ?>">
              <input type="hidden" name="redirect" value="machote_revisar">
              <textarea id="editor" name="contenido" rows="24"><?= $contenidoHtml ?></textarea>

              <div class="editor-actions">
                <button class="btn" type="button" onclick="togglePreview()">Vista limpia</button>
                <button class="btn primary" type="submit" <?= $machoteBloqueado ? 'disabled' : '' ?>>Guardar cambios</button>
                <a class="btn" target="_blank" rel="noopener"
                   href="../../handler/machote/machote_generate_pdf.php?id=<?= (int) $machoteId ?>">Previsualizar PDF</a>
              </div>
            </form>
          </div>
        </div>

        <!-- Vista previa -->
        <div class="card preview-card panel">
          <header>Vista previa viva</header>
          <div class="content">
            <?php if (isset($machote['contenido_preview'])): ?>
              <div class="preview-box">
                <?= $machote['contenido_preview'] ?>
              </div>
            <?php else: ?>
              <p class="empty-note">Aún no se ha generado contenido previo.</p>
            <?php endif; ?>
          </div>
        </div>


      </section>

      <footer class="hint">
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

  // Oculta los mensajes flash tras 5 segundos para limpiar la vista
  document.querySelectorAll('.flash').forEach((flashEl) => {
    setTimeout(() => {
      flashEl.classList.add('is-hidden');
      flashEl.addEventListener('transitionend', () => flashEl.remove(), { once: true });
    }, 5000);
  });
</script>

  <style>
    /* Reduce hints visuales de CKEditor cuando activas "Vista limpia" */
    .cke_editable.preview-clean *{ outline: none !important; }
    #machote-workspace {
      scrollbar-gutter: stable both-axis;
    }
    .preview-card .content {
      padding: 0.75rem 1rem 1rem;
    }
    .preview-card header {
      border-bottom: 1px solid #d0d7de;
      padding-bottom: 0.5rem;
    }
    .preview-box {
      border: 1px solid #d0d7de;
      border-radius: 6px;
      padding: 1rem;
      background: #fff;
      min-height: 360px;
      max-height: 520px;
      overflow: auto;
    }
    .preview-box p:last-child {
      margin-bottom: 0;
    }
    .empty-note {
      color: #64748b;
      font-size: 0.95rem;
      margin: 0;
    }
  </style>
</body>
</html>
