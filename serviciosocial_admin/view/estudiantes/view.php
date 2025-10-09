<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión Integral del Estudiante · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/estudiante/estudianteview.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>👤 Gestión Integral del Estudiante</h2>
        <a href="progreso.php?id=2030456" class="btn primary">📊 Ver Progreso</a>

        <a href="list.php" class="btn secondary">⬅ Volver al listado</a>
      </header>

      <!-- 🧑‍🎓 Información básica -->
      <section class="card">
        <header>📄 Información del Estudiante</header>
        <div class="content student-info">
          <div class="info-row"><strong>Nombre:</strong> <span>Ana Rodríguez</span></div>
          <div class="info-row"><strong>Matrícula:</strong> <span>A012345</span></div>
          <div class="info-row"><strong>Carrera:</strong> <span>Ingeniería en Informática</span></div>
          <div class="info-row"><strong>Correo:</strong> <span>ana.rodriguez@alumno.edu.mx</span></div>
          <div class="info-row"><strong>Teléfono:</strong> <span>3310000001</span></div>
          <div class="info-row"><strong>Semestre:</strong> <span>8</span></div>
        </div>
      </section>

      <!-- 🏢 Empresa -->
      <section class="card">
        <header>🏢 Empresa Asignada</header>
        <div class="content">
          <div class="info-status">
            <p><strong>Estado:</strong> <span class="status pendiente">❌ No asignada</span></p>
          </div>
          <div class="actions">
            <a href="../asignaciones/add.php?estudiante=1" class="btn primary">➕ Asignar Empresa</a>
          </div>
        </div>
      </section>

      <!-- 📜 Convenio -->
      <section class="card">
        <header>📜 Convenio Asociado</header>
        <div class="content">
          <div class="info-status">
            <p><strong>Estado:</strong> <span class="status pendiente">❌ No asignado</span></p>
          </div>
          <div class="actions">
            <a href="../convenios/add.php?estudiante=1" class="btn primary">➕ Vincular Convenio</a>
          </div>
        </div>
      </section>

      <!-- 📍 Plaza -->
      <section class="card">
        <header>📍 Plaza Asignada</header>
        <div class="content">
          <div class="info-status">
            <p><strong>Estado:</strong> <span class="status pendiente">❌ No asignada</span></p>
          </div>
          <div class="actions">
            <a href="../plazas/add.php?estudiante=1" class="btn primary">➕ Asignar Plaza</a>
          </div>
        </div>
      </section>

      <!-- 🗓️ Periodo -->
      <section class="card">
        <header>🗓️ Periodo de Servicio</header>
        <div class="content">
          <div class="info-status">
            <p><strong>Estado:</strong> <span class="status pendiente">❌ No definido</span></p>
          </div>
          <div class="actions">
            <a href="../periodos/add.php?estudiante=1" class="btn primary">➕ Asignar Periodo</a>
          </div>
        </div>
      </section>

      <!-- 📄 Documentos -->
      <section class="card">
        <header>📄 Documentos Asignados</header>
        <div class="content">
          <p><strong>Progreso:</strong> <span>0 / 12 documentos</span></p>
          <div class="actions">
            <a href="../documentos/asignar.php?estudiante=1" class="btn primary">➕ Asignar Documentos</a>
          </div>
        </div>
      </section>

      <!-- 💼 Servicio -->
      <section class="card">
        <header>💼 Proyecto / Servicio</header>
        <div class="content">
          <div class="info-status">
            <p><strong>Estado:</strong> <span class="status pendiente">❌ No registrado</span></p>
          </div>
          <div class="actions">
            <a href="../servicios/add.php?estudiante=1" class="btn primary">➕ Registrar Proyecto</a>
          </div>
        </div>
      </section>

      <!-- 📝 Observaciones -->
      <section class="card">
        <header>📝 Observaciones</header>
        <div class="content">
          <p class="observaciones">Ninguna observación registrada.</p>
          <div class="actions">
            <a href="edit.php?estudiante=1" class="btn secondary">✏️ Agregar Observaciones</a>
          </div>
        </div>
      </section>

      <!-- 🔚 Acciones finales -->
      <div class="actions final-actions">
        <a href="edit.php" class="btn primary">✏️ Editar Información</a>
        <a href="delete.php" class="btn danger">🗑️ Eliminar Estudiante</a>
      </div>
    </main>
  </div>
</body>

</html>