<?php

// ✅ Seguridad: validación de sesión y rol
require_once __DIR__ . '/../config/session.php';
$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../common/login.php?module=serviciosocial_admin&error=unauthorized');
    exit;
}

$userName = htmlspecialchars((string)($user['name'] ?? 'Coordinador'), ENT_QUOTES, 'UTF-8');
?>


<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard · Servicio Social</title>

  <!-- Estilos base del sistema -->
  <link rel="stylesheet" href="assets/css/dashboard.css">
  <!-- Estilos del dashboard principal -->
  <link rel="stylesheet" href="assets/css/home.css">

  <!-- Chart.js para las gráficas -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <!-- <?php include __DIR__ . '/view/layout/sidebar.php'; ?> -->
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"></div>
        <h1>Servicio Social · Admin</h1>
      </div>
<nav class="nav">
  <a href="index.php" class="active">🏠 Dashboard</a>
  <a href="view/estudiantes/list.php">👨‍🎓 Gestión de Estudiantes</a>
  <a href="view/empresas/list.php">🏢 Empresas</a>
  <a href="view/convenios/list.php">📑 Convenios</a>
  <a href="view/plazas/list.php">📌 Plazas</a>
  <a href="view/asignaciones/list.php">📋 Asignaciones</a>
  <a href="view/periodos/list.php">📆 Periodos</a>
  <a href="view/documentos/list.php">📂 Documentos</a>
  <!-- <a href="view/reportes/index.php">📊 Reportes</a> -->
  <a href="view/servicios/list.php">🧾 Servicios</a>
</nav>

    </aside>
    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>📊 Panel Global de Gestión</h2>
          <p class="subtitle">Vista 360° del sistema de Vinculación</p>
        </div>
        <div class="top-actions">
          <a href="view/estudiantes/add.php" class="btn primary">➕ Nuevo Estudiante</a>
          <a href="view/empresas/add.php" class="btn secondary">🏢 Nueva Empresa</a>
          <a href="view/reportes/index.php" class="btn">📈 Reportes</a>
        </div>
      </header>

      <!-- KPIs -->
      <section class="kpis">
        <div class="kpi-card">
          <h3>👨‍🎓 Estudiantes Activos</h3>
          <p class="kpi-number">132</p>
          <span class="kpi-note">+8 este mes</span>
        </div>
        <div class="kpi-card">
          <h3>✅ Servicios Concluidos</h3>
          <p class="kpi-number">87</p>
          <span class="kpi-note">Corte actual</span>
        </div>
        <div class="kpi-card">
          <h3>🏢 Empresas Activas</h3>
          <p class="kpi-number">27</p>
          <span class="kpi-note">4 en revisión</span>
        </div>
        <div class="kpi-card">
          <h3>📜 Convenios Vigentes</h3>
          <p class="kpi-number">48</p>
          <span class="kpi-note">6 por vencer</span>
        </div>
        <div class="kpi-card">
          <h3>📍 Plazas Disp.</h3>
          <p class="kpi-number">14</p>
          <span class="kpi-note">de 63 totales</span>
        </div>
      </section>

      <!-- Alertas + Acciones rápidas -->
      <section class="grid-2">
        <div class="card">
          <header>🚨 Alertas</header>
          <div class="content">
            <ul class="alerts">
              <li class="warn">⚠️ 6 convenios vencen en 30 días. <a href="view/convenios/list.php">Revisar</a></li>
              <li class="warn">⚠️ 12 estudiantes sin empresa asignada. <a href="view/estudiantes/list.php">Asignar</a></li>
              <li class="info">ℹ️ 9 documentos con retraso. <a href="view/documentos/list.php">Ver</a></li>
              <li class="ok">✅ 5 periodos terminaron correctamente.</li>
            </ul>
          </div>
        </div>

        <div class="card">
          <header>⚡ Accesos Rápidos</header>
          <div class="content quick-actions">
            <a class="qa" href="view/estudiantes/list.php">👨‍🎓 Gestionar Estudiantes</a>
            <a class="qa" href="view/empresas/list.php">🏢 Gestionar Empresas</a>
            <a class="qa" href="view/convenios/list.php">📜 Convenios</a>
            <a class="qa" href="view/plazas/list.php">📍 Plazas</a>
            <a class="qa" href="view/periodos/list.php">📆 Periodos</a>
            <a class="qa" href="view/documentos/list.php">📄 Documentos</a>
            <a class="qa" href="view/servicios/list.php">💼 Servicios</a>
            <a class="qa" href="view/reportes/index.php">📈 Reportes</a>
          </div>
        </div>
      </section>

      <!-- Gráficas -->
      <section class="charts">
        <div class="card">
          <header>📈 Estado Global de Servicios</header>
          <div class="content">
            <canvas id="chartEstado"></canvas>
          </div>
        </div>

        <div class="card">
          <header>🎓 Estudiantes por Carrera</header>
          <div class="content">
            <canvas id="chartCarreras"></canvas>
          </div>
        </div>
      </section>

      <!-- Tablas resumen -->
      <section class="grid-2">
        <div class="card">
          <header>🏆 Top 5 Empresas con más Estudiantes</header>
          <div class="content">
            <table>
              <thead>
                <tr><th>#</th><th>Empresa</th><th>Estudiantes</th></tr>
              </thead>
              <tbody>
                <tr><td>1</td><td>Secretaría de Innovación</td><td>35</td></tr>
                <tr><td>2</td><td>Municipio de Guadalajara</td><td>29</td></tr>
                <tr><td>3</td><td>Secretaría de Educación</td><td>21</td></tr>
                <tr><td>4</td><td>Hospital Civil</td><td>18</td></tr>
                <tr><td>5</td><td>IMSS Delegación Jalisco</td><td>17</td></tr>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card">
          <header>📅 Documentos con Mayor Retraso</header>
          <div class="content">
            <table>
              <thead>
                <tr><th>Documento</th><th>Retrasos</th></tr>
              </thead>
              <tbody>
                <tr><td>Reporte Parcial 1</td><td>42</td></tr>
                <tr><td>Reporte Parcial 2</td><td>35</td></tr>
                <tr><td>Constancia Final</td><td>29</td></tr>
                <tr><td>Plan de Trabajo</td><td>18</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- Pie de página -->
      <footer class="footnote">
        <small>Última actualización simulada: hoy · Conecta estos widgets a tu BD cuando gustes.</small>
      </footer>
    </main>
  </div>

  <!-- Scripts de gráficas -->
  <script>
    // Doughnut: Estado global
    new Chart(document.getElementById('chartEstado'), {
      type: 'doughnut',
      data: {
        labels: ['Concluido', 'En curso', 'Pendiente', 'Cancelado'],
        datasets: [{
          data: [87, 43, 15, 5],
          backgroundColor: ['#2db980','#1f6feb','#ffb400','#e44848']
        }]
      },
      options: { responsive: true }
    });

    // Barras: Estudiantes por carrera
    new Chart(document.getElementById('chartCarreras'), {
      type: 'bar',
      data: {
        labels: ['Informática','Sistemas','Industrial','Administración','Electrónica'],
        datasets: [{
          label: 'Estudiantes',
          data: [55,40,28,15,12],
          backgroundColor: '#1f6feb'
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
