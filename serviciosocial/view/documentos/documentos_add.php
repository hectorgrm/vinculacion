<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subir Nuevo Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Subir Nuevo Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="documentos_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Nuevo</span>
    </nav>
  </header>

  <main>
    <a href="documentos_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card">
      <h2>Registrar Documento</h2>
      <p>Completa los siguientes campos para subir un nuevo documento relacionado con un estudiante o convenio.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🏢 Empresa -->
          <div class="field">
            <label for="empresa" class="required">Empresa</label>
            <select id="empresa" name="empresa" required>
              <option value="">-- Seleccionar empresa --</option>
              <option value="1">Tech Solutions S.A. de C.V.</option>
              <option value="2">Hospital General</option>
              <option value="3">Instituto Cultural</option>
            </select>
          </div>

          <!-- 📄 Convenio -->
          <div class="field">
            <label for="convenio">Convenio</label>
            <select id="convenio" name="convenio">
              <option value="">-- Seleccionar convenio --</option>
              <option value="1">Convenio 2025-01</option>
              <option value="2">Convenio 2025-02</option>
            </select>
          </div>

          <!-- 🧑‍🎓 Estudiante -->
          <div class="field">
            <label for="estudiante">Estudiante (opcional)</label>
            <select id="estudiante" name="estudiante">
              <option value="">-- No relacionado --</option>
              <option value="1">Juan Carlos Pérez López (20214567)</option>
              <option value="2">María Fernanda Ortega (20214580)</option>
            </select>
          </div>

          <!-- 📑 Tipo de documento -->
          <div class="field">
            <label for="tipo" class="required">Tipo de Documento</label>
            <select id="tipo" name="tipo" required>
              <option value="">-- Seleccionar tipo --</option>
              <option value="1">Carta de Intención</option>
              <option value="2">Carta de Compromiso</option>
              <option value="3">Carta de Aceptación</option>
              <option value="4">Reporte Bimestral</option>
              <option value="5">Evaluación Cualitativa</option>
            </select>
          </div>

          <!-- 📎 Archivo -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo" class="required">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <div class="hint">Formato permitido: PDF (máx. 5 MB)</div>
          </div>

          <!-- 📝 Observaciones -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="observacion">Observaciones</label>
            <textarea id="observacion" name="observacion" placeholder="Notas o comentarios sobre el documento (opcional)..."></textarea>
          </div>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="documentos_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">📤 Subir Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
