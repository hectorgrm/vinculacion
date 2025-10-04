<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Reportes - Servicio Social</title>
  <link rel="stylesheet" href="../assets/css/reportes.css" />
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>🎓 Portal Estudiante</h2>
    <ul>
      <li><a href="estudiante_dashboard.php">🏠 Inicio</a></li>
      <li><a href="expediente.php">📁 Expediente</a></li>
      <li><a href="documentos.php">📄 Documentos</a></li>
      <li><a href="plazas.php">🏢 Plazas</a></li>
      <li><a href="reportes.php" class="active">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="content">

    <header class="header">
      <h1>📊 Reportes y Progreso</h1>
      <p>Aquí puedes revisar el estado actual de tu Servicio Social.</p>
    </header>

    <!-- SECCIÓN 1: RESUMEN GENERAL -->
    <section class="card resumen">
      <h2>📈 Progreso General</h2>
      <div class="progress-container">
        <div class="progress-bar">
          <div class="progress" style="width: 75%"></div>
        </div>
        <p>Avance total: <strong>75%</strong></p>
      </div>

      <div class="stats">
        <div class="stat">
          <h3>📁 Documentos</h3>
          <p><strong>6</strong> entregados de <strong>8</strong></p>
        </div>
        <div class="stat">
          <h3>🏢 Horas completadas</h3>
          <p><strong>340</strong> de <strong>480</strong></p>
        </div>
        <div class="stat">
          <h3>📅 Periodo</h3>
          <p><strong>En curso</strong> - Finaliza: <strong>15/12/2025</strong></p>
        </div>
      </div>
    </section>

    <!-- SECCIÓN 2: ESTADO DE DOCUMENTOS -->
    <section class="card">
      <h2>📄 Estado de Documentos</h2>
      <table>
        <thead>
          <tr>
            <th>Documento</th>
            <th>Estatus</th>
            <th>Última actualización</th>
            <th>Observaciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Solicitud de Servicio</td>
            <td><span class="status aprobado">Aprobado</span></td>
            <td>2025-09-15</td>
            <td>Documento correcto ✅</td>
          </tr>
          <tr>
            <td>Plan de Trabajo</td>
            <td><span class="status pendiente">Pendiente</span></td>
            <td>2025-09-18</td>
            <td>En revisión</td>
          </tr>
          <tr>
            <td>Informe Final</td>
            <td><span class="status rechazado">Rechazado</span></td>
            <td>2025-09-21</td>
            <td>Falta firma del responsable</td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- SECCIÓN 3: DETALLES DE LA PLAZA -->
    <section class="card">
      <h2>🏢 Detalles de la Plaza</h2>
      <div class="plaza-details">
        <p><strong>Empresa:</strong> SoftTech Solutions</p>
        <p><strong>Área:</strong> Desarrollo Web</p>
        <p><strong>Supervisor:</strong> Ing. Laura Pérez</p>
        <p><strong>Duración:</strong> 6 meses</p>
        <p><strong>Fecha de inicio:</strong> 01/07/2025</p>
        <p><strong>Fecha de fin:</strong> 15/12/2025</p>
      </div>
    </section>

  </main>

</body>
</html>
