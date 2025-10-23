<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Convenio · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenioadd.css" />
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Registrar Nuevo Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="convenio_list.php">Convenios</a>
            <span>›</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a href="convenio_list.php" class="btn">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📄 Formulario de Alta de Convenio</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Registra un convenio vinculado a una empresa. Podrás adjuntar el archivo y definir su vigencia.
          </p>

          <form class="form" method="POST" action="convenio_add_action.php" enctype="multipart/form-data">
            <div class="grid">

              <!-- Empresa -->
              <div class="field col-span-2">
                <label for="empresa_id" class="required">Empresa *</label>
                <select name="empresa_id" id="empresa_id" required>
                  <option value="">-- Selecciona una empresa --</option>
                  <!-- Aquí se llenará dinámicamente con PHP -->
                </select>
              </div>

              <!-- Folio -->
              <div class="field">
                <label for="folio">Folio del convenio</label>
                <input type="text" name="folio" id="folio" placeholder="Ej: CBR-2025-01" />
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus del convenio *</label>
                <select name="estatus" id="estatus" required>
                  <option value="">-- Selecciona el estatus --</option>
                  <option value="Activa">Activa</option>
                  <option value="En revisión">En revisión</option>
                  <option value="Inactiva">Inactiva</option>
                  <option value="Suspendida">Suspendida</option>
                </select>
              </div>

              <!-- Machote versión -->
              <div class="field">
                <label for="machote_version">Versión de machote</label>
                <input type="text" name="machote_version" id="machote_version" placeholder="Ej: v1.0" />
              </div>

              <!-- Versión actual -->
              <div class="field">
                <label for="version_actual">Versión actual del convenio</label>
                <input type="text" name="version_actual" id="version_actual" placeholder="Ej: v1.2" />
              </div>

              <!-- Fechas -->
              <div class="field">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" />
              </div>

              <div class="field">
                <label for="fecha_fin">Fecha de término</label>
                <input type="date" name="fecha_fin" id="fecha_fin" />
              </div>

              <!-- Archivo -->
              <div class="field col-span-2">
                <label for="borrador_path">Archivo del convenio (PDF)</label>
                <input type="file" name="borrador_path" id="borrador_path" accept="application/pdf" />
              </div>

              <!-- Observaciones -->
              <div class="field col-span-2">
                <label for="observaciones">Notas / Observaciones</label>
                <textarea name="observaciones" id="observaciones" rows="4" placeholder="Comentarios internos del área de vinculación..."></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="convenio_list.php" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Convenio</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
