<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Revisión de Documento · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentos/documento_review.css" />

 
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>📝 Revisión de Documento</h2>
          <p>Valida y aprueba o rechaza el documento cargado por la empresa.</p>
        </div>
        <a href="documento_list.php" class="btn secondary">⬅ Volver al listado</a>
      </header>

      <!-- Vista previa -->
      <section class="card">
        <header>📄 Documento</header>
        <div class="content">
          <iframe src="../../uploads/ine_josevelador.pdf" title="Vista previa del documento"></iframe>
        </div>
      </section>

      <!-- Formulario de revisión -->
      <section class="card">
        <header>🧾 Evaluación del documento</header>
        <div class="content">
          <form action="" method="POST" class="review-form">
            <div class="field">
              <label>Empresa</label>
              <div><strong>Casa del Barrio</strong></div>
            </div>
            <div class="field">
              <label>Tipo de documento</label>
              <div>INE del representante legal</div>
            </div>

            <div class="field">
              <label>Estatus actual</label>
              <span class="badge warn">Pendiente</span>
            </div>

            <div class="field">
              <label>Nuevo estatus</label>
              <select name="estatus" required>
                <option value="pendiente">Pendiente</option>
                <option value="aprobado">Aprobado</option>
                <option value="rechazado">Rechazado</option>
              </select>
            </div>

            <div class="field full">
              <label>Observaciones</label>
              <textarea name="observacion" rows="3" placeholder="Escribe observaciones o motivos..."></textarea>
            </div>

            <div class="actions full">
              <button type="submit" class="btn primary">💾 Guardar Revisión</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
