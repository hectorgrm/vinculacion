<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Eliminar Comentario - Machote</title>
  <link rel="stylesheet" href="../../assets/css/machote/machoteglobalstyles.css">
</head>
<body>

  <header>
    <h1>Eliminar Comentario de Machote</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <a href="machote_list.php">Comentarios Machote</a> <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <div class="card danger-zone">
      <h2>⚠️ Confirmar Eliminación</h2>
      <p class="hint">Estás a punto de eliminar el siguiente comentario de machote. Esta acción <strong>no se puede deshacer</strong>. Asegúrate de que realmente deseas eliminarlo.</p>

      <!-- Información del comentario -->
      <div class="info-box">
        <p><strong>ID:</strong> 4</p>
        <p><strong>Convenio:</strong> Convenio #1 - v1.2</p>
        <p><strong>Cláusula:</strong> Cláusula 3</p>
        <p><strong>Comentario:</strong> Se solicita aclarar las obligaciones de la empresa.</p>
        <p><strong>Estatus:</strong> Pendiente</p>
      </div>

      <form action="machote_delete_action.php" method="POST" class="form">
        <input type="hidden" name="id" value="4"><!-- 🔁 dinámico desde backend -->

        <div class="form-actions">
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Comentario</button>
          <a href="machote_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
