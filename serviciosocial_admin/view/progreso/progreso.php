
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Progreso del Estudiante · Servicio Social</title>

     <link rel="stylesheet" href="../../assets/css/estudiante/estudianteprogreso.css">
   <link rel="stylesheet" href="../../assets/css/dashboard.css">


  </head>
<body>
  <div class="app">
    <aside class="sidebar">
      <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    
    </aside>

    <main class="main">
      <header class="topbar">
        <h2>Progreso del Estudiante – María López</h2>
        <a href="../estudiantes/profile.html" class="btn secondary">⬅ Volver al perfil</a>
      </header>

      <!-- 🟢 Progreso general -->
      <section class="card">
        <header>Horas de Servicio Social</header>
        <div class="content">
          <div class="progress-container">
            <div class="progress-label">
              <span>288 / 480 horas completadas</span>
              <span>60%</span>
            </div>
            <div class="progress-bar">
              <div class="progress-fill" style="width:60%">60%</div>
            </div>
          </div>
        </div>
      </section>

      <!-- 📆 Periodos -->
      <section class="card">
        <header>Periodos de Servicio</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Periodo</th>
                <th>Fechas</th>
                <th>Estado</th>
                <th>Horas</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>15 Ene 2025 – 15 Mar 2025</td>
                <td><span class="status ok">Completado</span></td>
                <td>160</td>
              </tr>
              <tr>
                <td>2</td>
                <td>16 Mar 2025 – 16 May 2025</td>
                <td><span class="status pending">En curso</span></td>
                <td>128</td>
              </tr>
              <tr>
                <td>3</td>
                <td>17 May 2025 – 17 Jul 2025</td>
                <td><span class="status delayed">Pendiente</span></td>
                <td>0</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 📁 Documentos -->
      <section class="card">
        <header>Documentación del Estudiante</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Última actualización</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Carta de presentación</td>
                <td><span class="status ok">Aprobado</span></td>
                <td>20 Ene 2025</td>
              </tr>
              <tr>
                <td>Plan de trabajo</td>
                <td><span class="status pending">En revisión</span></td>
                <td>25 Ene 2025</td>
              </tr>
              <tr>
                <td>Carta de terminación</td>
                <td><span class="status delayed">No entregado</span></td>
                <td>-</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

                <section class="card">
        <header>Historial de Descargas</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Fecha de descarga</th>
                <th>IP</th>
                <th>Dispositivo</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Carta de presentación</td>
                <td>15 Ene 2025 - 10:34 AM</td>
                <td>187.233.55.101</td>
                <td>Chrome / Windows</td>
              </tr>
              <tr>
                <td>Plan de trabajo</td>
                <td>20 Ene 2025 - 11:12 AM</td>
                <td>187.233.55.101</td>
                <td>Chrome / Windows</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

    </main>
  </div>
</body>
</html>
