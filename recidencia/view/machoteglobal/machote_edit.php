<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>✏️ Editar Machote Global · Vinculación</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <!-- Puedes crear un CSS específico si lo deseas: ../../assets/css/machote/machote_edit.css -->

  <style>
    :root{
      --bg:#f6f7fb; --card:#fff; --border:#e5e7eb; --text:#334155;
      --primary:#0d6efd; --success:#16a34a; --warn:#f59e0b; --danger:#ef4444;
      --radius:12px;
    }
    body{background:var(--bg); color:var(--text)}
    .app{min-height:100vh}
    .topbar{display:flex; justify-content:space-between; align-items:center; padding:16px 20px; background:#fff; border-bottom:1px solid var(--border)}
    .title h2{margin:0}
    .subtitle{margin:4px 0 0 0; color:#64748b}
    .actions{display:flex; gap:8px; flex-wrap:wrap}
    .btn{border:1px solid var(--border); background:#fff; padding:.5rem .85rem; border-radius:10px; cursor:pointer}
    .btn:hover{background:#f5f5f5}
    .btn.primary{background:var(--primary); color:#fff; border-color:var(--primary)}
    .btn.success{background:var(--success); color:#fff; border-color:var(--success)}
    .btn.warn{background:var(--warn); color:#fff; border-color:var(--warn)}
    .btn.danger{background:var(--danger); color:#fff; border-color:var(--danger)}
    .btn.small{padding:.35rem .6rem; border-radius:8px; font-size:.92rem}

    main.main{padding:16px}
    .grid{display:grid; grid-template-columns:1fr 1fr; gap:16px}
    @media (max-width: 1024px){ .grid{grid-template-columns:1fr} }

    .card{background:#fff; border:1px solid var(--border); border-radius:var(--radius); padding:14px}
    .card header{font-weight:700; margin-bottom:10px}
    .row{display:grid; grid-template-columns:1fr 1fr; gap:12px}
    .row .full{grid-column:1/-1}
    label{font-size:.92rem; color:#475569; margin-bottom:4px; display:block}
    input[type="text"], textarea, select{
      width:100%; border:1px solid #cbd5e1; border-radius:10px; padding:8px; font-family:inherit
    }
    .hint{color:#64748b; font-size:.9rem}

    /* Tabla de versiones */
    table{width:100%; border-collapse:separate; border-spacing:0; overflow:hidden}
    thead th{background:#f1f5f9; text-align:left; padding:10px; font-size:.9rem; color:#475569}
    tbody td{padding:10px; border-top:1px solid var(--border)}
    tbody tr:hover{background:#f8fafc}
    .badge{border-radius:999px; padding:2px 8px; font-weight:700; font-size:.75rem; color:#fff}
    .badge.vigente{background:#16a34a}
    .badge.archivado{background:#64748b}
    .badge.borrador{background:#f59e0b}

    .ck-editor__editable{min-height:560px; border-radius:12px}
    .save-toast{
      position:fixed; right:20px; bottom:20px; background:#16a34a; color:#fff; padding:.6rem 1rem; border-radius:10px; display:none;
      box-shadow:0 8px 20px rgba(2,6,23,.18); z-index:10
    }
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <!-- Topbar -->
      <div class="topbar">
        <div class="title">
          <h2>✏️ Editar Machote Global</h2>
          <p class="subtitle">Plantilla institucional base · Aquí se gestionan versiones (v1.0, v1.1, v2.0…)</p>
        </div>
        <div class="actions">
          <button id="btnGuardar" class="btn">💾 Guardar borrador</button>
          <button class="btn primary" title="(Demo) Activar como vigente" disabled>✅ Marcar como vigente</button>
          <button id="btnPre" class="btn">👁️ Previsualizar</button>
          <a href="machote_list.php" class="btn">⬅ Volver</a>
        </div>
      </div>

      <!-- Metadatos + Editor -->
      <section class="grid">
        <!-- Metadatos -->
        <div class="card">
          <header>🧩 Metadatos del machote</header>

          <div class="row">
            <div>
              <label for="version">Versión</label>
              <input type="text" id="version" placeholder="Ej. Inst v1.3" value="Inst v1.3" />
            </div>
            <div>
              <label for="estado">Estado</label>
              <select id="estado">
                <option value="vigente" selected>Vigente</option>
                <option value="borrador">Borrador</option>
                <option value="archivado">Archivado</option>
              </select>
            </div>
            <div class="full">
              <label for="descripcion">Descripción corta</label>
              <input type="text" id="descripcion" placeholder="Breve resumen de cambios institucionales…" value="Versión vigente con cláusula de confidencialidad actualizada." />
            </div>
          </div>

          <p class="hint" style="margin-top:8px">
            💡 Usa variables entre llaves para datos dinámicos que se rellenarán al crear el convenio: 
            <code>{{empresa_nombre}}</code>, <code>{{fecha_inicio}}</code>, <code>{{fecha_fin}}</code>, <code>{{direccion_empresa}}</code>.
          </p>
        </div>

        <!-- Acciones rápidas (demo UI) -->
        <div class="card">
          <header>🛠️ Acciones rápidas</header>
          <div class="row">
            <div>
              <button class="btn small" id="btnDuplicar" title="(Demo) Duplica el texto actual en localStorage">📄 Duplicar versión</button>
            </div>
            <div>
              <button class="btn small" id="btnLimpiar" title="(Demo) Limpia el borrador del localStorage">🧹 Limpiar borrador</button>
            </div>
            <div class="full">
              <span class="hint">Estas acciones son de demostración (no hay backend todavía). En la fase de conexión, se convertirán en endpoints.</span>
            </div>
          </div>
        </div>
      </section>

      <!-- Editor -->
      <section class="card" style="margin-top:16px">
        <header style="display:flex;justify-content:space-between;align-items:center;">
          <strong>🧾 Contenido del Machote Global</strong>
          <div style="display:flex; gap:6px">
            <button id="btnGuardar2" class="btn small">💾 Guardar</button>
            <button id="btnPre2" class="btn small">👁️ Previsualizar</button>
          </div>
        </header>

        <textarea id="editor">
          <h2 style="text-align:center;">Convenio de Colaboración para la Realización de Residencias Profesionales</h2>
          <p>Celebran por una parte el <strong>Instituto Tecnológico de Ejemplo</strong> y por la otra la empresa <strong>{{empresa_nombre}}</strong>, quienes convienen lo siguiente:</p>

          <h3>Cláusula Primera: Objeto</h3>
          <p>El presente machote establece las bases de colaboración para residencias profesionales realizadas por estudiantes de la institución.</p>

          <h3>Cláusula Segunda: Obligaciones de la Empresa</h3>
          <ul>
            <li>Designar un responsable técnico.</li>
            <li>Proporcionar medios y apoyos necesarios.</li>
            <li>Permitir seguimiento y evaluación.</li>
          </ul>

          <h3>Cláusula de Confidencialidad</h3>
          <p>La información compartida durante la residencia será confidencial y no podrá divulgarse sin consentimiento de ambas partes.</p>

          <h3>Vigencia</h3>
          <p>La vigencia del convenio será del <strong>{{fecha_inicio}}</strong> al <strong>{{fecha_fin}}</strong>.</p>

          <p style="margin-top:24px"><em>Variables disponibles: {{empresa_nombre}}, {{fecha_inicio}}, {{fecha_fin}}, {{direccion_empresa}}, {{rfc_empresa}}.</em></p>
        </textarea>
      </section>

      <!-- Tabla de versiones (demo, estática) -->
      <section class="card" style="margin-top:16px">
        <header>📚 Versiones del Machote Global (ejemplo)</header>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Versión</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Creado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="verTable">
              <tr>
                <td>1</td>
                <td>Inst v1.3</td>
                <td><span class="badge vigente">Vigente</span></td>
                <td>Cláusula de confidencialidad actualizada</td>
                <td>2025-11-01</td>
                <td>
                  <button class="btn small">✏️ Editar</button>
                  <button class="btn small" title="(Demo) Ver revisiones">🔍 Revisiones</button>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Inst v1.2</td>
                <td><span class="badge archivado">Archivado</span></td>
                <td>Formato anterior</td>
                <td>2025-05-10</td>
                <td>
                  <button class="btn small" disabled>✏️ Editar</button>
                  <button class="btn small">👁️ Ver</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <p class="hint" style="margin-top:8px">Cuando conectes el backend, esta tabla se llenará con <code>rp_machote</code> (version, estado, descripción, creado_en).</p>
      </section>

      <div id="saveToast" class="save-toast">✅ Borrador guardado (local)</div>
    </main>
  </div>

  <!-- CKEditor 5 (CDN) -->
  <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
  <script>
    // ===== Simulación de almacenamiento local (sin backend) =====
    // Clave de almacenamiento basada en la versión para no mezclar borradores.
    const versionInput = document.getElementById('version');
    const estadoInput  = document.getElementById('estado');
    const descInput    = document.getElementById('descripcion');

    function storageKey(){
      const v = (versionInput.value || 'Inst vX').replace(/\s+/g, '_');
      return 'MachoteGlobal_' + v;
    }

    let editor;
    ClassicEditor
      .create(document.querySelector('#editor'), {
        toolbar: ['undo','redo','|','bold','italic','link','|','numberedList','bulletedList','|','insertTable','blockQuote']
      })
      .then(newEditor => {
        editor = newEditor;
        // Cargar borrador si existe
        const saved = localStorage.getItem(storageKey());
        if (saved) editor.setData(saved);
      })
      .catch(console.error);

    const toast = document.getElementById('saveToast');
    function showSaved(){
      toast.style.display = 'block';
      setTimeout(()=> toast.style.display = 'none', 1600);
    }

    function saveDraft(){
      if (!editor) return;
      const data = {
        version: versionInput.value,
        estado : estadoInput.value,
        descripcion: descInput.value,
        html: editor.getData(),
        saved_at: new Date().toISOString()
      };
      localStorage.setItem(storageKey(), JSON.stringify(data));
      showSaved();
    }

    function previewDraft(){
      // Abre una nueva pestaña con el HTML actual (simple previsualización)
      const w = window.open('', '_blank');
      const html = editor ? editor.getData() : '';
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Previsualización · ${versionInput.value}</title></head><body>${html}</body></html>`);
      w.document.close();
    }

    // Botones
    document.getElementById('btnGuardar').addEventListener('click', saveDraft);
    document.getElementById('btnGuardar2').addEventListener('click', saveDraft);
    document.getElementById('btnPre').addEventListener('click', previewDraft);
    document.getElementById('btnPre2').addEventListener('click', previewDraft);

    // Acciones demo
    document.getElementById('btnDuplicar').addEventListener('click', () => {
      // Guarda otro borrador con sufijo _copy (solo demostración)
      if (!editor) return;
      const baseKey = storageKey();
      const copyKey = baseKey + '_copy';
      const current = localStorage.getItem(baseKey);
      localStorage.setItem(copyKey, current || JSON.stringify({
        version: versionInput.value + ' (copy)',
        estado: estadoInput.value,
        descripcion: descInput.value,
        html: editor.getData(),
        saved_at: new Date().toISOString()
      }));
      alert('Versión duplicada (localStorage).');
    });

    document.getElementById('btnLimpiar').addEventListener('click', () => {
      localStorage.removeItem(storageKey());
      alert('Borrador eliminado del localStorage para esta versión.');
    });
  </script>
</body>
</html>
