<?php
use Residencia\Controller\MachoteGlobal\MachoteGlobalController;

if (!isset($controller) || !($controller instanceof MachoteGlobalController)) {
    require __DIR__ . '/../../handler/machoteglobal/machote_edit_handler.php';
    return;
}

require_once __DIR__ . '/../../common/helpers/machoteglobal_helper.php';

$machoteData = is_array($machote) ? $machote : [];
$machoteId = $machoteData['id'] ?? ($id ?? null);
$versionValue = (string)($machoteData['version'] ?? '');
$descripcionValue = (string)($machoteData['descripcion'] ?? '');
$estadoValue = (string)($machoteData['estado'] ?? 'borrador');
$contenidoHtmlValue = (string)($machoteData['contenido_html'] ?? '');

$defaultHtml = <<<HTML
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
HTML;

if ($contenidoHtmlValue === '' && empty($machoteId)) {
    $contenidoHtmlValue = $defaultHtml;
}

$versionHtml = htmlspecialchars($versionValue, ENT_QUOTES, 'UTF-8');
$descripcionHtml = htmlspecialchars($descripcionValue, ENT_QUOTES, 'UTF-8');
$contenidoHtml = htmlspecialchars($contenidoHtmlValue, ENT_NOQUOTES, 'UTF-8');
$estadoSeleccionado = in_array($estadoValue, ['vigente', 'borrador', 'archivado'], true)
    ? $estadoValue
    : 'borrador';

