<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>🎓 Reporte por Carrera · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/reportes/carreras.css" />
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
                <h2>🎓 Reporte por Carrera</h2>
                <a href="index.php" class="btn secondary">⬅ Volver al panel de reportes</a>
            </header>

            <!-- 📊 KPIs generales -->
            <section class="kpis">
                <div class="kpi-card">
                    <h3>👨‍🎓 Total de Estudiantes</h3>
                    <p class="kpi-number">150</p>
                </div>
                <div class="kpi-card">
                    <h3>🎓 Carreras Participantes</h3>
                    <p class="kpi-number">5</p>
                </div>
                <div class="kpi-card">
                    <h3>✅ Servicios Concluidos</h3>
                    <p class="kpi-number">87</p>
                </div>
                <div class="kpi-card">
                    <h3>📈 Horas Promedio</h3>
                    <p class="kpi-number">456 h</p>
                </div>
            </section>

            <!-- 📊 Gráfica distribución por carrera -->
            <section class="card">
                <header>📊 Estudiantes por carrera</header>
                <div class="content">
                    <canvas id="carrerasChart"></canvas>
                </div>
            </section>

            <!-- 📈 Gráfica porcentaje de conclusión -->
            <section class="card">
                <header>📈 Tasa de conclusión por carrera</header>
                <div class="content">
                    <canvas id="tasaConclusion"></canvas>
                </div>
            </section>

            <!-- 📋 Tabla detallada -->
            <section class="card">
                <header>📋 Detalle por carrera</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Carrera</th>
                                <th>Estudiantes</th>
                                <th>Concluidos</th>
                                <th>En curso</th>
                                <th>Pendientes</th>
                                <th>Tasa de Conclusión</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Ingeniería en Informática</td>
                                <td>55</td>
                                <td>40</td>
                                <td>10</td>
                                <td>5</td>
                                <td><span class="badge">72%</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Ingeniería en Sistemas</td>
                                <td>40</td>
                                <td>25</td>
                                <td>12</td>
                                <td>3</td>
                                <td><span class="badge">62%</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Ingeniería Industrial</td>
                                <td>28</td>
                                <td>16</td>
                                <td>10</td>
                                <td>2</td>
                                <td><span class="badge">57%</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Administración</td>
                                <td>15</td>
                                <td>6</td>
                                <td>7</td>
                                <td>2</td>
                                <td><span class="badge">40%</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>Electrónica</td>
                                <td>12</td>
                                <td>5</td>
                                <td>5</td>
                                <td>2</td>
                                <td><span class="badge">41%</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 📤 Acciones -->
            <div class="actions">
                <a href="export_carreras.php" class="btn primary">📥 Exportar a Excel</a>
                <a href="index.php" class="btn secondary">⬅ Volver</a>
            </div>
        </main>
    </div>

    <!-- 📊 Scripts Chart.js -->
    <script>
        // Distribución por carrera
        new Chart(document.getElementById("carrerasChart"), {
            type: "bar",
            data: {
                labels: ["Informática", "Sistemas", "Industrial", "Administración", "Electrónica"],
                datasets: [{
                    label: "Estudiantes",
                    data: [55, 40, 28, 15, 12],
                    backgroundColor: "#1f6feb"
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Tasa de conclusión por carrera
        new Chart(document.getElementById("tasaConclusion"), {
            type: "line",
            data: {
                labels: ["Informática", "Sistemas", "Industrial", "Administración", "Electrónica"],
                datasets: [{
                    label: "Tasa de Conclusión (%)",
                    data: [72, 62, 57, 40, 41],
                    borderColor: "#2db980",
                    backgroundColor: "rgba(45,185,128,0.2)",
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    </script>
</body>

</html>