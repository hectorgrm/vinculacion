<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Editar Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card">
      <h2>Formulario de Edición</h2>
      <p>Modifica la información necesaria del documento. Puedes actualizar su estado, agregar observaciones o subir una nueva versión.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🧑‍🎓 Estudiante -->
          <div class="field">
            <label for="estudiante">Estudiante</label>
            <input type="text" id="estudiante" name="estudiante" value="Juan Carlos Pérez López (20214567)" readonly />
          </div>

          <!-- 📑 Tipo de Documento -->
          <div class="field">
            <label for="tipo">Tipo de Documento</label>
            <input type="text" id="tipo" name="tipo" value="Reporte Bimestral" readonly />
          </div>

          <!-- 📅 Fecha de subida -->
          <div class="field">
            <label for="fecha_subida">Fecha de subida</label>
            <input type="text" id="fecha_subida" name="fecha_subida" value="2025-09-25" readonly />
          </div>

          <!-- 📌 Estado del Documento -->
          <div class="field">
            <label for="estatus" class="required">Estado</label>
            <select id="estatus" name="estatus" required>
              <option value="pendiente" selected>Pendiente</option>
              <option value="aprobado">Aprobado</option>
              <option value="rechazado">Rechazado</option>
            </select>
          </div>

          <!-- 📎 Archivo actual -->
          <div class="field" style="grid-column: 1 / -1;">
            <label>Archivo actual</label>
            <a href="../../uploads/documentos/reporte_bimestral_juanperez.pdf" target="_blank" class="btn btn-primary">📄 Ver PDF</a>
          </div>

          <!-- 📤 Subir nuevo archivo (opcional) -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo">Actualizar archivo PDF (opcional)</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" />
            <div class="hint">Si subes un nuevo archivo, reemplazará al actual.</div>
          </div>

          <!-- 📝 Observaciones -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Escribe tus comentarios o notas sobre el documento...">Faltan conclusiones y anexos. Enviar versión corregida.</textarea>
          </div>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_view.php?id=12" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
