<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>🏢 Reporte por Empresa · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/reportes/empresas.css" />
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
                <h2>🏢 Reporte por Empresa</h2>
                <a href="index.php" class="btn secondary">⬅ Volver al panel de reportes</a>
            </header>

            <!-- 📊 Resumen general -->
            <section class="kpis">
                <div class="kpi-card">
                    <h3>🏢 Empresas registradas</h3>
                    <p class="kpi-number">42</p>
                </div>
                <div class="kpi-card">
                    <h3>👨‍🎓 Estudiantes asignados</h3>
                    <p class="kpi-number">150</p>
                </div>
                <div class="kpi-card">
                    <h3>📈 Servicios concluidos</h3>
                    <p class="kpi-number">87</p>
                </div>
                <div class="kpi-card">
                    <h3>📌 Plazas disponibles</h3>
                    <p class="kpi-number">68</p>
                </div>
            </section>

            <!-- 📊 Gráfica de empresas con más estudiantes -->
            <section class="card">
                <header>📊 Top 5 empresas con mayor participación</header>
                <div class="content">
                    <canvas id="empresasChart"></canvas>
                </div>
            </section>

            <!-- 📋 Tabla detallada -->
            <section class="card">
                <header>📋 Detalle de empresas registradas</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Empresa</th>
                                <th>Estudiantes</th>
                                <th>Plazas</th>
                                <th>Servicios Concluidos</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Secretaría de Innovación</td>
                                <td>35</td>
                                <td>12</td>
                                <td>29</td>
                                <td><span class="badge activo">Activa</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Municipio de Guadalajara</td>
                                <td>29</td>
                                <td>10</td>
                                <td>21</td>
                                <td><span class="badge activo">Activa</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Secretaría de Educación</td>
                                <td>21</td>
                                <td>8</td>
                                <td>15</td>
                                <td><span class="badge activo">Activa</span></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td>Hospital Civil</td>
                                <td>18</td>
                                <td>6</td>
                                <td>13</td>
                                <td><span class="badge activo">Activa</span></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td>IMSS Delegación Jalisco</td>
                                <td>17</td>
                                <td>5</td>
                                <td>10</td>
                                <td><span class="badge inactiva">Inactiva</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 📤 Acciones -->
            <div class="actions">
                <a href="export_empresas.php" class="btn primary">📥 Exportar a Excel</a>
                <a href="index.php" class="btn secondary">⬅ Volver</a>
            </div>

        </main>
    </div>

    <!-- 📊 Script de gráfica -->
    <script>
        new Chart(document.getElementById("empresasChart"), {
            type: "bar",
            data: {
                labels: ["Secretaría de Innovación", "Municipio Guadalajara", "Sec. Educación", "Hospital Civil", "IMSS Jalisco"],
                datasets: [{
                    label: "Estudiantes asignados",
                    data: [35, 29, 21, 18, 17],
                    backgroundColor: "#1f6feb"
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>
</body>

</html>