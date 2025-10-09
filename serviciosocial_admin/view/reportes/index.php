<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>📊 Reportes y Estadísticas · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/reportes/reportesindex.css">
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
                <h2>📊 Panel de Reportes y Estadísticas</h2>
            </header>

            <!-- === Indicadores principales === -->
            <section class="kpis">
                <div class="kpi-card">
                    <h3>👨‍🎓 Estudiantes Registrados</h3>
                    <p class="kpi-number">150</p>
                </div>
                <div class="kpi-card">
                    <h3>✅ Servicios Concluidos</h3>
                    <p class="kpi-number">87</p>
                </div>
                <div class="kpi-card">
                    <h3>🏢 Empresas Activas</h3>
                    <p class="kpi-number">42</p>
                </div>
                <div class="kpi-card">
                    <h3>📄 Documentos Entregados</h3>
                    <p class="kpi-number">1,235</p>
                </div>
            </section>

            <!-- === Gráficas === -->
            <section class="charts">
                <div class="card">
                    <header>📈 Estado de Servicios</header>
                    <div class="content">
                        <canvas id="estadoServicios"></canvas>
                    </div>
                </div>

                <div class="card">
                    <header>🎓 Estudiantes por Carrera</header>
                    <div class="content">
                        <canvas id="estudiantesCarrera"></canvas>
                    </div>
                </div>
            </section>

            <!-- === Tablas resumen === -->
            <section class="tables">
                <div class="card">
                    <header>🏆 Top 5 Empresas con Más Estudiantes</header>
                    <div class="content">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Empresa</th>
                                    <th>Estudiantes Asignados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Secretaría de Innovación</td>
                                    <td>35</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Municipio de Guadalajara</td>
                                    <td>29</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Secretaría de Educación</td>
                                    <td>21</td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Hospital Civil</td>
                                    <td>18</td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>IMSS Delegación Jalisco</td>
                                    <td>17</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <header>📅 Documentos con Mayor Retraso</header>
                    <div class="content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Documento</th>
                                    <th>Entregas Retrasadas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Reporte Parcial 1</td>
                                    <td>42</td>
                                </tr>
                                <tr>
                                    <td>Reporte Parcial 2</td>
                                    <td>35</td>
                                </tr>
                                <tr>
                                    <td>Constancia Final</td>
                                    <td>29</td>
                                </tr>
                                <tr>
                                    <td>Plan de Trabajo</td>
                                    <td>18</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

        </main>
    </div>

    <!-- === Gráficas con Chart.js === -->
    <script>
        // 📊 Estado de los servicios
        new Chart(document.getElementById("estadoServicios"), {
            type: "doughnut",
            data: {
                labels: ["Concluido", "En curso", "Pendiente", "Cancelado"],
                datasets: [{
                    data: [87, 43, 15, 5],
                    backgroundColor: ["#2db980", "#1f6feb", "#ffb400", "#e44848"],
                }]
            },
            options: { responsive: true }
        });

        // 📈 Estudiantes por carrera
        new Chart(document.getElementById("estudiantesCarrera"), {
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
    </script>
</body>

</html>