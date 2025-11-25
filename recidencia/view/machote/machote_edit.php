<?php
declare(strict_types=1);
error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once __DIR__ . '/../../../common/model/db.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

use Common\Model\Database;
use Residencia\Model\Convenio\ConvenioMachoteModel;

// ID del machote
$machoteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$machoteId) {
    exit('ID de machote no válido.');
}

try {
    // Instanciar modelo con conexión por defecto
    $machoteModel = ConvenioMachoteModel::createWithDefaultConnection();
    $machote = $machoteModel->getById($machoteId);
} catch (Throwable $e) {
    error_log('Error en machote_edit.php: ' . $e->getMessage());
    exit('Error interno al cargar el machote.');
}

if (!$machote) {
    exit('Machote no encontrado.');
}

// Variables
$contenido = $machote['contenido_html'] ?? '';
$convenioId = $machote['convenio_id'] ?? null;
$contenidoEscapado = $contenido;
$machoteConfirmado = (int) ($machote['confirmacion_empresa'] ?? 0) === 1;
$machoteBloqueado = $machoteConfirmado;

$editorAction = '../../handler/machote/machote_update_handler.php';
$volverUrl = $convenioId
    ? '../convenio/convenio_view.php?id=' . urlencode((string)$convenioId)
    : '../convenio/convenio_list.php';
$pdfUrl = '../../handler/machote/machote_generate_pdf.php?id=' . urlencode((string)$machoteId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Machote · Convenio <?= htmlspecialchars((string)$convenioId) ?></title>
      <link rel="stylesheet" href="../../assets/css/modules/machote.css" />
   
</head>

<body data-machote-bloqueado="<?= $machoteBloqueado ? '1' : '0' ?>">
<div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
        <header class="topbar">
            <h2>📝 Editar Machote del Convenio #<?= htmlspecialchars((string)$convenioId) ?></h2>
            <a href="<?= htmlspecialchars($volverUrl) ?>" class="btn">← Volver al Convenio</a>
        </header>

        <section class="card card-editable">
            <header>Documento Editable</header>
            <div class="content">
                <?php if ($machoteBloqueado): ?>
                    <p class="text-muted" style="margin:0 0 12px;">El machote ya fue confirmado por la empresa. Reabre la revisión para habilitar nuevamente la edición.</p>
                <?php endif; ?>
                <form method="POST" action="<?= htmlspecialchars($editorAction) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$machoteId) ?>">
                    <div class="editor-shell">
                        <textarea name="contenido" id="editor" rows="30"><?= $contenidoEscapado ?></textarea>
                    </div>

                    <div class="actions" style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;">
                        <button type="submit" class="btn primary" <?= $machoteBloqueado ? 'disabled' : '' ?>>💾 Guardar Machote</button>
                        <a href="<?= htmlspecialchars($pdfUrl) ?>" class="btn btn-outline" target="_blank">📄 Generar PDF</a>
                        <a href="<?= htmlspecialchars($volverUrl) ?>" class="btn">Cancelar</a>
                    </div>
                </form>
            </div>
        </section>
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
  const form = document.querySelector('form');
  if (form) {
    form.addEventListener('submit', (event) => {
      if (isReadOnly) {
        event.preventDefault();
        return false;
      }

      if (editor) form.querySelector('#editor').value = editor.getData();
    });
  }
</script>

</body>
</html>
