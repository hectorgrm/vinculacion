<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil del Estudiante · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/estudiante/estudianteview.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Perfil del Estudiante</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📄 Información General</header>
        <div class="content student-info">
          <div class="info-row">
            <strong>Nombre:</strong>
            <span>Ana Rodríguez</span>
          </div>
          <div class="info-row">
            <strong>Matrícula:</strong>
            <span>A012345</span>
          </div>
          <div class="info-row">
            <strong>Carrera:</strong>
            <span>Ingeniería en Informática</span>
          </div>
          <div class="info-row">
            <strong>Semestre:</strong>
            <span>8</span>
          </div>
          <div class="info-row">
            <strong>Correo electrónico:</strong>
            <span>ana.rodriguez@alumno.edu.mx</span>
          </div>
          <div class="info-row">
            <strong>Teléfono:</strong>
            <span>3310000001</span>
          </div>
        </div>
      </section>

      <section class="card">
        <header>🏢 Detalles del Servicio</header>
        <div class="content student-info">
          <div class="info-row">
            <strong>Dependencia Asignada:</strong>
            <span>Secretaría de Innovación y Tecnología</span>
          </div>
          <div class="info-row">
            <strong>Proyecto:</strong>
            <span>Desarrollo de Sistema de Control de Documentos</span>
          </div>
          <div class="info-row">
            <strong>Periodo:</strong>
            <span>01/02/2025 - 31/07/2025</span>
          </div>
          <div class="info-row">
            <strong>Estado del Servicio:</strong>
            <span class="badge en_curso">En curso</span>
          </div>
          <div class="info-row">
            <strong>Horas Acumuladas:</strong>
            <span>120 / 480</span>
          </div>
        </div>
      </section>

      <section class="card">
        <header>📑 Observaciones</header>
        <div class="content">
          <p class="observaciones">
            El estudiante ha mostrado excelente desempeño en el área asignada. Pendiente entregar reporte intermedio.
          </p>
        </div>
      </section>

      <div class="actions">
        <a href="edit.php" class="btn primary">✏️ Editar Información</a>
        <a href="delete.php" class="btn danger">🗑️ Eliminar Estudiante</a>
      </div>
    </main>
  </div>
</body>
</html>
