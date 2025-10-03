<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Empresa · Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/empresas/empresaglobalstyles.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Registrar Nueva Empresa</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="empresa_list.php">Empresas</a>
      <span>›</span>
      <span>Registrar</span>
    </nav>
  </header>

  <!-- MAIN -->
  <main>
    <div class="card">
      <h2>🏢 Información de la Empresa</h2>
      <p>Completa el siguiente formulario para registrar una nueva empresa en el sistema de Residencia Profesional.</p>

      <!-- FORMULARIO -->
      <form class="form" action="" method="post">
        <!-- NOMBRE -->
        <div class="field">
          <label for="nombre" class="required">Nombre de la empresa *</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ej: Industrias Yakumo S.A. de C.V." required />
        </div>

        <!-- RFC -->
        <div class="field">
          <label for="rfc">RFC</label>
          <input type="text" id="rfc" name="rfc" placeholder="Ej: YAK930303CC3" maxlength="20" />
        </div>

        <!-- CONTACTO -->
        <div class="field">
          <label for="contacto_nombre">Nombre del contacto</label>
          <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Ej: Luis Pérez" />
        </div>

        <div class="field">
          <label for="contacto_email">Correo electrónico del contacto</label>
          <input type="email" id="contacto_email" name="contacto_email" placeholder="Ej: contacto@empresa.com" />
        </div>

        <!-- TELEFONO -->
        <div class="field">
          <label for="telefono">Teléfono</label>
          <input type="text" id="telefono" name="telefono" placeholder="Ej: (33) 1234 5678" />
        </div>

        <!-- ESTADO -->
        <div class="field">
          <label for="estado">Estado / Localidad</label>
          <input type="text" id="estado" name="estado" placeholder="Ej: Jalisco, CDMX, etc." />
        </div>

        <!-- ACCIONES -->
        <div class="actions">
          <a href="empresa_list.php" class="btn btn-secondary">⬅ Cancelar</a>
          <button type="submit" class="btn btn-primary">💾 Guardar Empresa</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>
