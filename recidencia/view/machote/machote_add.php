<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Agregar Comentario - Machote</title>
  <link rel="stylesheet" href="../../assets/css/machote/machoteglobalstyles.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Agregar Nuevo Comentario al Machote</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="machote_list.php">Comentarios Machote</a> <span>›</span>
      <span>Agregar</span>
    </nav>
  </header>

  <main>
    <div class="card form-container">
      <h2>Registrar Comentario</h2>
      <p>Llena el siguiente formulario para registrar un comentario sobre una cláusula específica del convenio.</p>

      <form action="machote_add_action.php" method="POST" class="form">

        <!-- Convenio -->
        <div class="field">
          <label for="convenio_id">Convenio asociado:</label>
          <select name="convenio_id" id="convenio_id" required>
            <option value="">-- Selecciona un convenio --</option>
            <!-- 🔁 Opciones dinámicas desde BD -->
            <option value="1">Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>
        </div>

        <!-- Cláusula -->
        <div class="field">
          <label for="clausula">Número o nombre de la cláusula:</label>
          <input type="text" name="clausula" id="clausula" placeholder="Ej: Cláusula 3 - Responsabilidades" required>
        </div>

        <!-- Comentario -->
        <div class="field">
          <label for="comentario">Comentario:</label>
          <textarea name="comentario" id="comentario" rows="5" placeholder="Escribe el comentario detallado sobre esta cláusula..." required></textarea>
        </div>

        <!-- Estatus inicial -->
        <div class="field">
          <label for="estatus">Estatus inicial:</label>
          <select name="estatus" id="estatus" required>
            <option value="pendiente" selected>Pendiente</option>
            <option value="resuelto">Resuelto</option>
          </select>
        </div>

        <!-- Usuario (automático) -->
        <div class="field">
          <label for="usuario_id">Usuario que comenta:</label>
          <select name="usuario_id" id="usuario_id" required>
            <!-- Este campo se llenará automáticamente desde la sesión en backend -->
            <option value="1">Admin Residencia</option>
            <option value="2">Editor Jurídico</option>
          </select>
          <small>En el sistema real este valor se asignará automáticamente al usuario que está logueado.</small>
        </div>

        <!-- Botones -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">Guardar Comentario</button>
          <a href="machote_list.php" class="btn btn-secondary">Cancelar</a>
        </div>

      </form>
    </div>
  </main>

</body>
</html>
