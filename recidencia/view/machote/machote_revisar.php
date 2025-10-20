<?php
// ====== Datos simulados (reemplaza por tu controlador/BD) ======
$empresaNombre   = 'Casa del Barrio';
$empresaId       = 45;

$revisionId      = 123;
$docNombre       = 'Institucional v1.2';
$rutaPdfActual   = "../../uploads/machote_v12_borrador.pdf"; // <- desde BD
$cacheBusting    = "?rev=" . urlencode((string)$revisionId);

$estadoRevision  = 'En revisión'; // 'En revisión' | 'Con observaciones' | 'Aprobado'
$comentAbiertos  = 1;
$comentResueltos = 3;
$avancePct       = 75;

$aprobAdmin      = false;  // desde BD (toggle admin)
$aprobEmpresa    = false;  // desde BD (toggle empresa)
$puedeGenerar    = ($comentAbiertos === 0 && $aprobAdmin && $aprobEmpresa);

// ====== Ejemplo de hilos (luego sácalos de BD) ======
$hilos = [
  [
    'id' => 101,
    'estatus' => 'abierto',           // abierto | resuelto
    'autor_rol' => 'empresa',         // empresa | admin
    'asunto' => 'Ajustar vigencia',
    'resumen' => 'Proponemos 18 meses por motivos de calendario…',
    'hace' => 'hace 2 días',
    'adjuntos' => 1,
  ],
  [
    'id' => 99,
    'estatus' => 'resuelto',
    'autor_rol' => 'admin',
    'asunto' => 'Confidencialidad',
    'resumen' => 'Se integró un anexo de confidencialidad estándar.',
    'hace' => 'hace 5 días',
    'adjuntos' => 0,
  ],
];

