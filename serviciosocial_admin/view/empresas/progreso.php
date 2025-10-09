<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>📊 Progreso de Vinculación · Empresa</title>
  <link rel="stylesheet" href="../../assets/css/empresa/empresaprogreso.css">
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
        <h2>📊 Progreso de Vinculación – Casa del Barrio</h2>
        <a href="view.php?id_empresa=45" class="btn secondary">⬅ Volver a la empresa</a>
      </header>

      <!-- 🏢 Resumen general -->
      <section class="kpis">
        <div class="kpi-card">
          <h3>📜 Convenios Firmados</h3>
          <p class="kpi-number">4</p>
        </div>
        <div class="kpi-card">
          <h3>📍 Plazas Ofrecidas</h3>
          <p class="kpi-number">12</p>
        </div>
        <div class="kpi-card">
          <h3>👨‍🎓 Estudiantes Vinculados</h3>
          <p class="kpi-number">48</p>
        </div>
        <div class="kpi-card">
          <h3>📅 Periodos Completados</h3>
          <p class="kpi-number">5</p>
        </div>
      </section>

      <!-- 📈 Gráficas -->
      <section class="charts">
        <div class="card">
          <header>📊 Estudiantes por Periodo</header>
          <div class="content">
            <canvas id="estudiantesPorPeriodo"></canvas>
          </div>
        </div>

        <div class="card">
          <header>📈 Plazas Ocupadas vs Disponibles</header>
          <div class="content">
            <canvas id="plazasChart"></canvas>
          </div>
        </div>
      </section>

      <!-- 📜 Historial de convenios -->
      <section class="card">
        <header>📜 Historial de Convenios</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Estatus</th>
                <th>Fecha Inicio</th>
                <th>Fecha Fin</th>
                <th>Duración</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#1</td>
                <td><span class="badge vigente">Vigente</span></td>
                <td>2025-07-01</td>
                <td>2026-06-30</td>
                <td>12 meses</td>
              </tr>
              <tr>
                <td>#2</td>
                <td><span class="badge concluido">Concluido</span></td>
                <td>2024-07-01</td>
                <td>2025-06-30</td>
                <td>12 meses</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 👨‍🎓 Estudiantes vinculados -->
      <section class="card">
        <header>👨‍🎓 Historial de Estudiantes Vinculados</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Carrera</th>
                <th>Periodo</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Ana Rodríguez</td>
                <td>Informática</td>
                <td>2025A</td>
                <td><span class="badge concluido">Concluido</span></td>
              </tr>
              <tr>
                <td>Juan Pérez</td>
                <td>Sistemas</td>
                <td>2025A</td>
                <td><span class="badge en_curso">En curso</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 📁 Documentación -->
      <section class="card">
        <header>📁 Documentación de Vinculación</header>
        <div class="content">
          <ul class="docs-list">
            <li>📄 Convenio 2025 (PDF) – <a href="#">Descargar</a></li>
            <li>📄 Informe de Periodo 2024 – <a href="#">Descargar</a></li>
            <li>📄 Carta Compromiso – <a href="#">Descargar</a></li>
          </ul>
        </div>
      </section>

      <!-- ✅ Acciones finales -->
      <div class="actions">
        <button class="btn primary">📁 Generar Reporte de Empresa</button>
        <button class="btn danger">🔒 Cerrar Vinculación</button>
      </div>
    </main>
  </div>

  <!-- === Scripts de gráficas === -->
  <script>
    new Chart(document.getElementById("estudiantesPorPeriodo"), {
      type: "bar",
      data: {
        labels: ["2023A", "2023B", "2024A", "2024B", "2025A"],
        datasets: [{
          label: "Estudiantes",
          data: [8, 12, 15, 10, 18],
          backgroundColor: "#1f6feb"
        }]
      },
      options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });

    new Chart(document.getElementById("plazasChart"), {
      type: "doughnut",
      data: {
        labels: ["Ocupadas", "Disponibles"],
        datasets: [{
          data: [10, 2],
          backgroundColor: ["#2db980", "#e5e7eb"]
        }]
      },
      options: { responsive: true }
    });
  </script>
</body>
</html>
