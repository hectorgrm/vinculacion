<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Eliminar Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="dg-card form-card--danger">
      <h2>⚠️ Confirmación de eliminación</h2>
      <p>
        Estás a punto de <strong>eliminar permanentemente</strong> este documento global.  
        Esta acción <strong>no se puede deshacer</strong>. Por favor, confirma que realmente deseas eliminarlo.
      </p>

      <!-- 🧾 Información resumida del documento -->
      <div class="details-list">
        <dt>ID del documento:</dt>
        <dd>4</dd>

        <dt>Nombre:</dt>
        <dd>Guía de Reporte Bimestral</dd>

        <dt>Tipo:</dt>
        <dd><span class="doc-type">Plantilla de Reporte</span></dd>

        <dt>Descripción:</dt>
        <dd>Formato oficial que los estudiantes deben utilizar para elaborar su reporte bimestral.</dd>

        <dt>Estado actual:</dt>
        <dd><span class="status aprobado">Activo</span></dd>

        <dt>Archivo actual:</dt>
        <dd><a href="../../uploads/documentos/guia_reporte_bimestral.pdf" class="file-link" target="_blank">📄 Ver PDF</a></dd>

        <dt>Última actualización:</dt>
        <dd>2025-09-30 15:41:27</dd>
      </div>

      <!-- 🗑️ Formulario de confirmación -->
      <form action="" method="post" class="form" style="margin-top: 30px;">
        <div class="field">
          <label for="confirmacion" class="required">Escribe <strong>ELIMINAR</strong> para confirmar</label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR aquí..." required />
          <div class="hint">Esta medida es para evitar eliminaciones accidentales.</div>
        </div>

        <div class="actions">
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
