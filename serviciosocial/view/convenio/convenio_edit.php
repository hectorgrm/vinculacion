<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Editar Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <section class="card">
      <h2>Actualizar información del convenio</h2>

      <!-- ALERTA DE INFORMACIÓN -->
      <div class="alert alert-success">
        Estás editando un convenio existente. Asegúrate de revisar todos los datos antes de guardar los cambios.
      </div>

      <form action="" method="post" class="form">

        <!-- Sección 1: Información general -->
        <div class="form-section">
          <h3>📁 Información General</h3>

          <div class="field">
            <label for="ss_empresa_id">Empresa relacionada <span class="required">*</span></label>
            <select id="ss_empresa_id" name="ss_empresa_id" required>
              <option value="">-- Selecciona la empresa --</option>
              <option value="1" selected>Universidad Tecnológica del Centro</option>
              <option value="2">Hospital General San José</option>
              <option value="3">Biblioteca Municipal Central</option>
            </select>
            <div class="hint">Si cambias la empresa, asegúrate que sea la correcta para este convenio.</div>
          </div>

          <div class="field">
            <label for="estatus">Estatus <span class="required">*</span></label>
            <select id="estatus" name="estatus" required>
              <option value="pendiente">Pendiente</option>
              <option value="vigente" selected>Vigente</option>
              <option value="vencido">Vencido</option>
            </select>
            <div class="hint">Cambia el estado si el convenio ha caducado o sigue activo.</div>
          </div>
        </div>

        <!-- Sección 2: Vigencia -->
        <div class="form-section">
          <h3>📅 Vigencia del Convenio</h3>
          <div class="form-grid">
            <div class="field">
              <label for="fecha_inicio">Fecha de inicio</label>
              <input type="date" id="fecha_inicio" name="fecha_inicio" value="2025-01-01" />
              <div class="hint">Fecha desde la cual el convenio entra en vigor.</div>
            </div>

            <div class="field">
              <label for="fecha_fin">Fecha de fin</label>
              <input type="date" id="fecha_fin" name="fecha_fin" value="2025-12-31" />
              <div class="hint">Fecha en la que finaliza la vigencia actual del convenio.</div>
            </div>
          </div>

          <div class="field">
            <label for="version_actual">Versión del convenio</label>
            <input type="text" id="version_actual" name="version_actual" value="v1.0" />
            <div class="hint">Úsalo para controlar cambios o actualizaciones en el documento.</div>
          </div>
        </div>

        <!-- Sección 3: Estado actual -->
        <div class="form-section">
          <h3>📊 Estado del Convenio</h3>
          <p>Estado actual: <span class="badge vigente">vigente</span></p>
          <div class="hint">Este estado se actualizará automáticamente al cambiar el estatus y guardar los cambios.</div>
        </div>

        <!-- Acciones -->
        <div class="actions">
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
          <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
