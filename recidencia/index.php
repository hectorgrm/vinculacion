<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/session.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    // Ajusta 'module' si tu login lo requiere distinto
    header('Location: ../common/login.php?module=residencias&error=unauthorized');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
        <a href="index.php">🏠 Dashboard</a>

        <!-- Núcleo con TUS rutas -->
        <a href="view/empresa/empresa_list.php">🏢 Empresas</a>
        <a href="view/convenio/convenio_list.php">📑 Convenios</a>
        <a href="view/documentos/documento_list.php">📂 Documentos</a>
        <a href="view/machote/machote_list.php">📝 Machote Comentarios</a>
        <a href="view/documentotipo/documentotipo_list.php">🗂️ Documento Tipo</a>
        <a href="view/portalacceso/portal_list.php">🔐 Portal Acceso</a>

        <!-- Reportes con submenú -->
        <!-- <div class="submenu">
          <a href="view/reportes/reportes_dashboard.php" class="submenu-title">📊 Reportes</a>
          <div class="submenu-links">
            <a href="view/reportes/reportes_dashboard.php">📈 General</a>
            <a href="view/reportes/reportes_dashboard.php#convenios">📜 Convenios</a>
            <a href="view/reportes/reportes_dashboard.php#documentos">📄 Documentos</a>
            <a href="view/reportes/reportes_dashboard.php#empresas">🏢 Por Empresa</a>
          </div>
        </div> -->

        <a href="../common/logout.php">🚪 Cerrar sesión</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Dashboard Residencia</h2>
          <p class="welcome">Hola, <?php echo htmlspecialchars((string)($user['name'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
      </header>

      <section class="card">
        <header>Bienvenido al módulo de Residencia</header>
        <div class="content">
          <p>Selecciona una de las opciones para comenzar:</p>
        </div>
      </section>

      <!-- Tarjetas principales -->
      <section class="card">
        <div class="content card-container">
          <div class="card mini">
            <h3>Gestión de Empresas</h3>
            <a href="view/empresa/empresa_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Gestión de Convenios</h3>
            <a href="view/convenio/convenio_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Gestión de Documentos</h3>
            <a href="view/documentos/documento_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Machote Comentarios</h3>
            <a href="view/machote/machote_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Documento Tipo</h3>
            <a href="view/documentotipo/documentotipo_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Portal Acceso</h3>
            <a href="view/portalacceso/portal_list.php" class="btn primary">Entrar</a>
          </div>

          <div class="card mini">
            <h3>Reportes</h3>
            <a href="view/reportes/reportes_dashboard.php" class="btn primary">Entrar</a>
          </div>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
