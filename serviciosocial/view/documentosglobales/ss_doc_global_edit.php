<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Editar Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="dg-card">
      <h2>✏️ Editar Información</h2>
      <p>Modifica los datos del documento global y guarda los cambios.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 📑 Nombre -->
          <div class="field">
            <label for="nombre" class="required">Nombre del Documento</label>
            <input type="text" id="nombre" name="nombre" value="Guía de Reporte Bimestral" required />
          </div>

          <!-- 📂 Tipo de documento -->
          <div class="field">
            <label for="tipo" class="required">Tipo de Documento</label>
            <select id="tipo" name="tipo" required>
              <option value="">-- Seleccionar tipo --</option>
              <option value="1" selected>Formato Oficial</option>
              <option value="2">Plantilla de Reporte</option>
              <option value="3">Instructivo</option>
            </select>
          </div>

          <!-- 📝 Descripción -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Describe brevemente el propósito del documento...">Este documento contiene el formato oficial que los estudiantes deben utilizar para el reporte bimestral de actividades.</textarea>
          </div>

          <!-- 📎 Archivo PDF -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo">Actualizar Archivo PDF (opcional)</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" />
            <div class="hint">Si no deseas cambiar el archivo, deja este campo vacío.</div>
            <p>📁 Archivo actual: <a href="../../uploads/documentos/guia_reporte_bimestral.pdf" class="file-link" target="_blank">Ver documento</a></p>
          </div>

          <!-- ⚙️ Estado -->
          <div class="field">
            <label for="estatus" class="required">Estado</label>
            <select id="estatus" name="estatus" required>
              <option value="activo" selected>Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
          </div>

          <!-- 📅 Fecha última actualización -->
          <div class="field">
            <label>Última actualización</label>
            <input type="text" value="2025-09-30 15:41:27" disabled />
          </div>

        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
