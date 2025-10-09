<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>🏢 Detalle de Empresa · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresa/empresaview.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>🏢 Detalle de la Empresa</h2>
        <div class="topbar-actions">
          <a href="progreso.php?id_empresa=45" class="btn primary">📊 Ver Progreso de Vinculación</a>
          <a href="list.php" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- 🏢 Información General -->
      <section class="card">
        <header>📄 Información General</header>
        <div class="content empresa-info">
          <div class="info-row"><strong>Nombre:</strong> <span>Casa del Barrio</span></div>
          <div class="info-row"><strong>RFC:</strong> <span>CDB810101AA1</span></div>
          <div class="info-row"><strong>Sector:</strong> <span>Educación / Social</span></div>
          <div class="info-row"><strong>Estado:</strong> <span class="badge activo">Activo</span></div>
          <div class="info-row"><strong>Fecha de Registro:</strong> <span>2025-09-09</span></div>
        </div>
      </section>

      <!-- 👤 Información de Contacto -->
      <section class="card">
        <header>👤 Contacto Principal</header>
        <div class="content empresa-info">
          <div class="info-row"><strong>Representante Legal:</strong> <span>José Manuel Velador</span></div>
          <div class="info-row"><strong>Email:</strong> <span>contacto@casadelbarrio.mx</span></div>
          <div class="info-row"><strong>Teléfono:</strong> <span>(33) 1234 5678</span></div>
          <div class="info-row"><strong>Dirección:</strong> <span>Calle Independencia 321, Guadalajara, Jal.</span></div>
        </div>
      </section>

      <!-- 📜 Convenios Asociados -->
      <section class="card">
        <header>📜 Convenios Asociados 
          <a href="../convenios/add.php?empresa=45" class="btn small primary">+ Nuevo Convenio</a>
        </header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Estatus</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Versión</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#1</td>
                <td><span class="badge vigente">Vigente</span></td>
                <td>2025-07-01</td>
                <td>2026-06-30</td>
                <td>v1.2</td>
                <td>
                  <a href="../convenios/view.php?id=1" class="btn small">👁️ Ver</a>
                  <a href="../convenios/edit.php?id=1" class="btn small">✏️ Editar</a>
                </td>
              </tr>
              <tr>
                <td>#2</td>
                <td><span class="badge en_revision">En Revisión</span></td>
                <td>2025-08-15</td>
                <td>-</td>
                <td>v1.0</td>
                <td>
                  <a href="../convenios/view.php?id=2" class="btn small">👁️ Ver</a>
                  <a href="../convenios/edit.php?id=2" class="btn small">✏️ Editar</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 📍 Plazas Disponibles -->
      <section class="card">
        <header>📍 Plazas Ofrecidas 
          <a href="../plazas/add.php?empresa=45" class="btn small primary">+ Nueva Plaza</a>
        </header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Área</th>
                <th>Vacantes</th>
                <th>Candidatos Asignados</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#10</td>
                <td>Desarrollo Web</td>
                <td>4</td>
                <td>3</td>
                <td>
                  <a href="../plazas/view.php?id=10" class="btn small">👁️ Ver</a>
                  <a href="../plazas/edit.php?id=10" class="btn small">✏️ Editar</a>
                </td>
              </tr>
              <tr>
                <td>#11</td>
                <td>Administración de Datos</td>
                <td>2</td>
                <td>2</td>
                <td>
                  <a href="../plazas/view.php?id=11" class="btn small">👁️ Ver</a>
                  <a href="../plazas/edit.php?id=11" class="btn small">✏️ Editar</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🗓️ Periodos Activos -->
      <section class="card">
        <header>🗓️ Periodos 
          <a href="../periodos/add.php?empresa=45" class="btn small primary">+ Nuevo Periodo</a>
        </header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#2025A</td>
                <td>2025-02-01</td>
                <td>2025-07-31</td>
                <td><span class="badge en_curso">En curso</span></td>
                <td>
                  <a href="../periodos/view.php?id=2025A" class="btn small">👁️ Ver</a>
                  <a href="../periodos/edit.php?id=2025A" class="btn small">✏️ Editar</a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🧑‍🎓 Estudiantes Vinculados -->
      <section class="card">
        <header>👨‍🎓 Estudiantes Asignados</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Matrícula</th>
                <th>Plaza</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Ana Rodríguez</td>
                <td>A012345</td>
                <td>Desarrollo Web</td>
                <td><span class="badge en_curso">En curso</span></td>
                <td><a href="../estudiantes/view.php?id=2030456" class="btn small">👁️ Ver</a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- 🛠️ Acciones Finales -->
      <div class="actions">
        <a href="edit.php?id=45" class="btn primary">✏️ Editar Empresa</a>
        <a href="delete.php?id=45" class="btn danger">🗑️ Eliminar Empresa</a>
      </div>
    </main>
  </div>
</body>
</html>
