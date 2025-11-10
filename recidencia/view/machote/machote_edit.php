<?php
declare(strict_types=1);

require_once __DIR__ . '/../../model/Conexion.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die('ID de machote no válido.');
}

// Obtener el machote hijo desde la base de datos
$machote = ConvenioMachoteModel::getById($id);

if (!$machote) {
    die('Machote no encontrado.');
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Machote · Convenio <?= htmlspecialchars($machote['convenio_id']) ?></title>

    <link rel="stylesheet" href="../../assets/stylesrecidencia.css">
    <link rel="stylesheet" href="../../assets/css/machote/machote_edit.css">

    <script src="../../assets/ckeditor/ckeditor.js"></script>
</head>

<body>
    <div class="app">
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <main class="main">
            <header class="topbar">
                <h2>📝 Editar Machote del Convenio #<?= htmlspecialchars($machote['convenio_id']) ?></h2>
                <a href="../convenio/convenio_view.php?id=<?= htmlspecialchars($machote['convenio_id']) ?>"
                    class="btn">← Volver al Convenio</a>
            </header>

            <section class="card">
                <header>Documento Editable</header>
                <div class="content">

                    <form method="POST" action="../../handler/machote/machote_update_handler.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

                        <textarea name="contenido" id="editor"
                            rows="30"><?= htmlspecialchars($machote['contenido_html']) ?></textarea>

                        <div class="actions" style="margin-top: 16px;">
                            <button type="submit" class="btn primary">💾 Guardar Machote</button>
                            <a href="../convenio/convenio_view.php?id=<?= htmlspecialchars($machote['convenio_id']) ?>"
                                class="btn">Cancelar</a>
                        </div>
                    </form>

                </div>

                <div class="actions" style="margin-top: 16px;">
                    <button type="submit" class="btn primary">💾 Guardar Machote</button>
                    <a href="../../handler/machote/machote_generate_pdf.php?id=<?= htmlspecialchars($id) ?>"
                        class="btn btn-outline" target="_blank">
                        📄 Generar PDF
                    </a>
                    <a href="../convenio/convenio_view.php?id=<?= htmlspecialchars($machote['convenio_id']) ?>"
                        class="btn">
                        Cancelar
                    </a>
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