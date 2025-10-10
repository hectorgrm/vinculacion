<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Subir Documento · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
      <link rel="stylesheet" href="../../assets/css/documentos/documento_upload.css" />


  <!-- Estilos mínimos para el dropzone (opcional, puedes moverlos a un .css) -->
  <style>
    .dropzone{
      display:flex; flex-direction:column; align-items:center; justify-content:center;
      gap:6px; padding:22px; border:2px dashed #cbd5e1; border-radius:14px; background:#f8fafc;
      text-align:center; transition: background .2s, border-color .2s;
    }
    .dropzone:hover{ background:#f1f5f9; border-color:#94a3b8 }
    .dropzone input[type="file"]{ width:100%; }
    .file-hint{ color:#64748b; font-size:13px; }
  </style>
</head>
<body>
  <?php
    // Prefills opcionales por query string
    $empresaId  = isset($_GET['empresa'])  ? (int) $_GET['empresa']  : null;
    $convenioId = isset($_GET['convenio']) ? (int) $_GET['convenio'] : null;
    $tipoQS     = isset($_GET['tipo'])     ? (string) $_GET['tipo']  : '';
  ?>

  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>⬆️ Subir Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="documento_list.php<?php echo $empresaId ? '?empresa='.$empresaId : '';?>">Documentos</a>
            <span>›</span>
            <span>Subir</span>
          </nav>
        </div>
        <a href="documento_list.php<?php echo $empresaId ? '?empresa='.$empresaId : '';?>"
           class="btn">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📄 Datos del Documento</header>
        <div class="content">
          <p class="text-muted" style="margin-top:-6px">
            Asocia el documento a una empresa y, si aplica, a un convenio. Define su tipo, estatus y agrega observaciones.
          </p>

          <form class="form" action="documento_upload_action.php" method="post" enctype="multipart/form-data">
            <div class="grid">
              <!-- Empresa -->
              <?php if ($empresaId): ?>
                <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
                <div class="field">
                  <label>Empresa</label>
                  <div style="display:flex; gap:8px; align-items:center;">
                    <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 Empresa #<?php echo $empresaId; ?></a>
                    <span class="text-muted">(preseleccionada)</span>
                  </div>
                </div>
              <?php else: ?>
                <div class="field">
                  <label for="empresa_id" class="required">Empresa *</label>
                  <select id="empresa_id" name="empresa_id" required>
                    <option value="">— Selecciona una empresa —</option>
                    <option value="45">Casa del Barrio</option>
                    <option value="22">Tequila ECT</option>
                    <option value="31">Industrias Yakumo</option>
                    <!-- 🔁 Poblar dinámicamente desde rp_empresa -->
                  </select>
                </div>
              <?php endif; ?>

              <!-- Convenio (opcional) -->
              <?php if ($convenioId): ?>
                <input type="hidden" name="convenio_id" value="<?php echo $convenioId; ?>">
                <div class="field">
                  <label>Convenio (opcional)</label>
                  <div style="display:flex; gap:8px; align-items:center;">
                    <a class="btn" href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">📑 Convenio #<?php echo $convenioId; ?></a>
                    <span class="text-muted">(preseleccionado)</span>
                  </div>
                </div>
              <?php else: ?>
                <div class="field">
                  <label for="convenio_id">Convenio (opcional)</label>
                  <select id="convenio_id" name="convenio_id">
                    <option value="">— Sin convenio —</option>
                    <option value="12">#12 (v1.2)</option>
                    <option value="15">#15 (v2.0)</option>
                    <!-- 🔁 Poblar dinámicamente según empresa -->
                  </select>
                  <div class="help">Sugerencia: selecciona primero la empresa para filtrar convenios.</div>
                </div>
              <?php endif; ?>

              <!-- Tipo de documento -->
              <div class="field">
                <label for="tipo_documento" class="required">Tipo de documento *</label>
                <select id="tipo_documento" name="tipo_documento" required>
                  <option value="">— Selecciona un tipo —</option>
                  <option value="INE"      <?php echo $tipoQS === 'INE'      ? 'selected' : ''; ?>>INE Representante</option>
                  <option value="ACTA"     <?php echo $tipoQS === 'ACTA'     ? 'selected' : ''; ?>>Acta Constitutiva</option>
                  <option value="ANEXO"    <?php echo $tipoQS === 'ANEXO'    ? 'selected' : ''; ?>>Anexo Técnico</option>
                  <option value="OFICIO"   <?php echo $tipoQS === 'OFICIO'   ? 'selected' : ''; ?>>Oficio de Intención</option>
                  <option value="CONVENIO" <?php echo $tipoQS === 'CONVENIO' ? 'selected' : ''; ?>>Convenio (PDF)</option>
                  <!-- 🔁 Poblar dinámicamente desde rp_documento_tipo -->
                </select>
              </div>

              <!-- Estatus -->
              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <option value="pendiente" selected>Pendiente</option>
                  <option value="aprobado">Aprobado</option>
                  <option value="rechazado">Rechazado</option>
                </select>
              </div>

              <!-- Archivo -->
              <div class="field col-span-2">
                <label for="archivo" class="required">Archivo *</label>
                <div class="dropzone">
                  <input type="file" id="archivo" name="archivo" accept="application/pdf,image/*" required />
                  <div>Arrastra tu archivo aquí o haz clic para seleccionar</div>
                  <div class="file-hint">Formatos permitidos: PDF o imagen (JPG/PNG). Tamaño máx. 10 MB.</div>
                </div>
              </div>

              <!-- Fecha de documento (opcional) -->
              <div class="field">
                <label for="fecha_doc">Fecha del documento (opcional)</label>
                <input type="date" id="fecha_doc" name="fecha_doc" />
              </div>

              <!-- Observaciones -->
              <div class="field col-span-2">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" rows="4" placeholder="Comentarios o notas internas..."></textarea>
              </div>
            </div>

            <div class="actions">
              <a href="documento_list.php<?php echo $empresaId ? '?empresa='.$empresaId : '';?>"
                 class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn primary">💾 Guardar y Subir</button>
            </div>
          </form>
        </div>
      </section>

      <!-- Accesos rápidos (opcionales) -->
      <?php if ($empresaId): ?>
        <section class="card">
          <header>Accesos rápidos</header>
          <div class="content actions" style="justify-content:flex-start;">
            <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 Ver empresa</a>
            <?php if ($convenioId): ?>
              <a class="btn" href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">📑 Ver convenio</a>
            <?php endif; ?>
            <a class="btn" href="documento_list.php?empresa=<?php echo $empresaId; ?>">📂 Ver documentos de esta empresa</a>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
