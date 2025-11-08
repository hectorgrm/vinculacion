<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin'];

if (!is_array($user) || !in_array(strtolower((string) ($user['role'] ?? '')), $allowedRoles, true)) {
  header('Location: ../common/login.php?module=residencias&error=unauthorized');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Residencia</title>
  <link rel="stylesheet" href="assets/stylesrecidencia.css">
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"></div>
        <h1>Residencias Profesionales · Admin</h1>
      </div>

      <nav class="nav">
        <a href="index.php" class="active">🏠 Dashboard</a>
        <a href="view/empresa/empresa_list.php">🏢 Empresas</a>
        <a href="view/convenio/convenio_list.php">📑 Convenios</a>
        <a href="view/documentos/documento_list.php">📂 Documentos</a>
        <a href="view/machote/machote_list.php">📝 Machote Comentario</a>
        <a href="view/machoteglobal/machote_global_list.php">📝 Machote global</a>
        <a href="view/documentotipo/documentotipo_list.php">🗂️ Tipo de Documento</a>
        <a href="view/portalacceso/portal_list.php">🔐 Portal Empresa</a>
        <a href="view/estudiante/estudiante_list.php">🧑‍🎓 Estudiante</a>

        <a href="../common/logout.php">🚪 Cerrar sesión</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <!-- ================= DASHBOARD RESIDENCIA SIMPLIFICADO ================= -->
        <link rel="stylesheet" href="assets/stylesrecidencia.css">

        <div class="dashboard">

          <!-- Header -->
          <header class="dash-header">
            <div>
              <h2>Residencias · Panel General</h2>
              <p>Periodo Ene–Jun 2026 · 42 estudiantes activos · 18 convenios vigentes · 25 empresas registradas</p>
            </div>

            <div class="dash-controls">
              <label class="searchbox">
                🔍 <input type="search" placeholder="Buscar empresa, estudiante o documento...">
              </label>
            </div>
          </header>

          <!-- KPIs -->
          <section class="kpis">
            <div class="kpi">
              <small>🎓 Estudiantes Activos</small>
              <b>42</b>
              <a href="view/estudiante/estudiante_list.php?status=activo">Ver lista →</a>
            </div>
            <div class="kpi ok">
              <small>📑 Convenios Vigentes</small>
              <b>18</b>
              <a href="view/convenio/convenio_list.php?status=vigente">Ir a convenios →</a>
            </div>
            <div class="kpi">
              <small>🏢 Empresas con Residentes</small>
              <b>25</b>
              <a href="view/empresa/empresa_list.php?f=con_residentes">Ver empresas →</a>
            </div>
            <div class="kpi warn">
              <small>📂 Documentos Pendientes</small>
              <b>7</b>
              <a href="#panel-documentos">Revisar documentos →</a>
            </div>
          </section>

          <!-- Bloque: Actividad reciente -->
          <section class="card-block">
            <header>
              <h3>🗓️ Actividad Reciente</h3>
            </header>
            <table class="table">
              <thead>
                <tr>
                  <th>Fecha</th>
                  <th>Evento</th>
                  <th>Origen</th>
                  <th>Estado</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>21/10/2025</td>
                  <td>Empresa <b>Homero Burgers</b> dejó comentario en Convenio #12</td>
                  <td>Empresa</td>
                  <td><span class="badge b-warn">Nuevo</span></td>
                  <td class="actions">
                    <a href="view/machote/machote_list.php" class="btn primary">Ver Comentario</a>
                  </td>
                </tr>
                <tr>
                  <td>20/10/2025</td>
                  <td>Estudiante <b>Juan Pérez</b> subió Reporte Intermedio</td>
                  <td>Estudiante</td>
                  <td><span class="badge b-sec">Revisión</span></td>
                  <td class="actions">
                    <a href="#panel-documentos" class="btn">Revisar</a>
                  </td>
                </tr>
                <tr>
                  <td>19/10/2025</td>
                  <td>Se aprobó el Machote de Convenio #3</td>
                  <td>Vinculación</td>
                  <td><span class="badge b-ok">Aprobado</span></td>
                  <td class="actions">
                    <a href="view/convenio/convenio_list.php" class="btn">Ver Convenio</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </section>

          <!-- Bloque: Documentos pendientes -->
          <section id="panel-documentos" class="card-block">
            <header>
              <h3>📂 Documentos Pendientes de Revisión</h3>
            </header>
            <table class="table">
              <thead>
                <tr>
                  <th>Tipo</th>
                  <th>Propietario</th>
                  <th>Relacionado</th>
                  <th>Estatus</th>
                  <th>Fecha subida</th>
                  <th>Acción</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Convenio firmado</td>
                  <td>Empresa · Barbería Gómez</td>
                  <td>Convenio #15</td>
                  <td><span class="badge b-warn">Pendiente</span></td>
                  <td>20/10/2025</td>
                  <td><a href="view/documentos/documento_list.php" class="btn primary">Revisar</a></td>
                </tr>
                <tr>
                  <td>Reporte intermedio</td>
                  <td>Estudiante · Lucía R.</td>
                  <td>Plaza #4</td>
                  <td><span class="badge b-sec">En revisión</span></td>
                  <td>19/10/2025</td>
                  <td><a href="view/documentos/documento_list.php" class="btn">Abrir</a></td>
                </tr>
              </tbody>
            </table>
          </section>

          <!-- Alertas -->
          <section class="alerts">
            <h3>⚠️ Alertas</h3>
            <ul>
              <li>📑 Convenio de <b>Homero Burgers</b> vence en 5 días.</li>
              <li>🧾 2 documentos requieren revisión urgente.</li>
              <li>👨‍🎓 <b>Juan Pérez</b> no ha subido su reporte final.</li>
            </ul>
          </section>

        </div>
        <!-- ================= /DASHBOARD RESIDENCIA ================= -->

    </main>
  </div>
</body>

</html>