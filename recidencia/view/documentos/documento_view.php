<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Detalle de Documento · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <!-- (Opcional) Estilos específicos -->
  <!-- <link rel="stylesheet" href="../../assets/css/residencias/documento_view.css" /> -->
  <style>
    .info-grid{ display:grid; grid-template-columns:1fr 1fr; gap:14px 16px }
    .field label{ display:block; font-weight:700; color:#334155; margin-bottom:4px }
    .col-span-2{ grid-column:1/3 }
    .preview{ border:1px solid #e5e7eb; border-radius:12px; overflow:hidden }
    .badge{ display:inline-block; padding:4px 8px; border-radius:999px; font-size:12px; font-weight:700; color:#fff; line-height:1 }
    .badge.ok{ background:#2db980 } .badge.warn{ background:#ffb400 } .badge.secondary{ background:#64748b }
    .actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:12px }
    @media (max-width:920px){ .info-grid{ grid-template-columns:1fr } .col-span-2{ grid-column:1/2 } }
  </style>
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
          <h2>📄 Detalle del Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="documento_list.php">Documentos</a>
            <span>›</span>
            <span>Ver</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="../../uploads/ine_josevelador.pdf" class="btn" target="_blank">📥 Descargar</a>
          <a href="documento_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <!-- Información principal -->
      <section class="card">
        <header>🧾 Información del Documento</header>
        <div class="content">
          <div class="info-grid">
            <div class="field">
              <label>Empresa</label>
              <div><a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Casa del Barrio</a></div>
            </div>

            <div class="field">
              <label>Convenio</label>
              <div><a class="btn" href="../convenio/convenio_view.php?id=12">📑 #12 (v1.2)</a></div>
            </div>

            <div class="field">
              <label>Tipo</label>
              <div>INE Representante</div>
            </div>

            <div class="field">
              <label>Estatus</label>
              <div><span class="badge ok">Aprobado</span></div>
            </div>

            <div class="field">
              <label>Fecha de carga</label>
              <div>10/09/2025</div>
            </div>

            <div class="field">
              <label>Fecha del documento</label>
              <div>08/09/2025</div>
            </div>

            <div class="field">
              <label>Tamaño</label>
              <div>0.9 MB</div>
            </div>

            <div class="field">
              <label>Archivo</label>
              <div><a class="btn" href="../../uploads/ine_josevelador.pdf" target="_blank">📄 Abrir PDF</a></div>
            </div>

            <div class="field col-span-2">
              <label>Observaciones</label>
              <div class="text-muted">Documento válido y legible; coincide con datos del representante.</div>
            </div>
          </div>

          <div class="actions" style="justify-content:flex-start;">
            <a class="btn" href="documento_upload.php?empresa=45&convenio=12&tipo=INE&copy=101">🔁 Subir nueva versión</a>
            <a class="btn" href="documento_list.php?empresa=45">📂 Ver todos de esta empresa</a>
          </div>
        </div>
      </section>

      <!-- Vista rápida (PDF) -->
      <section class="card">
        <header>👀 Vista rápida (PDF)</header>
        <div class="content preview">
          <iframe src="../../uploads/ine_josevelador.pdf" style="width:100%; height:420px; border:0;" title="Vista previa del documento"></iframe>
        </div>
      </section>

      <!-- Historial -->
      <section class="card">
        <header>🕒 Historial</header>
        <div class="content">
          <ul style="margin:0; padding-left:18px; color:#334155">
            <li><strong>10/09/2025</strong> — Documento cargado por <em>res_admin</em>. Estatus: Aprobado.</li>
            <li><strong>09/09/2025</strong> — Validación manual realizada.</li>
          </ul>
        </div>
      </section>

      <!-- Acciones finales -->
      <div class="actions">
        <a href="documento_delete.php?id=101" class="btn danger">🗑️ Eliminar Documento</a>
      </div>
    </main>
  </div>
</body>
</html>
