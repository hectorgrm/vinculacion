<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Nuevo Convenio - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Registrar Nuevo Convenio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="convenio_list.php">Convenios</a>
      <span>›</span>
      <span>Nuevo Convenio</span>
    </nav>
  </header>

  <main>
<section class="card">
  <h2>Registrar Nuevo Convenio</h2>

  <form action="" method="post" class="form">

    <!-- Sección 1: Información General -->
    <div class="form-section">
      <h3>📁 Información General</h3>

      <div class="field">
        <label for="ss_empresa_id">Empresa relacionada <span class="required">*</span></label>
        <select id="ss_empresa_id" name="ss_empresa_id" required>
          <option value="">-- Selecciona la empresa --</option>
          <option value="1">Universidad Tecnológica del Centro</option>
          <option value="2">Hospital General San José</option>
        </select>
        <div class="hint">La empresa debe existir previamente para poder asociar el convenio.</div>
      </div>

      <div class="field">
        <label for="estatus">Estatus <span class="required">*</span></label>
        <select id="estatus" name="estatus" required>
          <option value="">-- Selecciona el estatus --</option>
          <option value="pendiente">Pendiente</option>
          <option value="vigente">Vigente</option>
          <option value="vencido">Vencido</option>
        </select>
      </div>
    </div>

    <!-- Sección 2: Vigencia -->
    <div class="form-section">
      <h3>📅 Vigencia del Convenio</h3>
      <div class="form-grid">
        <div class="field">
          <label for="fecha_inicio">Fecha de inicio</label>
          <input type="date" id="fecha_inicio" name="fecha_inicio" />
        </div>
        <div class="field">
          <label for="fecha_fin">Fecha de fin</label>
          <input type="date" id="fecha_fin" name="fecha_fin" />
        </div>
      </div>

      <div class="field">
        <label for="version_actual">Versión del convenio</label>
        <input type="text" id="version_actual" name="version_actual" placeholder="Ej: v1.0" />
        <div class="hint">Puedes usar este campo para llevar control de revisiones del convenio.</div>
      </div>
    </div>

    <!-- Acciones -->
    <div class="actions">
      <button type="submit" class="btn btn-primary">💾 Guardar Convenio</button>
      <a href="convenio_list.php" class="btn btn-secondary">↩️ Cancelar</a>
    </div>
  </form>
</section>

  </main>

</body>
</html>
