<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>📊 Progreso del Estudiante · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/estudiante/estudianteprogreso.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📊 Progreso del Estudiante</h2>
        <a href="view.php?id=2030456" class="btn secondary">⬅ Volver al perfil</a>
      </header>

      <!-- 🧑‍🎓 Información del estudiante -->
      <section class="card">
        <header>👤 Información General</header>
        <div class="content student-info">
          <div class="info-row"><strong>Nombre:</strong> <span>Ana Rodríguez</span></div>
          <div class="info-row"><strong>Matrícula:</strong> <span>A012345</span></div>
          <div class="info-row"><strong>Carrera:</strong> <span>Ingeniería en Informática</span></div>
          <div class="info-row"><strong>Empresa:</strong> <span>Secretaría de Innovación</span></div>
          <div class="info-row"><strong>Periodo:</strong> <span>01/02/2025 - 31/07/2025</span></div>
        </div>
      </section>

      <!-- 📈 Progreso global -->
      <section class="card">
        <header>📈 Progreso General del Servicio</header>
        <div class="content">
          <div class="progress-bar">
            <div class="bar" style="width: 45%">45%</div>
          </div>
          <p>Estado actual: <span class="status curso">En curso</span></p>
        </div>
      </section>

      <!-- 📑 Seguimiento de documentos -->
      <section class="card">
        <header>📄 Documentación Asignada</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Fecha de Entrega</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>📘 Plan de Trabajo</td>
                <td><span class="badge aprobado">Aprobado</span></td>
                <td>02/02/2025</td>
                <td><button class="btn small secondary">Ver</button></td>
              </tr>
              <tr>
                <td>📄 Reporte Parcial 1</td>
                <td><span class="badge aprobado">Aprobado</span></td>
                <td>05/03/2025</td>
                <td><button class="btn small secondary">Ver</button></td>
              </tr>
              <tr>
                <td>📄 Reporte Parcial 2</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>-</td>
                <td>
                  <button class="btn small primary">Marcar entregado</button>
                  <button class="btn small danger">Rechazar</button>
                </td>
              </tr>
              <tr>
                <td>📜 Constancia Final</td>
                <td><span class="badge noentregado">No entregado</span></td>
                <td>-</td>
                <td>
                  <button class="btn small primary">Marcar entregado</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🕐 Horas del servicio -->
      <section class="card">
        <header>🕐 Horas Acumuladas</header>
        <div class="content">
          <p><strong>Horas registradas:</strong> <span>180 / 480</span></p>
          <div class="actions">
            <input type="number" placeholder="Agregar horas..." style="width:150px;">
            <button class="btn primary">Actualizar</button>
          </div>
        </div>
      </section>

      <!-- 📊 Etapas del proceso -->
      <section class="card">
        <header>📊 Etapas del Proceso</header>
        <div class="content">
          <ul class="timeline">
            <li class="done">✅ Alta del Estudiante</li>
            <li class="done">✅ Empresa Asignada</li>
            <li class="done">✅ Convenio Vinculado</li>
            <li class="done">✅ Plaza Asignada</li>
            <li class="done">✅ Periodo Definido</li>
            <li class="in-progress">⏳ Documentación en curso</li>
            <li class="pending">❌ Servicio Finalizado</li>
          </ul>
        </div>
      </section>

      <!-- 📝 Observaciones -->
      <section class="card">
        <header>📝 Observaciones del Administrador</header>
        <div class="content">
          <textarea rows="4" placeholder="Agregar notas o comentarios sobre el progreso..."></textarea>
          <div class="actions">
            <button class="btn primary">💾 Guardar Observación</button>
          </div>
        </div>
      </section>

      <!-- 📤 Acciones finales -->
      <div class="actions final-actions">
        <a href="view.php?id=2030456" class="btn secondary">⬅ Volver al perfil</a>
        <button class="btn primary">📁 Generar Reporte Final</button>
      </div>
    </main>
  </div>
</body>
</html>
