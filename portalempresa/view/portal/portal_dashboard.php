<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Empresa - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/portal/portal_dashboard.css">
</head>
<body>

  <header>
    <h1>Portal Empresa · Residencia Profesional</h1>
    <div class="actions">
      <a href="portal_documentos.php">📂 Documentos</a>
      <a href="portal_machote.php">📜 Machote</a>
      <a href="portal_config.php">⚙️ Configuración</a>
      <a href="portal_logout.php">🚪 Cerrar Sesión</a>
    </div>
  </header>

  <main>

    <!-- 🏁 Bienvenida -->
    <section class="welcome-card">
      <h2>¡Hola, <strong>Casa del Barrio S.A.</strong> 👋!</h2>
      <p>Bienvenido al portal del <strong>TecNM Tequila</strong>. Aquí podrás revisar el estado de tu convenio, gestionar documentos, revisar observaciones del machote y mantener la información de tu empresa actualizada.</p>
    </section>

    <!-- 📊 Resumen rápido -->
    <div class="stats-grid">
      <div class="stat-card info">
        <h3>Vigente</h3>
        <p>Estado del convenio</p>
      </div>
      <div class="stat-card success">
        <h3>5</h3>
        <p>Documentos aprobados</p>
      </div>
      <div class="stat-card warning">
        <h3>2</h3>
        <p>Documentos pendientes</p>
      </div>
      <div class="stat-card danger">
        <h3>1</h3>
        <p>Documentos rechazados</p>
      </div>
    </div>

    <!-- 📁 Documentos -->
    <section class="section">
      <h2>📂 Últimos documentos</h2>
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>Tipo</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Acta constitutiva</td>
              <td><span class="status aprobado">Aprobado</span></td>
              <td>2025-10-01</td>
              <td><a href="#" class="btn btn-primary">Ver</a></td>
            </tr>
            <tr>
              <td>INE Representante</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>2025-09-28</td>
              <td><a href="#" class="btn btn-primary">Subir</a></td>
            </tr>
            <tr>
              <td>Comprobante de domicilio</td>
              <td><span class="status rechazado">Rechazado</span></td>
              <td>2025-09-20</td>
              <td><a href="#" class="btn btn-primary">Corregir</a></td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- 📝 Machote -->
    <section class="section">
      <h2>📜 Comentarios de Machote</h2>
      <p>Hay <strong>3</strong> cláusulas con comentarios pendientes de revisión.</p>
      <a href="portal_machote.php" class="btn btn-primary">Ver y responder comentarios</a>
    </section>

  </main>

</body>
</html>
