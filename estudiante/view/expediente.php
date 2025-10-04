<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mi Expediente - Servicio Social</title>
  <link rel="stylesheet" href="../assets/css/expediente.css" />
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>🎓 Portal Estudiante</h2>
    <ul>
      <li><a href="estudiante_dashboard.php">🏠 Inicio</a></li>
      <li><a href="expediente.php" class="active">📁 Mi Expediente</a></li>
      <li><a href="documentos.php">📄 Documentos</a></li>
      <li><a href="plazas.php">🏢 Plazas</a></li>
      <li><a href="reportes.php">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="content">
    <header class="header">
      <h1>📁 Mi Expediente</h1>
      <p>Consulta y actualiza tu información registrada en el sistema.</p>
    </header>

    <section class="card">
      <h2>👤 Información Personal</h2>
      <form class="form">
        <div class="form-grid">
          <div class="field">
            <label>Nombre completo</label>
            <input type="text" placeholder="Juan Pérez Martínez" />
          </div>
          <div class="field">
            <label>Matrícula</label>
            <input type="text" placeholder="A012345" disabled />
          </div>
          <div class="field">
            <label>Correo institucional</label>
            <input type="email" placeholder="juan.perez@tectequila.edu.mx" />
          </div>
          <div class="field">
            <label>Teléfono</label>
            <input type="text" placeholder="322-456-7890" />
          </div>
          <div class="field">
            <label>Dirección</label>
            <input type="text" placeholder="Calle Falsa 123, Tequila, Jal." />
          </div>
        </div>
      </form>
    </section>

    <section class="card">
      <h2>🎓 Información Académica</h2>
      <form class="form">
        <div class="form-grid">
          <div class="field">
            <label>Carrera</label>
            <input type="text" placeholder="Ingeniería en Informática" />
          </div>
          <div class="field">
            <label>Semestre</label>
            <input type="text" placeholder="8°" />
          </div>
          <div class="field">
            <label>Promedio general</label>
            <input type="text" placeholder="89.5" />
          </div>
          <div class="field">
            <label>Periodo de servicio</label>
            <input type="text" placeholder="Agosto - Diciembre 2025" />
          </div>
        </div>
      </form>
    </section>

    <section class="card">
      <h2>🛠️ Información de Servicio Social</h2>
      <form class="form">
        <div class="form-grid">
          <div class="field">
            <label>Estado actual</label>
            <input type="text" placeholder="Activo" disabled />
          </div>
          <div class="field">
            <label>Plaza asignada</label>
            <input type="text" placeholder="Biblioteca Central - Apoyo en sistemas" disabled />
          </div>
          <div class="field">
            <label>Horas completadas</label>
            <input type="text" placeholder="320 / 480" disabled />
          </div>
          <div class="field">
            <label>Fecha de inicio</label>
            <input type="text" placeholder="14/10/2025" disabled />
          </div>
        </div>
      </form>
    </section>

    <div class="form-actions">
      <button class="btn btn-primary">💾 Guardar cambios</button>
      <a href="estudiante_dashboard.php" class="btn btn-secondary">Cancelar</a>
    </div>
  </main>

</body>
</html>
