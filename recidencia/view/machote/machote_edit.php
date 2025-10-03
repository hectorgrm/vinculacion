<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Editar Comentario - Machote</title>
  <link rel="stylesheet" href="../../assets/css/machote/machoteglobalstyles.css">
</head>
<body>

  <header>
    <h1>Editar Comentario de Machote</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="machote_list.php">Machote</a> <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Editar Comentario</h2>
      <p>Modifica la información del comentario de la cláusula seleccionada. Asegúrate de revisar bien antes de guardar los cambios.</p>

      <form action="machote_edit_action.php" method="POST" class="form">
        <!-- ID oculto -->
        <input type="hidden" name="id" value="4"><!-- 🔁 valor dinámico desde backend -->

        <!-- Convenio relacionado -->
        <div class="field">
          <label for="convenio_id">Convenio:</label>
          <select name="convenio_id" id="convenio_id" required>
            <option value="">-- Selecciona un convenio --</option>
            <option value="1" selected>Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>
        </div>

        <!-- Usuario (opcional) -->
        <div class="field">
          <label for="usuario_id">Usuario asignado (opcional):</label>
          <select name="usuario_id" id="usuario_id">
            <option value="">-- Sin asignar --</option>
            <option value="1">Admin Residencia</option>
            <option value="2">Editor Jurídico</option>
            <option value="3" selected>Lic. Adriana García</option>
          </select>
        </div>

        <!-- Cláusula -->
        <div class="field">
          <label for="clausula">Cláusula:</label>
          <input type="text" name="clausula" id="clausula" value="Cláusula 3" required>
        </div>

        <!-- Comentario -->
        <div class="field">
          <label for="comentario">Comentario:</label>
          <textarea name="comentario" id="comentario" rows="5" required>Se solicita aclarar las obligaciones de la empresa.</textarea>
        </div>

        <!-- Estatus -->
        <div class="field">
          <label for="estatus">Estatus:</label>
          <select name="estatus" id="estatus" required>
            <option value="pendiente">Pendiente</option>
            <option value="resuelto" selected>Resuelto</option>
          </select>
        </div>

        <!-- Botones de acción -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Guardar Cambios</button>
          <a href="machote_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
