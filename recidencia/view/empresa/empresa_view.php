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

      <!-- 🟢 Caso 1: Machote aprobado -->

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
      <!-- 🟡 Caso 2: En revisión -->


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
      <!-- 🔴 Caso 3: Sin revisión activa -->

      <section class="card">
        <header>📝 Revisión de Machote</header>
        <div class="content">
          <p>No existe una revisión de machote activa para esta empresa.</p>
          <div class="actions">
            <a href="../machote/add.php?empresa=45" class="btn primary">➕ Iniciar revisión</a>
          </div>
        </div>
      </section>



<!-- 📂 Documentación Legal -->
<section class="card">
  <header>
    📂 Documentación Legal
    <span class="subtitle">Control de documentos requeridos por Vinculación</span>
  </header>

  <div class="content">
    <?php
    // --- Datos simulados de ejemplo (puedes reemplazar luego con consulta real) ---
    $docsTotal = 5;      // Total de documentos requeridos
    $docsSubidos = 3;    // Archivos cargados por la empresa
    $docsAprobados = 2;  // Documentos validados por Vinculación
    $progreso = round(($docsSubidos / $docsTotal) * 100);
    ?>

    <!-- 🔢 Resumen visual -->
    <div class="docs-summary" style="margin-bottom:15px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
      <div style="flex:1;">
        <strong>📄 Documentos requeridos:</strong> <?php echo $docsTotal; ?><br>
        <strong>📤 Subidos:</strong> <?php echo $docsSubidos; ?>  
        <strong>✅ Aprobados:</strong> <?php echo $docsAprobados; ?>
      </div>
      <div style="flex:1;">
        <label style="font-weight:600;">Progreso general:</label>
        <div style="background:#eee; border-radius:8px; overflow:hidden; height:10px; margin-top:4px;">
          <div style="width:<?php echo $progreso; ?>%; height:10px; background:#4caf50;"></div>
        </div>
        <small><?php echo $progreso; ?>% completado</small>
      </div>
    </div>

    <!-- 🧾 Tabla de resumen de documentos -->
    <table>
      <thead>
        <tr>
          <th>Documento</th>
          <th>Estado</th>
          <th>Última actualización</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <!-- ✅ Ejemplo: documento aprobado -->
        <tr>
          <td>Constancia SAT</td>
          <td><span class="badge ok">Aprobado</span></td>
          <td>2025-09-10</td>
          <td><a href="../../uploads/empresa_45/sat_constancia.pdf" class="btn small">📄 Ver</a></td>
        </tr>

        <!-- ⏳ Ejemplo: documento pendiente -->
        <tr>
          <td>Acta Constitutiva</td>
          <td><span class="badge pendiente">Pendiente</span></td>
          <td>—</td>
          <td>
            <a href="empresa_docs.php?id_empresa=45" class="btn small primary">📁 Ver / Subir</a>
          </td>
        </tr>

        <!-- ⬆ Ejemplo: logotipo pendiente -->
        <tr>
          <td>Logotipo del Negocio</td>
          <td><span class="badge warn">Faltante</span></td>
          <td>—</td>
          <td>
            <a href="empresa_docs.php?id_empresa=45" class="btn small primary">📁 Ver / Subir</a>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- 🔗 Acción principal -->
    <div class="actions" style="margin-top:16px; justify-content:flex-end;">
      <a href="empresa_docs.php?id_empresa=45" class="btn primary">📁 Gestionar Documentos</a>
    </div>
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
      <section class="card">
        <div class="content actions" style="justify-content:flex-end;">
          <a href="empresa_edit.php?id=45" class="btn primary">✏️ Editar Empresa</a>
          <a href="empresa_delete.php?id=45" class="btn danger">🗑️ Eliminar Empresa</a>
        </div>
      </section>

    </main>
  </div>
</body>

</html>