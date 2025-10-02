<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- ===== HEADER ===== -->
  <header>
    <h1>Editar Empresa</h1>
    <p>Modifica la información de la empresa registrada en el sistema</p>
  </header>

  <div class="container">

    <!-- ===== FORMULARIO ===== -->
    <div class="form-card">
      <form action="#" method="post">

        <div class="form-group">
          <label for="nombre">Nombre de la Empresa <span class="required">*</span></label>
          <input type="text" id="nombre" name="nombre" value="Universidad Tecnológica del Centro" required />
        </div>

        <div class="form-group">
          <label for="contacto_nombre">Nombre del Contacto</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" value="Dra. María López" />
        </div>

        <div class="form-group">
          <label for="contacto_email">Email de Contacto</label>
          <input type="email" id="contacto_email" name="contacto_email" value="maria.lopez@utc.edu" />
        </div>

        <div class="form-group">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" value="555-1234567" />
        </div>

        <div class="form-group">
          <label for="direccion">Dirección</label>
          <input type="text" id="direccion" name="direccion" value="Av. Principal 123, Ciudad A" />
        </div>

        <div class="form-group">
          <label for="estado">Estado</label>
          <select id="estado" name="estado">
            <option value="activo" selected>Activo</option>
            <option value="inactivo">Inactivo</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-success">Guardar Cambios</button>
          <a href="empresa_list.php" class="btn btn-secondary">Cancelar</a>
        </div>
      </form>
    </div>

  </div>

</body>
</html>
