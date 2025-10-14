
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>📝 Revisión de Machote · Residencias</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/css/machote/revisar.css">
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📝 Revisión de Machote</h2>
          <p class="subtitle">
            Empresa: <strong>Casa del Barrio</strong> · Revisión <strong>#123</strong> · Documento: <strong>Institucional v1.2</strong> ·
            Estado: <span class="badge en_revision">En revisión</span>
          </p>
        </div>
        <div class="actions">
          <form action="revision_generate_convenio_action.php" method="post" style="display:inline;">
            <input type="hidden" name="revision_id" value="123">
            <button class="btn primary" disabled title="Se habilita cuando no hay comentarios abiertos y ambas aprobaciones están activas">
              📄 Generar convenio
            </button>
          </form>
          <a href="../empresas/view.php?id=45" class="btn secondary">⬅ Volver a la empresa</a>
        </div>
      </header>

      <!-- Indicadores y aprobaciones -->
      <section class="kpis card">
        <div class="kpi">
          <h4>Comentarios abiertos</h4>
          <div class="kpi-num">1</div>
        </div>
        <div class="kpi">
          <h4>Comentarios resueltos</h4>
          <div class="kpi-num">3</div>
        </div>
        <div class="kpi wide">
          <h4>Avance de la revisión</h4>
          <div class="progress"><div class="bar" style="width: 75%"></div></div>
          <small>75% completado</small>
        </div>
        <div class="approvals">
          <form action="revision_toggle_approve_action.php" method="post" class="switch">
            <input type="hidden" name="revision_id" value="123">
            <input type="hidden" name="who" value="admin">
            <input type="checkbox" id="aprob_admin" name="state" disabled>
            <span class="slider"></span><label class="label" for="aprob_admin">Aprobación del administrador</label>
          </form>

          <form action="revision_toggle_approve_action.php" method="post" class="switch">
            <input type="hidden" name="revision_id" value="123">
            <input type="hidden" name="who" value="empresa">
            <input type="checkbox" id="aprob_empresa" name="state" disabled>
            <span class="slider"></span><label class="label" for="aprob_empresa">Aprobación de la empresa</label>
          </form>
        </div>
      </section>

      <!-- Nuevo comentario -->
      <section class="card">
        <header style="display:flex;justify-content:space-between;align-items:center;">
          <span>💬 Nuevo comentario</span>
          <button type="button" class="btn" onclick="toggleNewComment()">➕ Mostrar/Ocultar</button>
        </header>
        <div class="content" id="newComment" style="display:none;">
          <form class="grid-2" action="revision_thread_create_action.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="revision_id" value="123">

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

      <!-- Listado (izquierda) + Detalle (derecha) -->
      <section class="split">
        <!-- Lista de comentarios -->
        <div class="card">
          <header style="display:flex;justify-content:space-between;align-items:center;">
            <span>💬 Comentarios</span>
            <div class="filters">
              <a href="#" class="chip active">Abiertos (1)</a>
              <a href="#" class="chip">Resueltos (3)</a>
              <a href="#" class="chip">Todos (4)</a>
            </div>
          </header>

          <div class="content threads">
            <!-- Ejemplo: comentario abierto -->
            <article class="thread active">
              <div class="meta">
                <span class="badge abierto">Abierto</span>
                <span class="author empresa">Empresa</span>
                <span class="time">hace 2 días</span>
              </div>
              <h4>Ajustar vigencia</h4>
              <p>Proponemos 18 meses por motivos de calendario…</p>
              <div class="row-actions">
                <button class="btn small" onclick="selectComment(101)">👁️ Ver detalle</button>
                <button class="btn small">📎 Archivos (1)</button>
                <form action="revision_thread_resolve_action.php" method="post" style="display:inline;" onsubmit="return confirm('¿Marcar como resuelto?');">
                  <input type="hidden" name="revision_id" value="123">
                  <input type="hidden" name="hilo_id" value="101">
                  <button class="btn small danger">✓ Marcar como resuelto</button>
                </form>
              </div>
            </article>

            <!-- Ejemplo: comentario resuelto -->
            <article class="thread">
              <div class="meta">
                <span class="badge resuelto">Resuelto</span>
                <span class="author admin">Administrador</span>
                <span class="time">hace 5 días</span>
              </div>
              <h4>Confidencialidad</h4>
              <p>Se integró un anexo de confidencialidad estándar.</p>
              <div class="row-actions">
                <button class="btn small" onclick="selectComment(99)">👁️ Ver detalle</button>
                <button class="btn small">📎 Archivos (0)</button>
                <form action="revision_thread_reopen_action.php" method="post" style="display:inline;">
                  <input type="hidden" name="revision_id" value="123">
                  <input type="hidden" name="hilo_id" value="99">
                  <button class="btn small">↺ Reabrir comentario</button>
                </form>
              </div>
            </article>

            <!-- Estado vacío (si aplica)
            <div class="empty">
              <p>Aún no hay comentarios. Crea el primero.</p>
              <button class="btn" onclick="toggleNewComment()">➕ Crear comentario</button>
            </div> -->
          </div>
        </div>

        <!-- Detalle del comentario seleccionado -->
        <div class="card">
          <header>📌 Detalle del comentario</header>
          <div class="content thread-detail" id="detailBox" aria-live="polite">
            <div class="message">
              <div class="head"><span class="pill empresa">Empresa</span><time>2025-10-01 10:12</time></div>
              <p>Proponemos 18 meses por calendario de proyectos…</p>
              <div class="files"><a href="#">borrador_v1.pdf</a></div>
            </div>
            <div class="message">
              <div class="head"><span class="pill admin">Administrador</span><time>2025-10-01 12:20</time></div>
              <p>Recibido. Prepararemos la versión 2 y la compartimos hoy.</p>
            </div>

            <!-- Responder -->
            <form class="reply" action="revision_reply_create_action.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="revision_id" value="123">
              <input type="hidden" name="hilo_id" id="reply_hilo_id" value="101">
              <label for="respuesta">Responder</label>
              <textarea id="respuesta" name="cuerpo" rows="3" placeholder="Escribe tu respuesta…" required></textarea>

              <div class="row">
                <input type="file" name="adjunto" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                <div class="actions">
                  <button class="btn">Adjuntar</button>
                  <button class="btn primary">Enviar</button>
                  <button class="btn danger" formaction="revision_thread_resolve_action.php" onclick="return confirm('¿Marcar como resuelto?');">✓ Marcar como resuelto</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>

      <footer class="hint">
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
      // Aquí puedes cargar por AJAX el detalle del comentario seleccionado.
      document.getElementById('reply_hilo_id').value = id;
    }
  </script>
</body>
</html>
