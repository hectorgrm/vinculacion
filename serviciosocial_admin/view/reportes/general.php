<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>📈 Reporte General · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/reportes/general.css" />
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
                <h2>📈 Reporte General del Servicio Social</h2>
                <a href="index.php" class="btn secondary">⬅ Volver al panel de reportes</a>
            </header>

            <!-- 🧾 KPIs Globales -->
            <section class="kpis">
                <div class="kpi-card">
                    <h3>👨‍🎓 Estudiantes Registrados</h3>
                    <p class="kpi-number">150</p>
                </div>
                <div class="kpi-card">
                    <h3>🏢 Empresas Vinculadas</h3>
                    <p class="kpi-number">42</p>
                </div>
                <div class="kpi-card">
                    <h3>✅ Servicios Concluidos</h3>
                    <p class="kpi-number">87</p>
                </div>
                <div class="kpi-card">
                    <h3>📄 Documentos Entregados</h3>
                    <p class="kpi-number">1,235</p>
                </div>
            </section>

            <!-- 📊 Gráfica Estado Global -->
            <section class="card">
                <header>📊 Estado Global de los Servicios</header>
                <div class="content">
                    <canvas id="estadoGlobal"></canvas>
                </div>
            </section>

            <!-- 📊 Gráfica Participación por Carrera -->
            <section class="card">
                <header>🎓 Participación por Carrera</header>
                <div class="content">
                    <canvas id="participacionCarrera"></canvas>
                </div>
            </section>

            <!-- 📊 Documentos y Cumplimiento -->
            <section class="card">
                <header>📑 Cumplimiento de Documentación</header>
                <div class="content">
                    <canvas id="documentosCumplimiento"></canvas>
                </div>
            </section>

            <!-- 📋 Tabla resumen global -->
            <section class="card">
                <header>📋 Resumen General por Periodo</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>Periodo</th>
                                <th>Estudiantes</th>
                                <th>Empresas</th>
                                <th>Servicios Concluidos</th>
                                <th>Documentos Entregados</th>
                                <th>Promedio Horas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Enero - Junio 2025</td>
                                <td>150</td>
                                <td>42</td>
                                <td>87</td>
                                <td>1,235</td>
                                <td>456 h</td>
                            </tr>
                            <tr>
                                <td>Agosto - Diciembre 2024</td>
                                <td>138</td>
                                <td>39</td>
                                <td>81</td>
                                <td>1,050</td>
                                <td>440 h</td>
                            </tr>
                            <tr>
                                <td>Enero - Junio 2024</td>
                                <td>128</td>
                                <td>34</td>
                                <td>75</td>
                                <td>980</td>
                                <td>430 h</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 📤 Acciones -->
            <div class="actions">
                <a href="export_general.php" class="btn primary">📥 Exportar Informe</a>
                <a href="index.php" class="btn secondary">⬅ Volver</a>
            </div>
        </main>
    </div>

    <!-- 📊 Scripts de Chart.js -->
    <script>
        // Estado global de servicios
        new Chart(document.getElementById("estadoGlobal"), {
            type: "doughnut",
            data: {
                labels: ["Concluidos", "En curso", "Pendientes", "Cancelados"],
                datasets: [{
                    data: [87, 43, 15, 5],
                    backgroundColor: ["#2db980", "#1f6feb", "#ffb400", "#e44848"]
                }]
            },
            options: { responsive: true }
        });

        // Participación por carrera
        new Chart(document.getElementById("participacionCarrera"), {
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

        // Documentación entregada
        new Chart(document.getElementById("documentosCumplimiento"), {
            type: "line",
            data: {
                labels: ["Plan de Trabajo", "Reporte 1", "Reporte 2", "Constancia Final"],
                datasets: [{
                    label: "Entregados",
                    data: [150, 145, 140, 130],
                    borderColor: "#2db980",
                    backgroundColor: "rgba(45,185,128,0.2)",
                    fill: true,
                    tension: 0.3
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>

</html>