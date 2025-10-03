<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Documento - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentoglobalstyles.css">
</head>
<body>

  <header>
    <h1>Editar Documento</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../empresas/empresa_list.php">Empresas</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="documento_list.php">Documentos</a> <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <div class="form-container">
      <h2>Actualizar Documento</h2>
      <p>Modifica la información del documento y guarda los cambios.</p>

      <form action="documento_edit_action.php" method="POST" enctype="multipart/form-data" class="form">

        <!-- ID oculto -->
        <input type="hidden" name="id" value="8"><!-- 🔁 ID dinámico desde la BD -->

        <!-- Empresa -->
        <div class="field">
          <label for="empresa_id">Empresa <span style="color:red">*</span></label>
          <select name="empresa_id" id="empresa_id" required>
            <option value="">-- Selecciona una empresa --</option>
            <option value="1" selected>Casa del Barrio</option>
            <option value="2">Tequila ECT</option>
            <option value="3">Industrias Yakumo</option>
          </select>
        </div>

        <!-- Convenio -->
        <div class="field">
          <label for="convenio_id">Convenio (opcional)</label>
          <select name="convenio_id" id="convenio_id">
            <option value="">-- Sin convenio asociado --</option>
            <option value="1" selected>Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>
        </div>

        <!-- Tipo de Documento -->
        <div class="field">
          <label for="tipo_id">Tipo de Documento <span style="color:red">*</span></label>
          <select name="tipo_id" id="tipo_id" required>
            <option value="">-- Selecciona un tipo --</option>
            <option value="1">INE representante</option>
            <option value="2">CURP representante</option>
            <option value="3" selected>Acta constitutiva</option>
            <option value="4">Comprobante de domicilio</option>
          </select>
        </div>

        <!-- Archivo PDF -->
        <div class="field">
          <label for="ruta">Archivo PDF</label>
          <div class="file-upload">
            <p>Archivo actual: <a href="/uploads/docs/technova_acta.pdf" target="_blank">📄 technova_acta.pdf</a></p>
            <input type="file" name="ruta" id="ruta" accept=".pdf">
            <label for="ruta">Haz clic o arrastra un nuevo archivo si deseas reemplazarlo</label>
            <p>Formato permitido: PDF (máximo 10 MB)</p>
          </div>
        </div>

        <!-- Estatus -->
        <div class="field">
          <label for="estatus">Estatus</label>
          <select name="estatus" id="estatus" required>
            <option value="pendiente">Pendiente</option>
            <option value="aprobado" selected>Aprobado</option>
            <option value="rechazado">Rechazado</option>
          </select>
        </div>

        <!-- Observaciones -->
        <div class="field">
          <label for="observacion">Observaciones</label>
          <textarea name="observacion" id="observacion" rows="4">Acta revisada y válida</textarea>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
          <a href="documento_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
