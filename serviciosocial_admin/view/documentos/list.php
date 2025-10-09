<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Documentos · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentoslist.css">
      <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📁 Gestión de Documentos</h2>
        <button class="btn primary" onclick="window.location.href='add.php'">+ Nuevo Documento</button>
      </header>

      <section class="card">
        <header>Listado de Documentos</header>
        <div class="content">

          <!-- 🔎 Barra de búsqueda -->
          <form class="search-bar" method="get">
            <input type="text" name="search" placeholder="Buscar por nombre, tipo o estudiante...">
            <button type="submit" class="btn primary">Buscar</button>
            <a href="list.php" class="btn secondary">Restablecer</a>
          </form>

          <!-- 📋 Tabla de documentos -->
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Documento</th>
                <th>Tipo</th>
                <th>Asignado a</th>
                <th>Periodo</th>
                <th>Estado</th>
                <th>Fecha de Subida</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>#D-001</td>
                <td>Solicitud de Servicio Social</td>
                <td>Global</td>
                <td>-</td>
                <td>Enero - Junio 2025</td>
                <td><span class="badge activo">Disponible</span></td>
                <td>2025-01-02</td>
                <td class="actions">
                  <a href="view.php?id=1" class="btn small">👁️ Ver</a>
                  <a href="edit.php?id=1" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=1" class="btn small danger">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>#D-045</td>
                <td>Reporte Parcial 1</td>
                <td>Estudiante</td>
                <td>Laura Méndez</td>
                <td>Enero - Junio 2025</td>
                <td><span class="badge pendiente">Pendiente</span></td>
                <td>2025-02-15</td>
                <td class="actions">
                  <a href="view.php?id=45" class="btn small">👁️ Ver</a>
                  <a href="edit.php?id=45" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=45" class="btn small danger">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>#D-050</td>
                <td>Constancia Final</td>
                <td>Estudiante</td>
                <td>Juan Pérez</td>
                <td>Agosto - Diciembre 2024</td>
                <td><span class="badge finalizado">Entregado</span></td>
                <td>2024-12-20</td>
                <td class="actions">
                  <a href="view.php?id=50" class="btn small">👁️ Ver</a>
                  <a href="edit.php?id=50" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=50" class="btn small danger">🗑️ Eliminar</a>
                </td>
              </tr>
            </tbody>
          </table>

        </div>
      </section>
    </main>
  </div>
</body>
</html>
