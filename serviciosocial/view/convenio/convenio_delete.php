<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />


</head>
<body>

  <header style="background: var(--danger-color);">
    <h1>Eliminar Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <section class="card danger-zone">
      <h2>⚠️ Confirmación de eliminación</h2>
      <div class="alert">
        Estás a punto de <strong>eliminar permanentemente</strong> este convenio.  
        Esta acción <strong>no se puede deshacer</strong>. Asegúrate de que ya no haya estudiantes asignados a este convenio ni documentos relacionados.
      </div>

      <!-- 🧾 Detalles del convenio (simulados, se llenarán desde la BD con PHP) -->
      <dl class="details">
        <dt>ID del convenio:</dt>
        <dd>3</dd>

        <dt>Empresa:</dt>
        <dd>Hospital General San José</dd>

        <dt>Estatus:</dt>
        <dd><span class="status pendiente">Pendiente</span></dd>

        <dt>Fecha de inicio:</dt>
        <dd>2025-03-01</dd>

        <dt>Fecha de fin:</dt>
        <dd>2025-12-31</dd>

        <dt>Versión actual:</dt>
        <dd>v0.9</dd>
      </dl>

      <!-- ⚠️ Confirmación para eliminar -->
      <form action="" method="post" class="form" style="margin-top: 30px;">
        <div class="field">
          <label for="confirmacion" class="required">Escribe <strong>ELIMINAR</strong> para confirmar</label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR aquí..." required />
          <div class="hint">Esta medida es para evitar eliminaciones accidentales.</div>
        </div>

        <div class="actions">
          <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Convenio</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
