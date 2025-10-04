<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Panel del Estudiante - Servicio Social</title>
  <link rel="stylesheet" href="../assets/css/estudiante_dashboard.css" />
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>🎓 Portal Estudiante</h2>
    <ul>
      <li><a href="#" class="active">🏠 Inicio</a></li>
      <li><a href="expediente.php">📁 Mi Expediente</a></li>
      <li><a href="documentos.php">📄 Documentos</a></li>
      <li><a href="plazas.php">🏢 Plazas</a></li>
      <li><a href="reportes.php">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="content">
    <header class="header">
      <div>
        <h1>Bienvenido, <span class="estudiante-nombre">Juan Pérez</span></h1>
        <p>Matrícula: <strong>A012345</strong></p>
      </div>
      <div class="perfil">
        <img src="../../assets/img/avatar_estudiante.png" alt="Avatar Estudiante" />
      </div>
    </header>

    <!-- KPIs PRINCIPALES -->
    <section class="stats">
      <div class="card stat">
        <h3>📊 Progreso</h3>
        <p><strong>65%</strong> completado</p>
      </div>
      <div class="card stat">
        <h3>📄 Documentos</h3>
        <p><strong>5/8</strong> entregados</p>
      </div>
      <div class="card stat">
        <h3>⏱️ Horas Servicio</h3>
        <p><strong>320 / 480</strong> hrs</p>
      </div>
      <div class="card stat">
        <h3>📅 Plazas</h3>
        <p><strong>1</strong> asignada</p>
      </div>
    </section>

    <!-- NOTIFICACIONES -->
    <section class="card notifications">
      <h2>📢 Notificaciones recientes</h2>
      <ul>
        <li>✅ Tu documento “Carta de aceptación” fue aprobado.</li>
        <li>📁 Tienes pendiente subir el “Reporte intermedio”.</li>
        <li>📅 Tu plaza iniciará el <strong>14 de octubre</strong>.</li>
      </ul>
    </section>

    <!-- ACCESOS RÁPIDOS -->
    <section class="quick-links">
      <div class="card link">
        <h3>📁 Ver Expediente</h3>
        <p>Consulta tu información personal y de servicio.</p>
        <a href="expediente.php" class="btn">Entrar</a>
      </div>
      <div class="card link">
        <h3>📄 Entregar Documentos</h3>
        <p>Sube tus documentos pendientes y verifica su estado.</p>
        <a href="documentos.php" class="btn">Entrar</a>
      </div>
      <div class="card link">
        <h3>🏢 Ver Plazas</h3>
        <p>Consulta tus asignaciones y el estatus de tu servicio.</p>
        <a href="plazas.php" class="btn">Entrar</a>
      </div>
    </section>
  </main>

</body>
</html>
