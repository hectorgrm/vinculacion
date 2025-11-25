<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>✏️ Revisión de Machote · Vinculación</title>

  <!-- Tus estilos globales -->
  <link rel="stylesheet" href="../../assets/css/modules/machote.css" />

  <style>
    :root{
      --bg:#f6f7fb; --card:#fff; --border:#e5e7eb; --text:#334155;
      --primary:#0d6efd; --success:#16a34a; --warn:#f59e0b; --danger:#ef4444;
      --radius:12px;
    }
    body{background:var(--bg); color:var(--text)}
    .app{min-height:100vh}
    .machote-app{
      display:grid; grid-template-columns: 360px 1fr; gap:16px; padding:16px;
    }
    @media (max-width:1024px){ .machote-app{grid-template-columns:1fr} }

    /* Topbar */
    .topbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; background:#fff; border-bottom:1px solid var(--border)}
    .title h2{margin:0}
    .subtitle{margin:4px 0 0 0; color:#64748b}
    .top-actions{display:flex; gap:8px; flex-wrap:wrap}
    .btn{border:1px solid var(--border); background:#fff; padding:.5rem .85rem; border-radius:10px; cursor:pointer}
    .btn:hover{background:#f5f5f5}
    .btn.primary{background:var(--primary); color:#fff; border-color:var(--primary)}
    .btn.success{background:var(--success); color:#fff; border-color:var(--success)}
    .btn.warn{background:var(--warn); color:#fff; border-color:var(--warn)}
    .btn.danger{background:var(--danger); color:#fff; border-color:var(--danger)}
    .btn.small{padding:.35rem .6rem; border-radius:8px; font-size:.9rem}

    /* Panel izquierdo: comentarios */
    .panel{
      background:var(--card); border:1px solid var(--border); border-radius:var(--radius); overflow:hidden;
      display:flex; flex-direction:column; min-height: calc(100dvh - 180px);
    }
    .panel header{padding:12px 16px; border-bottom:1px solid var(--border); background:#fff; display:flex; justify-content:space-between; align-items:center}
    .panel .toolbar{display:flex; gap:6px; flex-wrap:wrap}
    .chip{border:1px solid var(--border); padding:4px 8px; border-radius:999px; font-size:.85rem; color:#475569; text-decoration:none}
    .chip.active{background:#eef2ff; border-color:#c7d2fe; color:#3730a3}

    .threads{padding:10px 10px 0 10px; overflow:auto}
    .thread{
      border:1px solid var(--border); border-radius:12px; padding:10px; margin-bottom:10px; background:#fff;
    }
    .thread.active{background:#f8fafc; border-color:#dbeafe}
    .meta{display:flex; gap:8px; align-items:center; color:#64748b; font-size:.82rem; margin-bottom:6px}
    .badge{border-radius:999px; padding:2px 8px; font-weight:700; font-size:.75rem; color:#fff}
    .badge.abierto{background:#f59e0b}
    .badge.en_revision{background:#fb923c}
    .badge.resuelto{background:#16a34a}
    .who.admin{color:#0f172a; font-weight:700}
    .who.empresa{color:#1e40af; font-weight:700}
    .row-actions{display:flex; gap:8px; flex-wrap:wrap; margin-top:8px}

    .new-comment{border-top:1px solid var(--border); padding:12px 16px; background:#fafafa}
    .new-comment form{display:grid; grid-template-columns:1fr; gap:8px}
    .new-comment input, .new-comment textarea{border:1px solid #cbd5e1; border-radius:10px; padding:8px; font-family:inherit}

    /* Panel derecho: editor */
    .editor-panel{display:flex; flex-direction:column; gap:16px}
    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:14px}
    .kpis{display:grid; grid-template-columns:repeat(4,1fr); gap:12px}
    .kpi{border:1px solid var(--border); border-radius:12px; padding:10px; background:#fff}
    .kpi h4{margin:0 0 6px 0; font-size:.92rem; color:#475569}
    .kpi-num{font-weight:800; font-size:1.25rem}
    .kpi.wide{grid-column:span 2}
    .progress{width:100%; height:10px; background:#e5e7eb; border-radius:6px; overflow:hidden}
    .bar{height:100%; background:#16a34a}

    .ck-editor__editable{min-height:480px; border-radius:12px}
    .save-toast{
      position:fixed; right:20px; bottom:20px; background:#16a34a; color:#fff; padding:.6rem 1rem; border-radius:10px; display:none;
      box-shadow:0 8px 20px rgba(2,6,23,.18); z-index:10
    }
    .hint{color:#64748b; font-size:.9rem}
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <!-- Topbar -->
      <div class="topbar">
        <div class="title">
          <h2>✏️ Revisión de Machote</h2>
          <p class="subtitle">
            Empresa: <strong>Bonafont</strong> · Revisión <strong>#123</strong> · Documento: <strong>Inst v1.3</strong> ·
            Estado: <span class="badge en_revision">En revisión</span>
          </p>
        </div>
        <div class="top-actions">
          <button id="btnGuardar" class="btn">💾 Guardar</button>
          <button class="btn primary">✅ Aprobar</button>
          <button class="btn">📄 Generar PDF</button>
          <a href="machote_revisar.php" class="btn">⬅ Volver</a>
        </div>
      </div>

      <!-- Layout principal -->
      <div class="machote-app">
        <!-- Columna izquierda: Comentarios -->
        <aside class="panel">
          <header>
            <strong>💬 Comentarios</strong>
            <div class="toolbar">
              <a href="#" class="chip active">Abiertos (2)</a>
              <a href="#" class="chip">Resueltos (3)</a>
              <a href="#" class="chip">Todos (5)</a>
            </div>
          </header>

          <div class="threads" id="threadList">
            <!-- Ticket 1 -->
            <article class="thread active">
              <div class="meta">
                <span class="badge abierto">Abierto</span>
                <span class="who empresa">Empresa</span>
                <span>· hace 2 días</span>
              </div>
              <h4>Ajustar vigencia</h4>
              <p>Proponemos 18 meses por calendario…</p>
              <div class="row-actions">
                <button class="btn small" onclick="selectThread(101)">👁️ Ver detalle</button>
                <button class="btn small">📎 Archivos (1)</button>
                <button class="btn small warn" onclick="markInReview(101)">🛠️ Marcar en revisión</button>
                <button class="btn small success" onclick="resolveThread(101)">✓ Marcar resuelto</button>
              </div>
            </article>

            <!-- Ticket 2 -->
            <article class="thread">
              <div class="meta">
                <span class="badge resuelto">Resuelto</span>
                <span class="who admin">Administrador</span>
                <span>· hace 5 días</span>
              </div>
              <h4>Confidencialidad</h4>
              <p>Se integró un anexo de confidencialidad estándar.</p>
              <div class="row-actions">
                <button class="btn small" onclick="selectThread(99)">👁️ Ver detalle</button>
                <button class="btn small">📎 Archivos (0)</button>
                <button class="btn small">↺ Reabrir</button>
              </div>
            </article>
          </div>

          <div class="new-comment">
            <details>
              <summary class="btn small">➕ Nuevo comentario</summary>
              <form style="margin-top:10px">
                <label>Título</label>
                <input type="text" placeholder="Ej. Vigencia del convenio" required>
                <label>Descripción</label>
                <textarea rows="3" placeholder="Describe el cambio o aclaración…" required></textarea>
                <div style="display:flex; gap:8px; align-items:center; margin-top:6px;">
                  <input type="file" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg">
                  <button class="btn primary small" type="button">Publicar</button>
                </div>
              </form>
            </details>
          </div>
        </aside>

        <!-- Columna derecha: KPIs + Editor -->
        <section class="editor-panel">
          <!-- KPIs -->
          <div class="card">
            <div class="kpis">
              <div class="kpi">
                <h4>Comentarios abiertos</h4>
                <div class="kpi-num">2</div>
              </div>
              <div class="kpi">
                <h4>Comentarios resueltos</h4>
                <div class="kpi-num">3</div>
              </div>
              <div class="kpi wide">
                <h4>Avance de la revisión</h4>
                <div class="progress"><div class="bar" style="width: 70%"></div></div>
                <small>70% completado</small>
              </div>
              <div class="kpi">
                <h4>Aprobaciones</h4>
                <div class="hint">Admin: ❌ · Empresa: ❌</div>
              </div>
            </div>
          </div>

          <!-- Editor -->
          <div class="card">
            <header style="margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
              <strong>🧾 Editor de machote (Inst v1.3)</strong>
              <div style="display:flex; gap:6px">
                <button id="btnGuardar2" class="btn small">💾 Guardar</button>
                <button class="btn small">👁️ Previsualizar</button>
              </div>
            </header>

            <textarea id="editor">
              <h2 style="text-align:center;">Convenio de Colaboración para la Realización de Residencias Profesionales</h2>
              <p>Celebran por una parte el <strong>Instituto Tecnológico de Ejemplo</strong> y por la otra la empresa <strong>{{empresa_nombre}}</strong>, al tenor de las siguientes cláusulas:</p>

              <h3>Cláusula Primera: Objeto</h3>
              <p>El presente convenio tiene por objeto establecer las bases de colaboración entre las partes para que estudiantes realicen su residencia profesional.</p>

              <h3>Cláusula Segunda: Obligaciones de la Empresa</h3>
              <ul>
                <li>Designar un responsable técnico.</li>
                <li>Proporcionar medios, información y apoyo.</li>
                <li>Permitir evaluación de avances.</li>
              </ul>

              <h3>Cláusula de Confidencialidad</h3>
              <p>La información compartida con motivo de la residencia será tratada de forma confidencial y no divulgada sin consentimiento.</p>

              <p style="margin-top:24px"><em>Campos dinámicos: {{empresa_nombre}}, {{fecha_inicio}}, {{fecha_fin}}, {{direccion_empresa}}.</em></p>
            </textarea>

            <p class="hint" style="margin-top:10px">
              💡 Sugerencia: usa variables entre llaves (ej. <code>{{empresa_nombre}}</code>) para datos que llenarás automáticamente al generar el PDF final.
            </p>
          </div>
        </section>
      </div>

      <div id="saveToast" class="save-toast">✅ Cambios guardados (simulado)</div>
    </main>
  </div>

  <!-- CKEditor 5 (CDN) -->
  <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
  <script>
    // ===== Simulación de datos / claves =====
    const revisionId = 123; // cuando conectes backend, vendrá por GET/BD
    const STORAGE_KEY = 'machoteDraft_' + revisionId;

    let editor;
    ClassicEditor
      .create(document.querySelector('#editor'), {
        toolbar: ['undo','redo','|','bold','italic','link','|','numberedList','bulletedList','|','insertTable','|','blockQuote']
      })
      .then(newEditor => {
        editor = newEditor;
        // Cargar borrador guardado (si existe)
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) editor.setData(saved);
      })
      .catch(console.error);

    function showSaved(){
      const toast = document.getElementById('saveToast');
      toast.style.display = 'block';
      setTimeout(()=> toast.style.display = 'none', 1800);
    }

    function saveDraft(){
      if (!editor) return;
      const html = editor.getData();
      localStorage.setItem(STORAGE_KEY, html);
      showSaved();
    }

    document.getElementById('btnGuardar').addEventListener('click', saveDraft);
    document.getElementById('btnGuardar2').addEventListener('click', saveDraft);

    // ===== Acciones de los tickets (solo UI) =====
    function selectThread(id){
      // Aquí, cuando conectes backend, cargarás el hilo via fetch/AJAX
      console.log('Seleccionar hilo', id);
      // UI: resaltar (mock)
      [...document.querySelectorAll('.thread')].forEach(t => t.classList.remove('active'));
      const first = document.querySelector('.thread'); if (first) first.classList.add('active');
    }
    function markInReview(id){
      alert('Marcar hilo #' + id + ' como EN REVISIÓN (UI demo).');
    }
    function resolveThread(id){
      alert('Marcar hilo #' + id + ' como RESUELTO (UI demo).');
    }
  </script>
</body>
</html>
