<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mis Documentos - Servicio Social</title>
  <link rel="stylesheet" href="../assets/css/documentos.css" />
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>🎓 Portal Estudiante</h2>
    <ul>
      <li><a href="estudiante_dashboard.php">🏠 Inicio</a></li>
      <li><a href="expediente.php">📁 Mi Expediente</a></li>
      <li><a href="documentos.php" class="active">📄 Mis Documentos</a></li>
      <li><a href="plazas.php">🏢 Plazas</a></li>
      <li><a href="reportes.php">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="content">
    <header class="header">
      <h1>📄 Mis Documentos</h1>
      <p>Aquí puedes subir y consultar el estado de tus documentos requeridos para el Servicio Social.</p>
    </header>

    <!-- ESTADO GENERAL -->
    <section class="status-overview">
      <div class="status-card total">
        <h3>Total</h3>
        <p>8</p>
      </div>
      <div class="status-card aprobado">
        <h3>Aprobados</h3>
        <p>5</p>
      </div>
      <div class="status-card pendiente">
        <h3>Pendientes</h3>
        <p>2</p>
      </div>
      <div class="status-card rechazado">
        <h3>Rechazados</h3>
        <p>1</p>
      </div>
    </section>

    <!-- SUBIDA DE DOCUMENTO -->
    <section class="card upload-section">
      <h2>📤 Subir Nuevo Documento</h2>
      <form class="form">
        <div class="form-grid">
          <div class="field">
            <label for="tipo">Tipo de Documento</label>
            <select id="tipo" name="tipo" required>
              <option value="">-- Selecciona un documento --</option>
              <option value="1">Solicitud de Servicio Social</option>
              <option value="2">Carta de Aceptación</option>
              <option value="3">Plan de Trabajo</option>
              <option value="4">Reporte Parcial</option>
              <option value="5">Reporte Final</option>
            </select>
          </div>

          <div class="field">
            <label for="archivo">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <small>Solo archivos PDF. Tamaño máximo: 10MB</small>
          </div>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary">📤 Subir Documento</button>
        </div>
      </form>
    </section>

    <!-- TABLA DE DOCUMENTOS -->
    <section class="card">
      <h2>📚 Documentos Entregados</h2>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Tipo</th>
              <th>Archivo</th>
              <th>Estatus</th>
              <th>Observación</th>
              <th>Fecha</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>Solicitud de Servicio Social</td>
              <td><a href="/uploads/solicitud.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status aprobado">Aprobado</span></td>
              <td>Documento correcto</td>
              <td>2025-09-25</td>
              <td>
                <a href="#" class="btn btn-secondary">Actualizar</a>
              </td>
            </tr>
            <tr>
              <td>2</td>
              <td>Carta de Aceptación</td>
              <td><a href="/uploads/carta.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>En revisión</td>
              <td>2025-09-27</td>
              <td>
                <a href="#" class="btn btn-secondary">Actualizar</a>
              </td>
            </tr>
            <tr>
              <td>3</td>
              <td>Plan de Trabajo</td>
              <td><a href="/uploads/plan.pdf" target="_blank">📄 Ver PDF</a></td>
              <td><span class="status rechazado">Rechazado</span></td>
              <td>Falta firma institucional</td>
              <td>2025-09-28</td>
              <td>
                <a href="#" class="btn btn-secondary">Actualizar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

  </main>

</body>
</html>
