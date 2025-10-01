<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Nuevo Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Nuevo</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="dg-card">
      <h2>Registrar Documento Global</h2>
      <p>Completa los campos para subir un documento disponible para todos los estudiantes.</p>

      <!-- 
        NOTA:
        - method="post" y enctype="multipart/form-data" son necesarios para subir archivos.
        - En tu implementación real, llena el <select> de tipo con los valores de ss_doc_tipo.
      -->
      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🧩 Tipo de documento (FK: ss_doc_tipo.id) -->
          <div class="field">
            <label for="tipo_id" class="required">Tipo de documento</label>
            <select id="tipo_id" name="tipo_id" required>
              <option value="">-- Selecciona un tipo --</option>
              <option value="1">Carta de Intención</option>
              <option value="2">Carta de Compromiso</option>
              <option value="3">Carta de Aceptación</option>
              <option value="4">Reporte Bimestral</option>
              <option value="5">Evaluación Cualitativa</option>
            </select>
            <div class="hint">Este catálogo viene de <strong>ss_doc_tipo</strong>.</div>
          </div>

          <!-- 🏷️ Nombre visible -->
          <div class="field">
            <label for="nombre" class="required">Nombre del documento</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ej. Guía Oficial de Reporte Bimestral" required />
            <div class="hint">Título claro que verá el estudiante en su panel.</div>
          </div>

          <!-- 📝 Descripción -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Breve descripción o instrucciones para el uso del documento (opcional)…"></textarea>
          </div>

          <!-- 📎 Archivo PDF -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo" class="required">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <div class="hint">Formato permitido: PDF — Tamaño sugerido ≤ 5 MB.</div>
          </div>

          <!-- 🔘 Estatus -->
          <div class="field">
            <label for="estatus" class="required">Estatus</label>
            <select id="estatus" name="estatus" required>
              <option value="activo" selected>Activo</option>
              <option value="inactivo">Inactivo</option>
            </select>
            <div class="hint">Si está <em>inactivo</em>, no se mostrará a los estudiantes.</div>
          </div>

        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <span class="note">Revisa que el archivo sea el correcto antes de subirlo.</span>
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">📤 Subir documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
