<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>📄 Reporte de Documentos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/reportes/documentos.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📄 Reporte de Documentos</h2>
        <a href="index.php" class="btn secondary">⬅ Volver al panel de reportes</a>
      </header>

      <!-- 📊 KPIs -->
      <section class="kpis">
        <div class="kpi-card">
          <h3>📄 Documentos Totales</h3>
          <p class="kpi-number">1,500</p>
        </div>
        <div class="kpi-card">
          <h3>✅ Entregados</h3>
          <p class="kpi-number">1,235</p>
        </div>
        <div class="kpi-card">
          <h3>⏳ Pendientes</h3>
          <p class="kpi-number">180</p>
        </div>
        <div class="kpi-card">
          <h3>❌ Rechazados</h3>
          <p class="kpi-number">85</p>
        </div>
      </section>

      <!-- 📊 Gráfica general -->
      <section class="card">
        <header>📊 Estado general de los documentos</header>
        <div class="content">
          <canvas id="documentosEstado"></canvas>
        </div>
      </section>

      <!-- 📊 Gráfica por tipo -->
      <section class="card">
        <header>📈 Entregas por tipo de documento</header>
        <div class="content">
          <canvas id="documentosPorTipo"></canvas>
        </div>
      </section>

      <!-- 📋 Tabla detallada -->
      <section class="card">
        <header>📋 Documentos con más retrasos</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Documento</th>
                <th>Entregados</th>
                <th>Pendientes</th>
                <th>Rechazados</th>
                <th>Retrasos</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>📄 Reporte Parcial 1</td>
                <td>130</td>
                <td>12</td>
                <td>5</td>
                <td><span class="badge warning">42</span></td>
              </tr>
              <tr>
                <td>2</td>
                <td>📄 Reporte Parcial 2</td>
                <td>128</td>
                <td>15</td>
                <td>7</td>
                <td><span class="badge warning">35</span></td>
              </tr>
              <tr>
                <td>3</td>
                <td>📄 Constancia Final</td>
                <td>115</td>
                <td>20</td>
                <td>10</td>
                <td><span class="badge warning">29</span></td>
              </tr>
              <tr>
                <td>4</td>
                <td>📄 Plan de Trabajo</td>
                <td>140</td>
                <td>8</td>
                <td>2</td>
                <td><span class="badge warning">18</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 📤 Acciones -->
      <div class="actions">
        <a href="export_documentos.php" class="btn primary">📥 Exportar a Excel</a>
        <a href="index.php" class="btn secondary">⬅ Volver</a>
      </div>

    </main>
  </div>

  <!-- 📊 Scripts de gráficas -->
  <script>
    // 📊 Estado general
    new Chart(document.getElementById("documentosEstado"), {
      type: "doughnut",
      data: {
        labels: ["Entregados", "Pendientes", "Rechazados"],
        datasets: [{
          data: [1235, 180, 85],
          backgroundColor: ["#2db980", "#ffb400", "#e44848"]
        }]
      },
      options: { responsive: true }
    });

    // 📈 Entregas por tipo de documento
    new Chart(document.getElementById("documentosPorTipo"), {
      type: "bar",
      data: {
        labels: ["Reporte Parcial 1", "Reporte Parcial 2", "Constancia Final", "Plan de Trabajo"],
        datasets: [{
          label: "Entregados",
          data: [130, 128, 115, 140],
          backgroundColor: "#1f6feb"
        }]
      },
      options: {
        responsive: true,
        scales: { y: { beginAtZero: true } }
      }
    });
  </script>
</body>
</html>
