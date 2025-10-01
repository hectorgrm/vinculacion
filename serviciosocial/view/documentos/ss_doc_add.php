<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subir Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Subir Documento de Servicio Social</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Nuevo</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card">
      <h2>Registrar Nuevo Documento</h2>
      <p>Completa los siguientes campos para subir un documento relacionado con un estudiante y su servicio social.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🧑‍🎓 Estudiante -->
          <div class="field">
            <label for="estudiante" class="required">Estudiante</label>
            <select id="estudiante" name="estudiante" required>
              <option value="">-- Seleccionar estudiante --</option>
              <option value="1">Juan Carlos Pérez López (20214567)</option>
              <option value="2">María Fernanda Ortega (20214580)</option>
              <option value="3">Luis Alberto Mendoza (20214591)</option>
            </select>
          </div>

          <!-- 📆 Periodo -->
          <div class="field">
            <label for="periodo" class="required">Periodo</label>
            <select id="periodo" name="periodo" required>
              <option value="">-- Seleccionar periodo --</option>
              <option value="1">Periodo 1 - Febrero a Abril</option>
              <option value="2">Periodo 2 - Mayo a Julio</option>
              <option value="3">Periodo 3 - Agosto a Octubre</option>
            </select>
          </div>

          <!-- 📑 Tipo de Documento -->
          <div class="field">
            <label for="tipo" class="required">Tipo de Documento</label>
            <select id="tipo" name="tipo" required>
              <option value="">-- Seleccionar tipo --</option>
              <option value="1">Reporte Bimestral</option>
              <option value="2">Carta de Terminación</option>
              <option value="3">Evaluación Cualitativa</option>
              <option value="4">Autoevaluación</option>
              <option value="5">Plan de Trabajo</option>
            </select>
          </div>

          <!-- 📎 Archivo PDF -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo" class="required">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <div class="hint">Solo se permiten archivos PDF (máx. 5 MB).</div>
          </div>

          <!-- 📝 Observaciones -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Notas o comentarios sobre este documento (opcional)..."></textarea>
          </div>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">📤 Subir Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
