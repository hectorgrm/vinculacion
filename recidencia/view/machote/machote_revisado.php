<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Machote Revisado · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/css/machote/revisar.css">
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">

      <!-- 🧭 Encabezado -->
      <header class="topbar success">
        <div>
          <h2>📝 Machote Revisado y Aprobado</h2>
          <p class="subtitle">
            Empresa: <strong>Casa del Barrio</strong> · Machote: <strong>Institucional v1.2</strong> ·
            Estado: <span class="badge ok">Aprobado</span>
          </p>
        </div>
        <div class="actions">
          <button class="btn primary">📄 Generar Convenio</button>
          <a href="../empresa/empresa_view.php?id=45" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- 📊 KPIs -->
      <section class="card kpi-section">
        <div class="kpi">
          <h4>Hilos abiertos</h4>
          <div class="kpi-num">0</div>
        </div>
        <div class="kpi">
          <h4>Hilos resueltos</h4>
          <div class="kpi-num">4</div>
        </div>
        <div class="kpi wide">
          <h4>Progreso</h4>
          <div class="progress"><div class="bar" style="width: 100%"></div></div>
          <small>100% resuelto</small>
        </div>
        <div class="approvals">
          <label class="switch">
            <input type="checkbox" checked disabled>
            <span class="slider"></span>
            <span class="label">Aprobado Admin</span>
          </label>
          <label class="switch">
            <input type="checkbox" checked disabled>
            <span class="slider"></span>
            <span class="label">Aprobado Empresa</span>
          </label>
        </div>
      </section>

      <!-- ✅ Resumen de comentarios -->
      <section class="card">
        <header>🧾 Resumen Final de Comentarios</header>
        <div class="content threads">
          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Resuelto</span>
              <span class="author empresa">Empresa</span>
              <span class="time">hace 3 días</span>
            </div>
            <h4>Cláusula de vigencia</h4>
            <p>Se modificó a 18 meses conforme a la propuesta de la empresa.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Resuelto</span>
              <span class="author admin">Admin</span>
              <span class="time">hace 2 días</span>
            </div>
            <h4>Confidencialidad</h4>
            <p>Se anexó cláusula adicional de confidencialidad estándar.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Resuelto</span>
              <span class="author empresa">Empresa</span>
              <span class="time">hace 1 día</span>
            </div>
            <h4>Propiedad Intelectual</h4>
            <p>Se acordó que los entregables estarán bajo licencia interna compartida.</p>
          </article>

          <article class="thread">
            <div class="meta">
              <span class="badge resuelto">Resuelto</span>
              <span class="author admin">Admin</span>
              <span class="time">hace 1 día</span>
            </div>
            <h4>Firmas</h4>
            <p>Se confirmó que ambas partes firmarán versión digital.</p>
          </article>
        </div>
      </section>

      <!-- 📎 Archivos finales -->
      <section class="card">
        <header>📎 Archivos Finales del Machote</header>
        <div class="content">
          <ul class="file-list">
            <li><a href="../../uploads/machote_v12_final.pdf" target="_blank">📄 Machote Institucional v1.2 (Final)</a></li>
            <li><a href="../../uploads/convenio_v12.pdf" target="_blank">📑 Convenio firmado (PDF)</a></li>
          </ul>
        </div>
      </section>

      <footer class="hint">
        <small>✅ Este machote fue revisado, aprobado por ambas partes y listo para generar el convenio.</small>
      </footer>
    </main>
  </div>
</body>
</html>
