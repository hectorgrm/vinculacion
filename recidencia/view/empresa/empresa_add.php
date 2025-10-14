<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Empresa · Residencias Profesionales</title>

  <!-- Estilos globales del módulo de Residencias -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresalist.css">

</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Registrar Nueva Empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="empresa_list.php">Empresas</a>
            <span>›</span>
            <span>Registrar</span>
          </nav>
        </div>
        <a href="empresa_list.php" class="btn">⬅ Volver</a>
      </header>

      <section class="card">
        <header>🏢 Información de la Empresa</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Completa el formulario para registrar una nueva empresa en el sistema de Residencias Profesionales.
          </p>

          <!-- FORMULARIO -->
  <!-- FORMULARIO -->
<form class="form" action="" method="post">
  <div class="grid">

    <!-- 🏢 Datos generales -->
    <h3 class="section-title">🏢 Información general</h3>
    <div class="field col-span-2">
      <label for="nombre" class="required">Nombre de la empresa *</label>
      <input type="text" id="nombre" name="nombre" placeholder="Ej: Industrias Yakumo S.A. de C.V." required />
    </div>

    <div class="field">
      <label for="rfc">RFC</label>
      <input type="text" id="rfc" name="rfc" placeholder="Ej: YAK930303CC3" maxlength="20" />
    </div>

    <!-- 📞 Datos de contacto -->
    <h3 class="section-title">📞 Datos de contacto</h3>
    <div class="field">
      <label for="telefono">Teléfono</label>
      <input type="text" id="telefono" name="telefono" placeholder="Ej: (33) 1234 5678" />
    </div>

    <div class="field">
      <label for="contacto_nombre">Nombre del contacto</label>
      <input type="text" id="contacto_nombre" name="contacto_nombre" placeholder="Ej: Luis Pérez" />
    </div>

    <div class="field">
      <label for="contacto_email">Correo electrónico del contacto</label>
      <input type="email" id="contacto_email" name="contacto_email" placeholder="Ej: contacto@empresa.com" />
    </div>

    <!-- 📍 Ubicación -->
    <h3 class="section-title">📍 Ubicación</h3>
    <div class="field">
      <label for="estado">Estado / Entidad</label>
      <input type="text" id="estado" name="estado" placeholder="Ej: Jalisco" />
    </div>

    <div class="field">
      <label for="municipio">Municipio / Alcaldía</label>
      <input type="text" id="municipio" name="municipio" placeholder="Ej: Guadalajara" />
    </div>

    <div class="field">
      <label for="cp">Código Postal</label>
      <input type="text" id="cp" name="cp" placeholder="Ej: 44100" />
    </div>

    <div class="field col-span-2">
      <label for="direccion">Dirección (calle y número)</label>
      <input type="text" id="direccion" name="direccion" placeholder="Ej: Calle Independencia 321" />
    </div>

    <!-- ⚙️ Estatus -->
    <h3 class="section-title">⚙️ Estatus</h3>
    <div class="field">
      <label for="estatus">Estatus</label>
      <select id="estatus" name="estatus">
        <option value="activa" selected>Activa</option>
        <option value="inactiva">Inactiva</option>
      </select>
    </div>

  </div>

  <div class="actions">
    <a href="empresa_list.php" class="btn">⬅ Cancelar</a>
    <button type="submit" class="btn primary">💾 Guardar Empresa</button>
  </div>
</form>

        </div>
      </section>
    </main>
  </div>
</body>

</html>