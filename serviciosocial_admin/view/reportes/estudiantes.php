<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>👨‍🎓 Reporte por Estudiante · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/reportes/estudiantes.css" />
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
                <h2>👨‍🎓 Reporte de Estudiantes</h2>
                <a href="index.php" class="btn secondary">⬅ Volver al panel de reportes</a>
            </header>

            <!-- 📊 KPIs -->
            <section class="kpis">
                <div class="kpi-card">
                    <h3>👨‍🎓 Total de Estudiantes</h3>
                    <p class="kpi-number">150</p>
                </div>
                <div class="kpi-card">
                    <h3>✅ Concluidos</h3>
                    <p class="kpi-number">87</p>
                </div>
                <div class="kpi-card">
                    <h3>📈 En Curso</h3>
                    <p class="kpi-number">43</p>
                </div>
                <div class="kpi-card">
                    <h3>⏳ Pendientes</h3>
                    <p class="kpi-number">15</p>
                </div>
            </section>

            <!-- 📊 Gráfica de estado -->
            <section class="card">
                <header>📊 Estado general del servicio social</header>
                <div class="content">
                    <canvas id="estadoEstudiantes"></canvas>
                </div>
            </section>

            <!-- 📋 Tabla de progreso -->
            <section class="card">
                <header>📋 Progreso individual de estudiantes</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Estudiante</th>
                                <th>Matrícula</th>
                                <th>Empresa</th>
                                <th>Horas</th>
                                <th>Documentos</th>
                                <th>Estado</th>
                                <th>Progreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Laura Méndez</td>
                                <td>2056764</td>
                                <td>Secretaría de Innovación</td>
                                <td>480 / 480</td>
                                <td>12 / 12</td>
                                <td><span class="badge finalizado">Concluido</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="bar" style="width: 100%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Juan Pérez</td>
                                <td>2049821</td>
                                <td>Municipio de Guadalajara</td>
                                <td>320 / 480</td>
                                <td>8 / 12</td>
                                <td><span class="badge curso">En curso</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="bar" style="width: 66%"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>María López</td>
                                <td>2030456</td>
                                <td>Hospital Civil</td>
                                <td>100 / 480</td>
                                <td>3 / 12</td>
                                <td><span class="badge pendiente">Pendiente</span></td>
                                <td>
                                    <div class="progress">
                                        <div class="bar" style="width: 20%"></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- 📤 Acciones -->
            <div class="actions">
                <a href="export_estudiantes.php" class="btn primary">📥 Exportar a Excel</a>
                <a href="index.php" class="btn secondary">⬅ Volver</a>
            </div>
        </main>
    </div>

    <!-- 📊 Script gráfica -->
    <script>
        new Chart(document.getElementById("estadoEstudiantes"), {
            type: "doughnut",
            data: {
                labels: ["Concluido", "En curso", "Pendiente"],
                datasets: [{
                    data: [87, 43, 15],
                    backgroundColor: ["#2db980", "#1f6feb", "#ffb400"]
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>

</html>