$machotesListado = $machotesListado ?? [];
$notFound = ($machoteId !== null && $machote === null && $_SERVER['REQUEST_METHOD'] !== 'POST');
$actionUrl = 'machote_edit.php' . ($machoteId ? '?id=' . urlencode((string)$machoteId) : '');
?>
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
    .btn{border:1px solid var(--border); background:#fff; padding:.5rem .85rem; border-radius:10px; cursor:pointer; text-decoration:none; color:inherit}
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
    .alert{border-radius:10px; padding:12px 14px; margin:16px 0; display:flex; gap:10px; align-items:flex-start}
    .alert.error{background:#fee2e2; border:1px solid #fecaca; color:#991b1b}
    .alert.info{background:#e0f2fe; border:1px solid #bae6fd; color:#0c4a6e}

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
  </style>
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <!-- Topbar -->
      <div class="topbar">
        <div class="title">
          <h2>✏️ <?php echo $machoteId ? 'Editar Machote Global' : 'Nuevo Machote Global'; ?></h2>
          <p class="subtitle">Plantilla institucional base · Aquí se gestionan versiones (v1.0, v1.1, v2.0…)</p>
        </div>
        <div class="actions">
          <button type="submit" class="btn primary" form="machoteForm">💾 Guardar cambios</button>
          <button type="button" class="btn" id="btnPre" data-preview>👁️ Previsualizar</button>
          <a href="machote_global_list.php" class="btn">⬅ Volver</a>
        </div>
      </div>

      <?php if ($error) : ?>
        <div class="alert error">⚠️ <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
      <?php endif; ?>

      <?php if ($notFound) : ?>
        <div class="alert info">ℹ️ No se encontró el machote solicitado. Puedes crear una nueva versión.</div>
      <?php endif; ?>

      <form id="machoteForm" method="post" action="<?php echo $actionUrl; ?>">
        <input type="hidden" name="id" value="<?php echo $machoteId ? (int)$machoteId : ''; ?>" />

        <!-- Metadatos + Editor -->
        <section class="grid">
          <!-- Metadatos -->
          <div class="card">
            <header>🧩 Metadatos del machote</header>

            <div class="row">
              <div>
                <label for="version">Versión</label>
                <input type="text" id="version" name="version" placeholder="Ej. Inst v1.3" value="<?php echo $versionHtml; ?>" required />
              </div>
              <div>
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                  <option value="vigente" <?php echo $estadoSeleccionado === 'vigente' ? 'selected' : ''; ?>>Vigente</option>
                  <option value="borrador" <?php echo $estadoSeleccionado === 'borrador' ? 'selected' : ''; ?>>Borrador</option>
                  <option value="archivado" <?php echo $estadoSeleccionado === 'archivado' ? 'selected' : ''; ?>>Archivado</option>
                </select>
              </div>
              <div class="full">
                <label for="descripcion">Descripción corta</label>
                <input type="text" id="descripcion" name="descripcion" placeholder="Breve resumen de cambios institucionales…" value="<?php echo $descripcionHtml; ?>" />
              </div>
            </div>

            <p class="hint" style="margin-top:8px">
              💡 Usa variables entre llaves para datos dinámicos que se rellenarán al crear el convenio:
              <code>{{empresa_nombre}}</code>, <code>{{fecha_inicio}}</code>, <code>{{fecha_fin}}</code>, <code>{{direccion_empresa}}</code>.
            </p>
          </div>

          <!-- Acciones rápidas -->
          <div class="card">
            <header>🛠️ Acciones rápidas</header>
            <div class="row">
              <div>
                <button type="submit" class="btn small" form="machoteForm">💾 Guardar cambios</button>
              </div>
              <div>
                <button type="button" class="btn small" data-preview>👁️ Previsualizar</button>
              </div>
              <div class="full">
                <span class="hint">Estas acciones te permiten guardar la versión actual o visualizar el HTML antes de publicarlo.</span>
              </div>
            </div>
          </div>
        </section>

        <!-- Editor -->
        <section class="card" style="margin-top:16px">
          <header style="display:flex;justify-content:space-between;align-items:center;">
            <strong>🧾 Contenido del Machote Global</strong>
            <div style="display:flex; gap:6px">
              <button type="submit" class="btn small" form="machoteForm">💾 Guardar</button>
              <button type="button" class="btn small" data-preview>👁️ Previsualizar</button>
            </div>
          </header>

          <textarea id="editor" name="contenido_html"><?php echo $contenidoHtml; ?></textarea>
        </section>
      </form>

      <!-- Tabla de versiones -->
      <section class="card" style="margin-top:16px">
        <header>📚 Versiones del Machote Global registradas</header>
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Versión</th>
                <th>Estado</th>
                <th>Descripción</th>
                <th>Creado</th>
                <th>Actualizado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody id="verTable">
              <?php if (empty($machotesListado)) : ?>
                <tr>
                  <td colspan="7" style="text-align:center; padding:20px; color:#64748b;">Aún no hay machotes registrados.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($machotesListado as $index => $item) :
                    $listId = (int)($item['id'] ?? 0);
                    $listVersion = htmlspecialchars((string)($item['version'] ?? ''), ENT_QUOTES, 'UTF-8');
                    $listDescripcion = trim((string)($item['descripcion'] ?? ''));
                    $listDescripcion = $listDescripcion !== ''
                        ? nl2br(htmlspecialchars($listDescripcion, ENT_QUOTES, 'UTF-8'))
                        : '<span style="color:#64748b;">Sin descripción</span>';
                    $listEstado = machote_global_estado_badge((string)($item['estado'] ?? 'borrador'));
                    $listEstadoLabel = machote_global_estado_label((string)($item['estado'] ?? 'borrador'));
                    $listCreado = machote_global_format_datetime($item['creado_en'] ?? null);
                    $listActualizado = machote_global_format_datetime($item['actualizado_en'] ?? null);
                ?>
                  <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><strong><?php echo $listVersion !== '' ? $listVersion : '—'; ?></strong></td>
                    <td><span class="<?php echo $listEstado; ?>"><?php echo $listEstadoLabel; ?></span></td>
                    <td><?php echo $listDescripcion; ?></td>
                    <td><?php echo $listCreado; ?></td>
                    <td><?php echo $listActualizado; ?></td>
                    <td>
                      <a href="machote_edit.php?id=<?php echo $listId; ?>" class="btn small">✏️ Editar</a>
                      <a href="machote_revisar.php?id=<?php echo $listId; ?>" class="btn small">🔍 Ver revisiones</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
        <p class="hint" style="margin-top:8px">Información proveniente de <code>rp_machote</code> (versión, estado, descripción, creado_en, actualizado_en).</p>
      </section>
    </main>
  </div>

  <!-- CKEditor 5 (CDN) -->
  <script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
  <script>
    let editor;
    const form = document.getElementById('machoteForm');

    ClassicEditor
      .create(document.querySelector('#editor'), {
        toolbar: ['undo','redo','|','bold','italic','link','|','numberedList','bulletedList','|','insertTable','blockQuote']
      })
      .then(newEditor => {
        editor = newEditor;
      })
      .catch(console.error);

    const previewButtons = document.querySelectorAll('[data-preview]');
    function previewDraft(){
      if (!editor) {
        return;
      }
      const w = window.open('', '_blank');
      const html = editor.getData();
      w.document.write(`<!doctype html><html><head><meta charset="utf-8"><title>Previsualización · ${document.getElementById('version').value}</title></head><body>${html}</body></html>`);
      w.document.close();
    }

    previewButtons.forEach(btn => btn.addEventListener('click', (event) => {
      event.preventDefault();
      previewDraft();
    }));

    if (form) {
      form.addEventListener('submit', () => {
        if (editor) {
          editor.updateSourceElement();
        }
      });
    }
  </script>
</body>
</html>
