<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalles del Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicios/serviciosview.css">
      <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📊 Detalles del Servicio Social</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <!-- 📄 Información principal -->
      <section class="card">
        <header>📋 Información General del Servicio</header>
        <div class="content info-grid">
          <div>
            <h4>ID Servicio</h4>
            <p>#S-001</p>
          </div>
          <div>
            <h4>Estudiante</h4>
            <p>Laura Méndez</p>
          </div>
          <div>
            <h4>Matrícula</h4>
            <p>2056764</p>
          </div>
          <div>
            <h4>Empresa / Dependencia</h4>
            <p>Secretaría de Innovación</p>
          </div>
          <div>
            <h4>Proyecto</h4>
            <p>Sistema de Gestión Documental</p>
          </div>
          <div>
            <h4>Periodo</h4>
            <p>Enero - Junio 2025</p>
          </div>
          <div>
            <h4>Fecha de Inicio</h4>
            <p>2025-01-15</p>
          </div>
          <div>
            <h4>Fecha de Término</h4>
            <p>2025-06-15</p>
          </div>
          <div>
            <h4>Horas Requeridas</h4>
            <p>480</p>
          </div>
          <div>
            <h4>Horas Acumuladas</h4>
            <p>480</p>
          </div>
          <div>
            <h4>Estado</h4>
            <span class="badge finalizado">Concluido</span>
          </div>
        </div>
      </section>

      <!-- 📝 Observaciones -->
      <section class="card">
        <header>📝 Observaciones</header>
        <div class="content">
          <p class="descripcion">
            El estudiante completó el proyecto en tiempo y forma. Se cumplió con todas las actividades
            definidas en el plan de trabajo. El reporte final fue entregado y aprobado por la empresa receptora
            y por el asesor interno.  
          </p>
        </div>
      </section>

      <!-- ⚙️ Acciones -->
      <div class="actions">
        <a href="edit.php?id=1" class="btn primary">✏️ Editar Servicio</a>
        <a href="delete.php?id=1" class="btn danger">🗑️ Eliminar Servicio</a>
      </div>

    </main>
  </div>
</body>
</html>
