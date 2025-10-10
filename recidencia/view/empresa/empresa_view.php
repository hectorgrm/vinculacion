<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle de Empresa · Residencias Profesionales</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css">
  <!-- Si ya tienes estilos específicos para esta vista, mantenlos: -->
  <link rel="stylesheet" href="../../assets/css/empresas/empresaview.css">
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>🏢 Detalle de Empresa · Residencias Profesionales</h2>
          <p>Consulta y gestiona la información general, convenios y documentación de la empresa.</p>
        </div>
        <div class="actions">
          <!-- Progreso (misma carpeta) -->
          <a href="empresa_progreso.php?id_empresa=45" class="btn primary">📊 Ver Progreso</a>
          <!-- Volver al listado -->
          <a href="empresa_list.php" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- 🏢 Información General -->
      <section class="card">
        <header>🏢 Información General de la Empresa</header>
        <div class="content empresa-info">
          <div class="info-grid">
            <div><strong>Nombre:</strong> Casa del Barrio</div>
            <div><strong>RFC:</strong> CDB810101AA1</div>
            <div><strong>Representante Legal:</strong> José Manuel Velador</div>
            <div><strong>Teléfono:</strong> (33) 1234 5678</div>
            <div><strong>Correo:</strong> contacto@casadelbarrio.mx</div>
            <div><strong>Estatus:</strong> <span class="badge vigente">Vigente</span></div>
            <div><strong>Fecha de Registro:</strong> 09/09/2025</div>
            <div><strong>Última actualización:</strong> 02/10/2025</div>
          </div>
        </div>
      </section>

      <!-- 📜 Convenios asociados -->
      <section class="card">
        <header>📜 Convenios Activos</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Versión</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#12</td>
                <td>v1.2</td>
                <td>2025-06-01</td>
                <td>2026-05-30</td>
                <td><span class="badge vigente">Vigente</span></td>
                <td>
                  <a href="../convenio/convenio_view.php?id=12" class="btn small">👁️ Ver</a>
                  <a href="../convenio/convenio_edit.php?id=12" class="btn small">✏️ Editar</a>
                </td>
              </tr>
              <tr>
                <td>#15</td>
                <td>v2.0</td>
                <td>2024-04-01</td>
                <td>2025-03-30</td>
                <td><span class="badge por_vencer">Por vencer</span></td>
                <td>
                  <a href="../convenio/convenio_view.php?id=15" class="btn small">👁️ Ver</a>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="actions">
            <a href="../convenio/convenio_add.php?empresa=45" class="btn primary">➕ Nuevo Convenio</a>
          </div>
        </div>
      </section>

      <!-- 💬 Observaciones de Machote -->
<!-- 💬 Revisión de Machote -->
<section class="card">
  <header>📝 Revisión de Machote</header>
  <div class="content">
    <div class="review-summary">
      <strong>Versión aprobada:</strong> Institucional v1.2<br>
      <strong>Estado:</strong> <span class="badge ok">Aprobado</span><br>
      <ul class="file-list" style="margin-top:8px;">
        <li><a href="../../uploads/machote_v12_final.pdf" target="_blank">📄 Machote final (PDF)</a></li>
        <li><a href="../convenio/convenio_view.php?id=12">📑 Ver convenio generado</a></li>
      </ul>
    </div>
    <div class="actions">
      <a href="../machote/machote_revisado.php?id_empresa=45" class="btn secondary">👁️ Vista final</a>
    </div>
  </div>
</section>

<section class="card">
  <header>📝 Revisión de Machote</header>
  <div class="content">
    <div class="review-summary">
      <strong>Versión activa:</strong> Institucional v1.2<br>
      <strong>Estado:</strong> <span class="badge en_revision">En revisión</span><br>
      <strong>Hilos abiertos:</strong> 1 · <strong>Resueltos:</strong> 3 · <strong>Progreso:</strong> 75%
    </div>
    <div class="actions">
      <a href="../machote/revisar.php?id_empresa=45" class="btn primary">💬 Abrir Revisión</a>
    </div>
  </div>
</section>
<section class="card">
  <header>📝 Revisión de Machote</header>
  <div class="content">
    <p>No existe una revisión de machote activa para esta empresa.</p>
    <div class="actions">
      <a href="../machote/add.php?empresa=45" class="btn primary">➕ Iniciar revisión</a>
    </div>
  </div>
</section>



      <!-- 📂 Documentos -->
      <section class="card">
        <header>📂 Documentación Legal</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Fecha de carga</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>INE Representante</td>
                <td><span class="badge ok">Aprobado</span></td>
                <td>2025-09-10</td>
                <td><a href="../../uploads/ine_josevelador.pdf" class="btn small">📄 Ver</a></td>
              </tr>
              <tr>
                <td>Acta Constitutiva</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>—</td>
                <td><a href="../documentos/documento_upload.php?empresa=45" class="btn small primary">⬆️ Subir</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🎓 Estudiantes vinculados -->
      <section class="card">
        <header>🎓 Estudiantes que realizaron Residencia</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Periodo</th>
                <th>Proyecto</th>
                <th>Resultado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Ana Rodríguez</td>
                <td>Informática</td>
                <td>Feb–Jul 2025</td>
                <td>Desarrollo de sistema documental</td>
                <td><span class="badge ok">Concluido</span></td>
              </tr>
              <tr>
                <td>Juan Pérez</td>
                <td>Industrial</td>
                <td>Ago–Dic 2024</td>
                <td>Optimización de procesos</td>
                <td><span class="badge ok">Concluido</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🔧 Acciones -->
      <div class="actions">
        <a href="empresa_edit.php?id=45" class="btn primary">✏️ Editar Empresa</a>
        <a href="empresa_delete.php?id=45" class="btn danger">🗑️ Eliminar Empresa</a>
      </div>
    </main>
  </div>
</body>
</html>