// Hilo seleccionado por defecto (simulado)
$hiloSeleccionado = 101;
// Mensajes del hilo seleccionado (simulados)
$mensajes = [
  [
    'autor_rol' => 'empresa',
    'fecha'     => '2025-10-01 10:12',
    'texto'     => 'Proponemos 18 meses por calendario de proyectos…',
    'archivos'  => ['borrador_v1.pdf'],
  ],
  [
    'autor_rol' => 'admin',
    'fecha'     => '2025-10-01 12:20',
    'texto'     => 'Recibido. Prepararemos la versión 2 y la compartimos hoy.',
    'archivos'  => [],
  ],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📝 Revisión de Machote · Residencias</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/css/machote/revisar.css">

  <style>
    /* Estilos mínimos de apoyo (puedes moverlos a tu CSS) */
    .kpis.card{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;align-items:center}
    .kpi{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:12px}
    .kpi h4{margin:0 0 6px 0;font-size:14px;color:#334155}
    .kpi-num{font-size:22px;font-weight:800}
    .kpi.wide{grid-column:span 2}
    .progress{width:100%;height:10px;background:#e5e7eb;border-radius:6px;overflow:hidden}
    .progress .bar{height:100%;background:#16a34a}

    .approvals{display:flex;flex-direction:column;gap:8px}
    .switch{display:flex;align-items:center;gap:10px}
    .switch .label{font-weight:600;color:#334155}
    .badge.en_revision{background:#fff3cd;color:#7a5c00;padding:4px 8px;border-radius:999px;font-weight:700}
    .badge.con_observaciones{background:#fee2e2;color:#991b1b;padding:4px 8px;border-radius:999px;font-weight:700}
    .badge.aprobado{background:#dcfce7;color:#166534;padding:4px 8px;border-radius:999px;font-weight:700}

    .split{display:grid;grid-template-columns:1fr 1.1fr;gap:14px}
    @media (max-width: 1024px){.split{grid-template-columns:1fr}}

    .threads .thread{border-bottom:1px solid #e5e7eb;padding:12px 8px}
    .threads .thread.active{background:#f8fafc;border-radius:12px}
    .threads .meta{display:flex;gap:8px;align-items:center;margin-bottom:6px;color:#64748b;font-size:12px}
    .threads .badge.abierto{background:#fff7ed;color:#9a3412;border:1px solid #fed7aa;border-radius:999px;padding:2px 8px;font-weight:700}
    .threads .badge.resuelto{background:#dcfce7;color:#166534;border-radius:999px;padding:2px 8px;font-weight:700}
    .threads .author.admin{color:#0f172a;font-weight:700}
    .threads .author.empresa{color:#1f6feb;font-weight:700}
    .row-actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:8px}

    .thread-detail .message{border:1px solid #e5e7eb;border-radius:12px;padding:10px;margin-bottom:10px}
    .thread-detail .head{display:flex;justify-content:space-between;margin-bottom:6px}
    .pill.admin{background:#e2e8f0;color:#0f172a;border-radius:999px;padding:2px 8px;font-size:12px;font-weight:700}
    .pill.empresa{background:#dbeafe;color:#1e40af;border-radius:999px;padding:2px 8px;font-size:12px;font-weight:700}
    .files a{font-size:13px}
    .reply .row{display:flex;gap:10px;align-items:center;margin-top:8px;flex-wrap:wrap}

    .pdf-frame{border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#fff;height:520px}
    .pdf-frame iframe{width:100%;height:100%;border:0}
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📝 Revisión de Machote</h2>
          <p class="subtitle">
            Empresa: <strong><?= htmlspecialchars($empresaNombre) ?></strong> · Revisión <strong>#<?= (int)$revisionId ?></strong> · Documento: <strong><?= htmlspecialchars($docNombre) ?></strong> ·
            Estado:
            <?php if ($estadoRevision === 'Aprobado'): ?>
              <span class="badge aprobado">Aprobado</span>
            <?php elseif ($estadoRevision === 'Con observaciones'): ?>
              <span class="badge con_observaciones">Con observaciones</span>
            <?php else: ?>
              <span class="badge en_revision">En revisión</span>
            <?php endif; ?>
          </p>
        </div>
        <div class="actions">
          <form action="revision_generate_convenio_action.php" method="post" style="display:inline;">
            <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
            <button class="btn primary" <?= $puedeGenerar ? '' : 'disabled' ?>
              title="Se habilita cuando no hay comentarios abiertos y ambas aprobaciones están activas">
              📄 Generar convenio
            </button>
          </form>
          <a href="../empresa/empresa_view.php?id=<?= (int)$empresaId ?>" class="btn secondary">⬅ Volver a la empresa</a>
        </div>
      </header>

      <!-- KPIs + Aprobaciones -->
      <section class="kpis card">
        <div class="kpi">
          <h4>Comentarios abiertos</h4>
          <div class="kpi-num"><?= (int)$comentAbiertos ?></div>
        </div>
        <div class="kpi">
          <h4>Comentarios resueltos</h4>
          <div class="kpi-num"><?= (int)$comentResueltos ?></div>
        </div>
        <div class="kpi wide">
          <h4>Avance de la revisión</h4>
          <div class="progress"><div class="bar" style="width: <?= max(0,min(100,(int)$avancePct)) ?>%"></div></div>
          <small><?= (int)$avancePct ?>% completado</small>
        </div>

        <div class="approvals">
          <!-- Toggle aprobación admin -->
          <form action="revision_toggle_approve_action.php" method="post" class="switch"
                title="Se habilita cuando no hay comentarios abiertos">
            <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
            <input type="hidden" name="who" value="admin">
            <input type="checkbox" id="aprob_admin" name="state"
                   <?= $aprobAdmin ? 'checked' : '' ?> <?= $comentAbiertos ? 'disabled' : '' ?>>
            <span class="slider"></span>
            <label class="label" for="aprob_admin">Aprobación del administrador</label>
          </form>

          <!-- Toggle aprobación empresa -->
          <form action="revision_toggle_approve_action.php" method="post" class="switch"
                title="Se habilita cuando no hay comentarios abiertos">
            <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
            <input type="hidden" name="who" value="empresa">
            <input type="checkbox" id="aprob_empresa" name="state"
                   <?= $aprobEmpresa ? 'checked' : '' ?> <?= $comentAbiertos ? 'disabled' : '' ?>>
            <span class="slider"></span>
            <label class="label" for="aprob_empresa">Aprobación de la empresa</label>
          </form>
        </div>
      </section>

      <!-- 📄 Documento a revisar -->
      <section class="card">
        <header>Documento a revisar: <strong><?= htmlspecialchars($docNombre) ?></strong></header>
        <div class="content">
          <div class="pdf-frame">
            <iframe
              src="<?= htmlspecialchars($rutaPdfActual . $cacheBusting) ?>#toolbar=1&navpanes=0&statusbar=0&view=FitH"
              title="Machote - <?= htmlspecialchars($docNombre) ?>"
              loading="lazy"
            ></iframe>
          </div>

          <div class="file-actions" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
            <a class="btn" href="<?= htmlspecialchars($rutaPdfActual . $cacheBusting) ?>" target="_blank" rel="noopener">📄 Abrir en nueva pestaña</a>
            <a class="btn" download href="<?= htmlspecialchars($rutaPdfActual . $cacheBusting) ?>">⬇️ Descargar PDF</a>

            <!-- Solo Admin: Subir nueva versión -->
            <form action="revision_upload_version_action.php" method="post" enctype="multipart/form-data" style="margin-left:auto;display:flex;gap:8px;align-items:center">
              <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
              <input type="file" name="archivo_pdf" accept="application/pdf" required>
              <button class="btn">⬆️ Subir nueva versión</button>
            </form>
          </div>

          <small class="note" style="display:block; margin-top:8px; color:#64748b;">
            Si no se visualiza el PDF embebido, ábrelo en nueva pestaña o descárgalo.
          </small>
        </div>
      </section>

      <!-- 💬 Nuevo comentario -->
      <section class="card">
        <header style="display:flex;justify-content:space-between;align-items:center;">
          <span>💬 Nuevo comentario</span>
          <button type="button" class="btn" onclick="toggleNewComment()">➕ Mostrar/Ocultar</button>
        </header>
        <div class="content" id="newComment" style="display:none;">
          <form class="grid-2" action="revision_thread_create_action.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">

            <div class="full">
              <label for="asunto">Título del comentario</label>
              <input type="text" id="asunto" name="asunto" placeholder="Ej. Vigencia del convenio" required>
            </div>

            <div class="full">
              <label for="cuerpo">Descripción</label>
              <textarea id="cuerpo" name="cuerpo" rows="4" placeholder="Describe el cambio o la aclaración que solicitas…" required></textarea>
            </div>

            <div>
              <label for="adjunto">Adjunto (opcional)</label>
              <input type="file" id="adjunto" name="adjunto" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
            </div>

            <div class="actions">
              <button type="submit" class="btn primary">Publicar comentario</button>
              <button type="button" class="btn" onclick="toggleNewComment()">Cancelar</button>
            </div>
          </form>
        </div>
      </section>

      <!-- 💬 Listado de comentarios + Detalle -->
      <section class="split">
        <!-- Lista de comentarios -->
        <div class="card">
          <header style="display:flex;justify-content:space-between;align-items:center;">
            <span>💬 Comentarios</span>
            <div class="filters">
              <a href="#" class="chip active">Abiertos (<?= (int)$comentAbiertos ?>)</a>
              <a href="#" class="chip">Resueltos (<?= (int)$comentResueltos ?>)</a>
              <a href="#" class="chip">Todos (<?= (int)($comentAbiertos + $comentResueltos) ?>)</a>
            </div>
          </header>

          <div class="content threads">
            <?php foreach ($hilos as $h): ?>
              <article class="thread <?= $h['id'] === $hiloSeleccionado ? 'active' : '' ?>">
                <div class="meta">
                  <?php if ($h['estatus'] === 'abierto'): ?>
                    <span class="badge abierto">Abierto</span>
                  <?php else: ?>
                    <span class="badge resuelto">Resuelto</span>
                  <?php endif; ?>
                  <span class="author <?= $h['autor_rol'] === 'admin' ? 'admin' : 'empresa' ?>">
                    <?= $h['autor_rol'] === 'admin' ? 'Administrador' : 'Empresa' ?>
                  </span>
                  <span class="time"><?= htmlspecialchars($h['hace']) ?></span>
                </div>
                <h4><?= htmlspecialchars($h['asunto']) ?></h4>
                <p><?= htmlspecialchars($h['resumen']) ?></p>
                <div class="row-actions">
                  <button class="btn small" onclick="selectComment(<?= (int)$h['id'] ?>)">👁️ Ver detalle</button>
                  <a class="btn small" href="#">📎 Archivos (<?= (int)$h['adjuntos'] ?>)</a>

                  <?php if ($h['estatus'] === 'abierto'): ?>
                    <form action="revision_thread_resolve_action.php" method="post" style="display:inline;" onsubmit="return confirm('¿Marcar como resuelto?');">
                      <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
                      <input type="hidden" name="hilo_id" value="<?= (int)$h['id'] ?>">
                      <button class="btn small danger">✓ Marcar como resuelto</button>
                    </form>
                  <?php else: ?>
                    <form action="revision_thread_reopen_action.php" method="post" style="display:inline;">
                      <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
                      <input type="hidden" name="hilo_id" value="<?= (int)$h['id'] ?>">
                      <button class="btn small">↺ Reabrir comentario</button>
                    </form>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Detalle del comentario seleccionado -->
        <div class="card">
          <header>📌 Detalle del comentario</header>
          <div class="content thread-detail" id="detailBox" aria-live="polite">
            <?php foreach ($mensajes as $m): ?>
              <div class="message">
                <div class="head">
                  <span class="pill <?= $m['autor_rol'] === 'admin' ? 'admin' : 'empresa' ?>">
                    <?= $m['autor_rol'] === 'admin' ? 'Administrador' : 'Empresa' ?>
                  </span>
                  <time><?= htmlspecialchars($m['fecha']) ?></time>
                </div>
                <p><?= htmlspecialchars($m['texto']) ?></p>
                <?php if (!empty($m['archivos'])): ?>
                  <div class="files">
                    <?php foreach ($m['archivos'] as $file): ?>
                      <a href="#"><?= htmlspecialchars($file) ?></a>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>

            <!-- Responder -->
            <form class="reply" action="revision_reply_create_action.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="revision_id" value="<?= (int)$revisionId ?>">
              <input type="hidden" name="hilo_id" id="reply_hilo_id" value="<?= (int)$hiloSeleccionado ?>">
              <label for="respuesta">Responder</label>
              <textarea id="respuesta" name="cuerpo" rows="3" placeholder="Escribe tu respuesta…" required></textarea>

              <div class="row">
                <input type="file" name="adjunto" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                <div class="actions">
                  <button class="btn">Adjuntar</button>
                  <button class="btn primary">Enviar</button>
                  <button class="btn danger" formaction="revision_thread_resolve_action.php" onclick="return confirm('¿Marcar como resuelto?');">
                    ✓ Marcar como resuelto
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>

      <footer class="hint" style="margin-top:10px;color:#64748b">
        <small>El botón “Generar convenio” se habilitará cuando no existan comentarios abiertos y ambas aprobaciones estén activas.</small>
      </footer>
    </main>
  </div>

  <script>
    function toggleNewComment(){
      const box = document.getElementById('newComment');
      box.style.display = (box.style.display === 'none' || !box.style.display) ? 'block' : 'none';
    }
    function selectComment(id){
      // TODO: Cargar por AJAX el detalle del comentario seleccionado
      document.getElementById('reply_hilo_id').value = id;
      // Opcional: resaltar en la lista, recargar mensajes, etc.
    }
  </script>
</body>
</html>
