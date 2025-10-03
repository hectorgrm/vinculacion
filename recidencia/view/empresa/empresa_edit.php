<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Empresa · Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Editar Empresa</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="empresa_list.php">Empresas</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <!-- MAIN -->
  <main>
    <div class="card">
      <h2>✏️ Modificar datos de la empresa</h2>
      <p>Actualiza la información necesaria y guarda los cambios.</p>

      <!-- ALERTAS -->
      <div class="alert alert-info">
        Estás editando la información de <strong>Casa del Barrio</strong> (ID #1)
      </div>

      <!-- FORMULARIO -->
      <form class="form" action="" method="post">
        <!-- NOMBRE -->
        <div class="field">
          <label for="nombre" class="required">Nombre de la empresa *</label>
          <input type="text" id="nombre" name="nombre" value="Casa del Barrio" required />
        </div>

        <!-- RFC -->
        <div class="field">
          <label for="rfc">RFC</label>
          <input type="text" id="rfc" name="rfc" value="CDB810101AA1" maxlength="20" />
        </div>

        <!-- CONTACTO -->
        <div class="field">
          <label for="contacto_nombre">Nombre del contacto</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" value="José Manuel Velador" />
        </div>

        <div class="field">
          <label for="contacto_email">Correo electrónico del contacto</label>
          <input type="email" id="contacto_email" name="contacto_email" value="contacto@casadelbarrio.mx" />
        </div>

        <!-- TELEFONO -->
        <div class="field">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" value="(33) 1234 5678" />
        </div>

        <!-- ESTADO -->
        <div class="field">
          <label for="estado">Estado / Localidad</label>
          <input type="text" id="estado" name="estado" value="Jalisco" />
        </div>

        <!-- ACCIONES -->
        <div class="actions">
          <a href="empresa_list.php" class="btn btn-secondary">⬅ Cancelar</a>
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
