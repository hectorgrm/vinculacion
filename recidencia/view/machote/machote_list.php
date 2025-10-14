<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Machotes · Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/machote/machote_list.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>📑 Machotes de Convenio</h2>
        <p class="subtitle">Administra las versiones de machotes institucionales y revisiones activas con empresas.</p>
        <div class="actions">
          <a href="machote_add.php" class="btn primary">➕ Nuevo Machote</a>
        </div>
      </header>

      <!-- Filtros de búsqueda -->
      <section class="card">
        <form class="search-bar" method="get" style="display:flex; gap:10px; margin-bottom:15px;">
          <input type="text" name="search" placeholder="Buscar por empresa o versión..." />
          <button type="submit" class="btn primary">🔍 Buscar</button>
          <a href="list.php" class="btn secondary">Limpiar</a>
        </form>

        <!-- Tabla -->
        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Empresa</th>
                <th>Versión Machote</th>
                <th>Fecha</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo de registros -->
              <tr>
                <td>1</td>
                <td>Casa del Barrio</td>
                <td>Institucional v1.2</td>
                <td>2025-10-08</td>
                <td><span class="badge en_revision">En revisión</span></td>
                <td class="actions">
                  <a href="Machote_revisar.php?id=1" class="btn small primary">💬 Revisar</a>
                  <a href="machote_edit.php?id=1" class="btn small">✏️ Editar</a>
                  <a href="machote_view.php?id=1" class="btn small">✏️ view</a>
                  <a href="machote_delete.php?id=1" class="btn small danger" onclick="return confirm('¿Eliminar este machote?')">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>2</td>
                <td>Tequila ECT</td>
                <td>Institucional v1.1</td>
                <td>2025-09-25</td>
                <td><span class="badge aprobado">Aprobado</span></td>
                <td class="actions">
                  <a href="revisar.php?id=2" class="btn small primary">💬 Revisar</a>
                </td>
              </tr>
              <tr>
                <td>3</td>
                <td>Industrias Yakumo</td>
                <td>Institucional v1.0</td>
                <td>2025-09-10</td>
                <td><span class="badge cancelado">Cancelado</span></td>
                <td class="actions">
                  <a href="edit.php?id=3" class="btn small">✏️ Editar</a>
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
