<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Progreso de Vinculación · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/empresa.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>📊 Progreso de Vinculación</h2>
          <p>Monitoreo de etapas y documentos de la empresa <strong>Casa del Barrio</strong>.</p>
        </div>
        <a href="empresa_view.php?id=1" class="btn secondary">⬅ Volver</a>
      </header>

      <!-- 🔵 Estado general -->
      <section class="card">
        <header>📈 Estado General</header>
        <div class="content grid-2">
          <div>
            <h3>Porcentaje de Avance</h3>
            <div class="progress-bar">
              <div class="progress" style="width: 72%">72%</div>
            </div>
            <p class="status-note">Etapa actual: Revisión jurídica del convenio</p>
          </div>

          <div class="chart-container">
            <canvas id="progressChart"></canvas>
          </div>
        </div>
      </section>

      <!-- 📋 Etapas -->
      <section class="card">
        <header>📋 Etapas del Proceso de Vinculación</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Etapa</th>
                <th>Estatus</th>
                <th>Responsable</th>
                <th>Fecha</th>
                <th>Observaciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Registro de Empresa</td>
                <td><span class="badge ok">Completado</span></td>
                <td>Admin Vinculación</td>
                <td>2025-09-10</td>
                <td>Datos verificados</td>
              </tr>
              <tr>
                <td>Subida de Documentos Legales</td>
                <td><span class="badge ok">Completado</span></td>
                <td>José Velador</td>
                <td>2025-09-11</td>
                <td>Documentos cargados correctamente</td>
              </tr>
              <tr>
                <td>Revisión Jurídica de Convenio</td>
                <td><span class="badge en_proceso">En proceso</span></td>
                <td>Depto. Jurídico</td>
                <td>2025-10-01</td>
                <td>En espera de respuesta de empresa</td>
              </tr>
              <tr>
                <td>Firma de Convenio</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>Representante Legal</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Asignación de Residentes</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>Depto. Académico</td>
                <td>-</td>
                <td>-</td>
              </tr>
              <tr>
                <td>Seguimiento de Proyectos</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>Vinculación</td>
                <td>-</td>
                <td>-</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 📄 Documentación -->
      <section class="card">
        <header>📄 Documentación Legal</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estatus</th>
                <th>Revisión</th>
                <th>Comentarios</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>INE Representante Legal</td>
                <td><span class="badge ok">Aprobado</span></td>
                <td>02/10/2025</td>
                <td>Verificado correctamente</td>
              </tr>
              <tr>
                <td>Acta Constitutiva</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>—</td>
                <td>Falta versión actualizada</td>
              </tr>
              <tr>
                <td>Comprobante de Domicilio</td>
                <td><span class="badge en_proceso">En revisión</span></td>
                <td>—</td>
                <td>Documento ilegible</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 💬 Observaciones Generales -->
      <section class="card">
        <header>💬 Observaciones Generales</header>
        <div class="content">
          <textarea rows="4" placeholder="Agregar comentario interno o nota de seguimiento..."></textarea>
          <div class="actions">
            <button class="btn primary">💾 Guardar Comentario</button>
          </div>
        </div>
      </section>

      <!-- 🧾 Historial -->
      <section class="card">
        <header>🧾 Registro de Actividad</header>
        <div class="content">
          <ul class="timeline">
            <li><strong>02/10/2025:</strong> Revisión jurídica en curso (Depto. Jurídico)</li>
            <li><strong>12/09/2025:</strong> Documentación legal validada</li>
            <li><strong>10/09/2025:</strong> Empresa registrada en sistema</li>
          </ul>
        </div>
      </section>

      <footer class="footnote">
        <small>Última actualización simulada: 9 de octubre de 2025 · Datos de ejemplo</small>
      </footer>
    </main>
  </div>

  <!-- 📊 Script de gráfica -->
  <script>
    const ctx = document.getElementById("progressChart");
    new Chart(ctx, {
      type: "doughnut",
      data: {
        labels: ["Completado", "En proceso", "Pendiente"],
        datasets: [{
          data: [40, 20, 40],
          backgroundColor: ["#2db980", "#1f6feb", "#ffb400"]
        }]
      },
      options: { responsive: true, plugins: { legend: { position: "bottom" } } }
    });
  </script>
</body>
</html>
