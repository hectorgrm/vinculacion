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

// Solo cargar el machote oficial si es NUEVO (no hay contenido en BD)
if (empty($machoteId) && trim($contenidoHtmlValue) === '') {
    $templatePath = __DIR__ . '/../../templates/machote_oficial_v1_content.html';
    if (file_exists($templatePath)) {
        $contenidoHtmlValue = file_get_contents($templatePath);
    } else {
        $contenidoHtmlValue = '<p style="color:red;">⚠️ No se encontró la plantilla oficial (machote_oficial_v1_content.html)</p>';
    }
}

$versionHtml = htmlspecialchars($versionValue, ENT_QUOTES, 'UTF-8');
$descripcionHtml = htmlspecialchars($descripcionValue, ENT_QUOTES, 'UTF-8');
$contenidoHtml = $contenidoHtmlValue; // ✅ NO ESCAPAR: CKEditor necesita HTML limpio

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
  <link rel="stylesheet" href="../../assets/css/machoteglobal/machoteglobaledit.css" />
  <link rel="stylesheet" href="../../templates/machote_oficial_v1_content.css" /> <!-- ✅ Carga estilos institucionales -->
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
        toolbar: [
          'undo', 'redo', '|',
          'bold', 'italic', 'link', '|',
          'numberedList', 'bulletedList', '|',
          'insertTable', 'blockQuote'
        ],
        ckfinder: { uploadUrl: '' },
        heading: { options: [
          { model: 'paragraph', title: 'Párrafo', class: 'ck-heading_paragraph' },
          { model: 'heading2', view: 'h2', title: 'Título 2', class: 'ck-heading_heading2' },
          { model: 'heading3', view: 'h3', title: 'Título 3', class: 'ck-heading_heading3' }
        ]},
        fontSize: { options: [ 'default', 11, 12, 13, 14, 16 ] }
      })
      .then(newEditor => {
        editor = newEditor;
      })
      .catch(console.error);

    const previewButtons = document.querySelectorAll('[data-preview]');
    function previewDraft() {
      if (!editor) return;
      const w = window.open('', '_blank');
      const html = editor.getData();
      w.document.write(`
        <!doctype html>
        <html lang="es">
          <head>
            <meta charset="utf-8">
            <title>Previsualización · ${document.getElementById('version').value}</title>
            <link rel="stylesheet" href="../../templates/machote_oficial_v1_content.css">
          </head>
          <body>${html}</body>
        </html>
      `);
      w.document.close();
    }

    previewButtons.forEach(btn => btn.addEventListener('click', e => {
      e.preventDefault();
      previewDraft();
    }));

    if (form) {
      form.addEventListener('submit', () => {
        if (editor) editor.updateSourceElement();
      });
    }
  </script>
</body>
</html>
