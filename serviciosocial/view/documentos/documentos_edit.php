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
      <a href="documento_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="documento_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <!-- ⚠️ Mensaje de estado opcional -->
    <div class="alert info">
      Estás editando un documento. Asegúrate de revisar bien los cambios antes de guardarlos.
    </div>

    <!-- 📄 Datos del documento -->
    <section class="card">
      <h2>Información del Documento</h2>

      <form action="" method="post" enctype="multipart/form-data" class="form">

        <!-- 🏢 Relación con empresa y convenio -->
        <div class="grid cols-2">
          <div class="field">
            <label for="empresa">Empresa</label>
            <select id="empresa" name="empresa" required>
              <option value="">-- Seleccionar empresa --</option>
              <option value="1" selected>Empresa Tecnológica S.A.</option>
              <option value="2">Hospital Central</option>
              <option value="3">Instituto Cultural</option>
            </select>
          </div>

          <div class="field">
            <label for="convenio">Convenio</label>
            <select id="convenio" name="convenio">
              <option value="">-- Seleccionar convenio --</option>
              <option value="1" selected>Convenio Marco 2025</option>
              <option value="2">Convenio Hospitalario</option>
            </select>
          </div>
        </div>

        <!-- 📄 Tipo de documento -->
        <div class="field">
          <label for="tipo">Tipo de documento</label>
          <select id="tipo" name="tipo" required>
            <option value="">-- Seleccionar tipo --</option>
            <option value="1" selected>Carta de Intención</option>
            <option value="2">Carta de Compromiso</option>
            <option value="3">Solicitud de Servicio Social</option>
          </select>
        </div>

        <!-- 📁 Archivo actual -->
        <div class="field">
          <label>Archivo actual</label>
          <p>
            📄 <a href="../../uploads/documentos/carta_intencion.pdf" target="_blank">carta_intencion.pdf</a>
          </p>
        </div>

        <!-- 📤 Subir nuevo archivo -->
        <div class="field">
          <label for="archivo">Subir nueva versión</label>
          <input type="file" id="archivo" name="archivo" accept=".pdf,.doc,.docx" />
          <div class="hint">Opcional: si no seleccionas nada, se conservará el archivo anterior.</div>
        </div>

        <!-- 🔄 Estatus del documento -->
        <div class="field">
          <label for="estatus" class="required">Estatus del documento</label>
          <select id="estatus" name="estatus" required>
            <option value="pendiente">Pendiente</option>
            <option value="aprobado" selected>Aprobado</option>
            <option value="rechazado">Rechazado</option>
          </select>
        </div>

        <!-- 📝 Observaciones -->
        <div class="field">
          <label for="observacion">Observaciones</label>
          <textarea id="observacion" name="observacion" placeholder="Notas del revisor...">El documento ha sido revisado y aprobado correctamente. Cumple con todos los requisitos.</textarea>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="documento_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>

      </form>
    </section>
  </main>

</body>
</html>
