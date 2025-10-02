<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header class="danger-header">
    <h1>Eliminar Empresa</h1>
    <p>Confirma si deseas eliminar esta empresa del sistema</p>
  </header>

  <div class="container">

    <!-- ===== ALERTA ===== -->
    <div class="form-card danger-card">
      <h2>⚠️ Acción Irreversible</h2>
      <p>
        Estás a punto de <strong>eliminar permanentemente</strong> la siguiente empresa.
        Esta acción <strong>no se puede deshacer</strong>. Todos los registros asociados podrían verse afectados.
      </p>

      <!-- ===== DATOS DE LA EMPRESA ===== -->
      <div class="details-list">
        <dt>ID:</dt>
        <dd>1</dd>

        <dt>Nombre:</dt>
        <dd>Universidad Tecnológica del Centro</dd>

        <dt>Contacto:</dt>
        <dd>Dra. María López</dd>

        <dt>Email:</dt>
        <dd>maria.lopez@utc.edu</dd>

        <dt>Teléfono:</dt>
        <dd>555-1234567</dd>

        <dt>Estado:</dt>
        <dd><span class="status activo">Activo</span></dd>
      </div>

      <!-- ===== FORMULARIO DE CONFIRMACIÓN ===== -->
      <form action="#" method="post" class="delete-form">
        <div class="form-group">
          <label for="confirmacion" class="required">
            Escribe <strong>ELIMINAR</strong> para confirmar la eliminación:
          </label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR aquí..." required />
          <p class="hint">Esta medida evita eliminaciones accidentales.</p>
        </div>

        <div class="form-actions">
          <a href="empresa_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Empresa</button>
        </div>
      </form>
    </div>

  </div>

</body>
</html>
