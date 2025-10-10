<?php
declare(strict_types=1);
require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin', 'admin_ss'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$userName = htmlspecialchars((string)($user['name'] ?? 'Coordinador'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Institucional - Servicio Social</title>
  <link rel="stylesheet" href="assets/serviciosocialstyles.css">
</head>
<body>


  <header>
    <h1>Panel Institucional - Servicio Social</h1>
    <div class="welcome">
      <div>Bienvenido, <?php echo $userName; ?></div>
      <a class="logout-link" href="../common/logout.php">Cerrar sesión</a>
    </div>
  </header>

  <main>
    <!-- 🔔 Alertas académicas al inicio -->
    <!-- <section class="alerts">
      <div class="alert warning">⚠️ 12 documentos requieren revisión antes del cierre de periodo.</div>
      <div class="alert danger">⏰ 2 convenios expiran en menos de 10 días.</div>
      <div class="alert info">📅 El periodo Enero-Junio finalizará el 15 de junio.</div>
    </section> -->

    <!-- 📊 KPIs institucionales -->
    <!-- <section class="kpi-container">
      <div class="kpi">
        <h2>👩‍🎓 248</h2>
        <p>Estudiantes Registrados</p>
      </div>
      <div class="kpi">
        <h2>🏢 32</h2>
        <p>Empresas Colaboradoras</p>
      </div>
      <div class="kpi">
        <h2>📑 57</h2>
        <p>Documentos por Revisar</p>
      </div>
      <div class="kpi">
        <h2>🤝 12</h2>
        <p>Convenios Vigentes</p>
      </div>
      <div class="kpi">
        <h2>📆 3</h2>
        <p>Periodos Activos</p>
      </div>
    </section> -->

    <!-- 📚 Módulos principales -->
    <section class="modules">
      <h2>📂 Módulos del Sistema</h2>
      <div class="card-container">
        <div class="card">
          <h3>👩‍🎓 Estudiantes</h3>
          <a href="view/altaestudiante/estudiante_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>🏢 Empresas</h3>
          <a href="view/empresas/empresa_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>📑 Convenios</h3>
          <a href="view/convenio/convenio_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>📁 Documentos</h3>
          <a href="view/documentos/ss_doc_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>📚 Documentos Globales</h3>
          <a href="view/documentosglobales/ss_doc_global_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>📂 Plazas</h3>
          <a href="view/gestionplaza/plaza_list.php">Gestionar</a>
        </div>
        <div class="card">
          <h3>📊 Reportes</h3>
          <a href="view/reportes/reportes_dashboard.php">Visualizar</a>
        </div>
        <div class="card">
          <h3>📆 Periodos</h3>
          <a href="view/periodo/periodo_list.php">Administrar</a>
        </div>
      </div>
    </section>
  </main>




  <footer>
    © 2025 Sistema Institucional de Servicio Social – Universidad Nacional. Todos los derechos reservados.
  </footer>
</body>
</html>