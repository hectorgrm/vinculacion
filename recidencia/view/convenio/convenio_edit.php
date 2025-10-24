<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Editar Convenio · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/convenios/convenio_edit.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <!-- Topbar -->
      <header class="topbar">
        <div>
          <h2>✏️ Editar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="convenio_list.php">Convenios</a>
            <span>›</span>
            <span>Editar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="convenio_view.php?id=12" class="btn">👁️ Ver</a>
          <a href="convenio_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <!-- Formulario principal -->
      <section class="card">
        <header>🧾 Datos del Convenio</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Estás editando el convenio <strong>#12</strong> vinculado a la empresa 
            <strong>Casa del Barrio</strong> (ID <strong>45</strong>).
          </p>

          <form class="form" method="POST" action="convenio_edit_action.php?id=12" enctype="multipart/form-data">
            <input type="hidden" name="id" value="12" />
            <input type="hidden" name="empresa_id" value="45" />

            <div class="grid">
              <!-- Empresa (bloqueada) -->
              <div class="field col-span-2">
                <label for="empresa_locked">Empresa</label>
                <input type="text" id="empresa_locked" value="Casa del Barrio (ID 45)" disabled />
                <div class="help">La empresa no puede cambiarse desde aquí.</div>
              </div>

              <!-- Folio -->
              <div class="field">
                <label for="folio">Folio</label>
                <input type="text" id="folio" name="folio" value="CBR-2025-01" placeholder="Ej: CBR-2025-01" />
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <option value="Activa" selected>Activa</option>
                  <option value="En revisión">En revisión</option>
                  <option value="Inactiva">Inactiva</option>
                  <option value="Suspendida">Suspendida</option>
                </select>
              </div>

              <!-- Machote versión -->
              <div class="field">
                <label for="machote_version">Versión de machote</label>
                <input type="text" id="machote_version" name="machote_version" value="v1.0" placeholder="Ej: v1.0, v1.1..." />
              </div>

              <!-- Versión actual -->
              <div class="field">
                <label for="version_actual">Versión actual del convenio</label>
                <input type="text" id="version_actual" name="version_actual" value="v1.2" placeholder="Ej: v1.0, v1.2, etc." />
              </div>

              <!-- Fechas -->
              <div class="field">
                <label for="fecha_inicio">Fecha de inicio</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="2025-07-01" />
              </div>

              <div class="field">
                <label for="fecha_fin">Fecha de término</label>
                <input type="date" id="fecha_fin" name="fecha_fin" value="2026-06-30" />
              </div>

              <!-- Archivo actual -->
              <div class="field">
                <label>Archivo actual (PDF)</label>
                <div>
                  <a class="btn" href="../../uploads/convenios/convenio_12.pdf" target="_blank">📄 Ver PDF actual</a>
                </div>
                <div class="help">Si no subirás un archivo nuevo, deja este campo vacío.</div>
              </div>

              <!-- Subir nuevo PDF -->
              <div class="field">
                <label for="borrador_path">Reemplazar PDF</label>
                <input type="file" id="borrador_path" name="archivo_pdf" accept="application/pdf" />
              </div>

              <!-- Observaciones -->
              <div class="field col-span-2">
                <label for="observaciones">Notas / Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="4" placeholder="Comentarios internos del área de vinculación...">Convenio marco para prácticas y residencias; incluye anexo técnico para proyectos TI.</textarea>
              </div>
            </div>

            <div class="actions">
              <a href="convenio_view.php?id=12" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
            </div>
          </form>
        </div>
      </section>

      <!-- Histórico -->
      <section class="card">
        <header>Histórico de versiones</header>
        <div class="content">
          <ul>
            <li>v1.0 — Creado 10/09/2024</li>
            <li>v1.1 — Modificado 12/03/2025</li>
            <li>v1.2 — Actual (01/07/2025)</li>
          </ul>
        </div>
      </section>

      <!-- Accesos rápidos -->
      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content actions" style="justify-content:flex-start;">
          <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Ver empresa</a>
          <a class="btn" href="convenio_list.php?empresa=45">📑 Ver convenios de esta empresa</a>
          <a class="btn" href="../machote/revisar.php?id_empresa=45&convenio=12">📝 Revisar machote</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
