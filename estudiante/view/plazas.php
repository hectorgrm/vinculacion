<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Plazas Disponibles - Servicio Social</title>
  <link rel="stylesheet" href="../assets/css/plazas.css" />
</head>
<body>

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <h2>🎓 Portal Estudiante</h2>
    <ul>
      <li><a href="estudiante_dashboard.php">🏠 Inicio</a></li>
      <li><a href="expediente.php">📁 Expediente</a></li>
      <li><a href="documentos.php">📄 Documentos</a></li>
      <li><a href="plazas.php" class="active">🏢 Plazas</a></li>
      <li><a href="reportes.php">📊 Reportes</a></li>
      <li><a href="logout.php">🚪 Cerrar Sesión</a></li>
    </ul>
  </aside>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="content">
    <header class="header">
      <h1>🏢 Plazas Disponibles</h1>
      <p>Consulta las oportunidades disponibles para realizar tu Servicio Social.</p>
    </header>

    <!-- BARRA DE FILTRO -->
    <section class="filter-bar">
      <form class="filter-form">
        <input type="text" placeholder="🔍 Buscar por empresa o área..." />
        <select>
          <option value="">Todas las áreas</option>
          <option value="Desarrollo Web">Desarrollo Web</option>
          <option value="Administración">Administración</option>
          <option value="Atención al cliente">Atención al cliente</option>
        </select>
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </section>

    <!-- LISTADO DE PLAZAS -->
    <section class="plaza-list">

      <!-- PLAZA 1 -->
      <div class="plaza-card">
        <div class="plaza-info">
          <h2>💼 Empresa: SoftTech Solutions</h2>
          <p><strong>Área:</strong> Desarrollo Web</p>
          <p><strong>Duración:</strong> 6 meses</p>
          <p><strong>Horario:</strong> Lunes a Viernes, 9:00 - 14:00</p>
          <p><strong>Responsable:</strong> Ing. Laura Pérez</p>
          <p><strong>Requisitos:</strong> Conocimientos en HTML, CSS, PHP</p>
        </div>
        <div class="plaza-actions">
          <button class="btn btn-primary">Postularme</button>
        </div>
      </div>

      <!-- PLAZA 2 -->
      <div class="plaza-card">
        <div class="plaza-info">
          <h2>🏢 Empresa: Servicios Tequila</h2>
          <p><strong>Área:</strong> Administración</p>
          <p><strong>Duración:</strong> 4 meses</p>
          <p><strong>Horario:</strong> Lunes a Viernes, 10:00 - 14:00</p>
          <p><strong>Responsable:</strong> Lic. Martín Rodríguez</p>
          <p><strong>Requisitos:</strong> Manejo de Excel y bases de datos</p>
        </div>
        <div class="plaza-actions">
          <button class="btn btn-primary">Postularme</button>
        </div>
      </div>

      <!-- PLAZA 3 -->
      <div class="plaza-card">
        <div class="plaza-info">
          <h2>📊 Empresa: Grupo Innovatec</h2>
          <p><strong>Área:</strong> Atención al cliente</p>
          <p><strong>Duración:</strong> 5 meses</p>
          <p><strong>Horario:</strong> Lunes a Viernes, 8:00 - 13:00</p>
          <p><strong>Responsable:</strong> Lic. Sofía Gómez</p>
          <p><strong>Requisitos:</strong> Comunicación efectiva y empatía</p>
        </div>
        <div class="plaza-actions">
          <button class="btn btn-primary">Postularme</button>
        </div>
      </div>

    </section>
  </main>

</body>
</html>
