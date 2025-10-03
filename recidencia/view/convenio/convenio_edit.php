<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Convenio - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/convenios/convenioglobalstyles.css" />
</head>
<body>

<header>
  <h1>Editar Convenio</h1>
  <nav class="breadcrumb">
    <a href="../dashboard.php">Inicio</a> <span>/</span>
    <a href="convenio_list.php">Convenios</a> <span>/</span>
    <span>Editar</span>
  </nav>
</header>

<main>

  <section class="card">
    <h2>Formulario de Edición de Convenio</h2>

    <form class="form" method="POST" action="convenio_edit_action.php">
      <!-- ID oculto del convenio -->
      <input type="hidden" name="id" value="1" />
      <!-- Este valor se llenará dinámicamente con el ID real -->

      <!-- Empresa -->
      <div class="field">
        <label for="empresa_id">Empresa</label>
        <select name="empresa_id" id="empresa_id" required>
          <option value="">-- Selecciona una empresa --</option>
          <option value="1" selected>Casa del Barrio</option>
          <option value="2">Tequila ECT</option>
          <option value="3">Industrias Yakumo</option>
          <!-- 🔁 Este listado se llenará dinámicamente desde rp_empresa -->
        </select>
      </div>

      <!-- Estatus -->
      <div class="field">
        <label for="estatus">Estatus del convenio</label>
        <select name="estatus" id="estatus" required>
          <option value="">-- Selecciona el estatus --</option>
          <option value="pendiente">Pendiente</option>
          <option value="en_revision" selected>En revisión</option>
          <option value="vigente">Vigente</option>
          <option value="vencido">Vencido</option>
        </select>
      </div>

      <!-- Fecha Inicio -->
      <div class="field">
        <label for="fecha_inicio">Fecha de inicio</label>
        <input type="date" name="fecha_inicio" id="fecha_inicio" value="2025-08-15" />
      </div>

      <!-- Fecha Fin -->
      <div class="field">
        <label for="fecha_fin">Fecha de término</label>
        <input type="date" name="fecha_fin" id="fecha_fin" value="2026-08-14" />
      </div>

      <!-- Versión -->
      <div class="field">
        <label for="version_actual">Versión del convenio</label>
        <input type="text" name="version_actual" id="version_actual" value="v1.2" placeholder="Ej. v1.0, v1.2, etc." />
      </div>

      <div class="actions">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="convenio_list.php" class="btn btn-secondary">Cancelar</a>
      </div>
    </form>
  </section>

</main>

</body>
</html>
