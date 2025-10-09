<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalles del Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosview.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📄 Detalles del Documento</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <!-- 📁 Información principal -->
      <section class="card">
        <header>📁 Información del Documento</header>
        <div class="content info-grid">
          <div>
            <h4>ID Documento</h4>
            <p>#D-001</p>
          </div>
          <div>
            <h4>Nombre</h4>
            <p>Solicitud de Servicio Social</p>
          </div>
          <div>
            <h4>Tipo</h4>
            <p>Global</p>
          </div>
          <div>
            <h4>Estado</h4>
            <span class="badge activo">Disponible</span>
          </div>
          <div>
            <h4>Periodo</h4>
            <p>Enero - Junio 2025</p>
          </div>
          <div>
            <h4>Fecha de Publicación</h4>
            <p>2025-01-02</p>
          </div>
          <div>
            <h4>Archivo</h4>
            <a href="../../uploads/solicitud_servicio.pdf" target="_blank" class="file-link">📄 Ver Documento</a>
          </div>
          <div>
            <h4>Asignado a</h4>
            <p>- (Documento Global)</p>
          </div>
        </div>
      </section>

      <!-- 📝 Descripción -->
      <section class="card">
        <header>📝 Descripción / Instrucciones</header>
        <div class="content">
          <p class="descripcion">
            Este documento debe ser llenado por el estudiante antes de iniciar su servicio social.
            Incluye datos personales, información del proyecto y firma del responsable de la institución receptora.
            Debe entregarse en físico en la oficina de Vinculación dentro de los primeros 10 días del periodo.
          </p>
        </div>
      </section>

      <!-- ⚙️ Acciones -->
      <div class="actions">
        <a href="edit.php?id=D-001" class="btn primary">✏️ Editar Documento</a>
        <a href="delete.php?id=D-001" class="btn danger">🗑️ Eliminar Documento</a>
      </div>
    </main>
  </div>
</body>

</html>