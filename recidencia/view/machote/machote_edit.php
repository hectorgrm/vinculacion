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
$contenidoEscapado = htmlspecialchars($contenido, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

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
    <link rel="stylesheet" href="../../assets/stylesrecidencia.css">
    <link rel="stylesheet" href="../../assets/css/machote/machote_edit.css">
    <script src="../../assets/ckeditor/ckeditor.js"></script>
</head>

<body>
<div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
        <header class="topbar">
            <h2>📝 Editar Machote del Convenio #<?= htmlspecialchars((string)$convenioId) ?></h2>
            <a href="<?= htmlspecialchars($volverUrl) ?>" class="btn">← Volver al Convenio</a>
        </header>

        <section class="card">
            <header>Documento Editable</header>
            <div class="content">
                <form method="POST" action="<?= htmlspecialchars($editorAction) ?>">
                    <input type="hidden" name="id" value="<?= htmlspecialchars((string)$machoteId) ?>">
                    <textarea name="contenido" id="editor" rows="30"><?= $contenidoEscapado ?></textarea>

                    <div class="actions" style="margin-top:16px;display:flex;gap:12px;flex-wrap:wrap;">
                        <button type="submit" class="btn primary">💾 Guardar Machote</button>
                        <a href="<?= htmlspecialchars($pdfUrl) ?>" class="btn btn-outline" target="_blank">📄 Generar PDF</a>
                        <a href="<?= htmlspecialchars($volverUrl) ?>" class="btn">Cancelar</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

<script>
CKEDITOR.replace('editor', {
    height: 700,
    language: 'es',
    removePlugins: 'elementspath',
    resize_enabled: true
});
</script>
</body>
</html>
