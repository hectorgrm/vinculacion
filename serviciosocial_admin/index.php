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
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel Principal · Servicio Social</title>
  <link rel="stylesheet" href="assets/css/dashboard.css"
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <aside class="sidebar">
      <div class="brand">
        <div class="logo"></div>
        <h1>Servicio Social · Admin</h1>
      </div>
      <nav class="nav">
        <a href="#" class="active">Dashboard</a>
        <a href="view/estudiantes/list.php">Gestión de Estudiantes</a>
        <a href="view/empresas/list.php">Empresas</a>
        <a href="view/convenios/list.php">Convenios</a>
        <a href="view/plazas/list.php">Plazas</a>
        <a href="view/asignaciones/list.php">Asignaciones</a>
        <a href="view/periodos/list.php">Periodos</a>
        <a href="view/documentos/list.php">Documentos</a>
        <a href="view/reportes/index.php">Reportes</a>
      </nav>
    </aside>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Panel de Control · Servicio Social</h2>
        <div>Bienvenido, <?php echo $userName; ?> | <a href="../common/logout.php">Cerrar sesión</a></div>
      </header>

      <!-- KPIs principales -->
      <section class="stats">
        <div class="stat">
          <h3>Estudiantes registrados</h3>
          <p>248</p>
        </div>
        <div class="stat">
          <h3>Empresas colaboradoras</h3>
          <p>32</p>
        </div>
        <div class="stat">
          <h3>Documentos por revisar</h3>
          <p>57</p>
        </div>
        <div class="stat">
          <h3>Convenios vigentes</h3>
          <p>12</p>
        </div>
        <div class="stat">
          <h3>Periodos activos</h3>
          <p>3</p>
        </div>
      </section>

      <section class="grid-2">
        <article class="card">
          <header>Estudiantes recientes</header>
          <div class="content">
            <table>
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Matrícula</th>
                  <th>Empresa</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>María López</td>
                  <td>2030456</td>
                  <td>Gobierno de Jalisco</td>
                  <td><span class="status pendiente">Sin asignación</span></td>
                </tr>
                <tr>
                  <td>Juan Pérez</td>
                  <td>2049821</td>
                  <td>Hospital Civil</td>
                  <td><span class="status ok">Activo</span></td>
                </tr>
                <tr>
                  <td>Laura Méndez</td>
                  <td>2056764</td>
                  <td>IMSS</td>
                  <td><span class="status ok">Activo</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </article>

        <article class="card">
          <header>Alertas y notificaciones</header>
          <div class="content">
            <ul>
              <li>📄 12 estudiantes no han entregado su plan de trabajo.</li>
              <li>⚠️ 4 convenios están próximos a vencer.</li>
              <li>🏢 Nueva empresa vinculada: “Secretaría de Cultura”.</li>
            </ul>
          </div>
        </article>
      </section>
    </main>
  </div>
</body>
</html>