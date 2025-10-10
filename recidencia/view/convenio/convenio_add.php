<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Convenio · Residencias Profesionales</title>

  <!-- Estilos globales del módulo -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
    <link rel="stylesheet" href="../../assets/css/convenios/convenioadd.css" />

  <!-- (Opcional) agrega un CSS específico si lo necesitas -->
  <!-- <link rel="stylesheet" href="../../assets/css/residencias/convenio_add.css" /> -->
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
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

          <!-- FORMULARIO -->
          <!-- Si adjuntas archivo, usa multipart/form-data -->
          <form class="form" method="POST" action="convenio_add_action.php" enctype="multipart/form-data">
            <div class="grid">
              <!-- Empresa -->
              <div class="field col-span-2">
                <label for="empresa_id" class="required">Empresa *</label>
                <select name="empresa_id" id="empresa_id" required>
                  <option value="">-- Selecciona una empresa --</option>
                  <option value="1">Casa del Barrio</option>
                  <option value="2">Tequila ECT</option>
                  <option value="3">Industrias Yakumo</option>
                  <!-- 🔁 Este listado se llenará dinámicamente desde rp_empresa -->
                </select>
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus del convenio *</label>
                <select name="estatus" id="estatus" required>
                  <option value="">-- Selecciona el estatus --</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="en_revision">En revisión</option>
                  <option value="vigente">Vigente</option>
                  <option value="vencido">Vencido</option>
                </select>
              </div>

              <!-- Versión -->
              <div class="field">
                <label for="version_actual">Versión</label>
                <input type="text" name="version_actual" id="version_actual" placeholder="Ej: v1.0, v1.2, etc." />
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

              <!-- Archivo (opcional) -->
              <div class="field col-span-2">
                <label for="archivo_pdf">Archivo del convenio (PDF)</label>
                <input type="file" name="archivo_pdf" id="archivo_pdf" accept="application/pdf" />
              </div>

              <!-- Notas -->
              <div class="field col-span-2">
                <label for="notas">Notas / Observaciones</label>
                <textarea name="notas" id="notas" rows="4" placeholder="Comentarios internos del área de vinculación..."></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="convenio_list.php" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Convenio</button>
            </div>
          </form>
        </div>
      </section>

      <!-- (Opcional) Accesos rápidos si vienes desde empresa -->
      <!--
      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content actions" style="justify-content:flex-start;">
          <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Volver a la empresa</a>
          <a class="btn" href="convenio_list.php?empresa=45">📑 Ver convenios de esta empresa</a>
        </div>
      </section>
      -->
    </main>
  </div>
</body>
</html>
