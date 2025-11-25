<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalle de Machote · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/css/modules/machote.css" />
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
          <h2>📑 Detalle del Machote</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="machote_list.php">Machotes</a>
            <span>›</span>
            <span>Ver</span>
          </nav>
        </div>
        <div class="actions">
          <a href="machote_edit.php?id=1" class="btn">✏️ Editar</a>
          <a href="machote_list.php" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- Información General -->
      <section class="card">
        <header>🧾 Información General del Machote</header>
        <div class="content empresa-info">
          <div class="info-grid">
            <div><strong>Nombre:</strong> Machote Institucional Base</div>
            <div><strong>Versión:</strong> v1.2</div>
            <div><strong>Empresa Asociada:</strong> <a href="../empresa/empresa_view.php?id=45" class="btn small">🏢 Casa del Barrio</a></div>
            <div><strong>Estatus:</strong> <span class="badge en_revision">En revisión</span></div>
            <div><strong>Fecha de creación:</strong> 2025-09-28</div>
            <div><strong>Última actualización:</strong> 2025-10-08</div>
          </div>
        </div>
      </section>

      <!-- Archivo original -->
      <section class="card">
        <header>📄 Archivo del Machote</header>
        <div class="content">
          <p>Archivo original cargado por el administrador.</p>
          <ul class="file-list">
            <li>
              <a href="../../uploads/machote_v12_base.pdf" target="_blank">📥 machote_v12_base.pdf</a>
            </li>
          </ul>
          <p class="text-muted">Formatos permitidos: PDF o DOCX. Máx. 10 MB.</p>
        </div>
      </section>

      <!-- Revisión activa -->
      <section class="card">
        <header>💬 Revisión activa</header>
        <div class="content">
          <p>Existe una revisión activa asociada a este machote:</p>
          <div class="info-grid">
            <div><strong>ID Revisión:</strong> #123</div>
            <div><strong>Empresa:</strong> Casa del Barrio</div>
            <div><strong>Comentarios abiertos:</strong> 1</div>
            <div><strong>Avance:</strong> 75%</div>
            <div><strong>Estado:</strong> <span class="badge en_revision">En revisión</span></div>
          </div>

          <div class="actions" style="margin-top:12px;">
            <a href="machote_revisar.php?id=123" class="btn primary">💬 Abrir revisión</a>
          </div>
        </div>
      </section>

      <!-- Historial -->
      <section class="card">
        <header>🕒 Historial de Cambios</header>
        <div class="content">
          <ul>
            <li><strong>08/10/2025</strong> — Se inició la revisión con la empresa Casa del Barrio.</li>
            <li><strong>01/10/2025</strong> — Se cargó la versión v1.2 del machote.</li>
            <li><strong>25/09/2025</strong> — Versión anterior v1.1 archivada.</li>
          </ul>
        </div>
      </section>

      <!-- Acciones finales -->
      <div class="actions">
        <a href="machote_edit.php?id=1" class="btn primary">✏️ Editar Machote</a>
        <a href="machote_delete.php?id=1" class="btn danger">🗑️ Eliminar Machote</a>
      </div>

    </main>
  </div>
</body>
</html>
