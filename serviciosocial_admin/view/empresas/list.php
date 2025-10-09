<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Empresas · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/empresa/empresalist.css">
   <link rel="stylesheet" href="../../assets/css/dashboard.css"> 

</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Gestión de Empresas</h2>
        <button class="btn primary" onclick="window.location.href='add.php'">+ Nueva Empresa</button>
      </header>

      <section class="card">
        <header>Listado de Empresas</header>
        <div class="content">
          <!-- 🔎 Barra de búsqueda -->
          <form class="search-bar" method="get">
            <input type="text" name="search" placeholder="Buscar por nombre o estado...">
            <button type="submit" class="btn primary">Buscar</button>
            <a href="list.php" class="btn secondary">Restablecer</a>
          </form>

          <!-- 📊 Tabla -->
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>RFC</th>
                <th>Contacto</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <!-- Ejemplo de fila (luego dinámico con PHP) -->
              <tr>
                <td>Casa del Barrio</td>
                <td>CDB810101AA1</td>
                <td>José Manuel Velador</td>
                <td>contacto@casadelbarrio.mx</td>
                <td>(33) 1234 5678</td>
                <td><span class="badge activo">Activo</span></td>
                <td class="actions">
                  <a href="view.php?id=1" class="btn small">👁️ Ver</a>
                  <a href="edit.php?id=1" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=1" class="btn small danger">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>Tequila ECT</td>
                <td>TEC920202BB2</td>
                <td>María González</td>
                <td>legal@tequilaect.com</td>
                <td>(33) 2345 6789</td>
                <td><span class="badge activo">Activo</span></td>
                <td class="actions">
                  <a href="view.php?id=2" class="btn small">👁️Ver</a>
                  <a href="edit.php?id=2" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=2" class="btn small danger">🗑️ Eliminar</a>
                </td>
              </tr>
              <tr>
                <td>Industrias Yakumo</td>
                <td>IYA930303CC3</td>
                <td>Luis Pérez</td>
                <td>vinculacion@yakumo.com</td>
                <td>(55) 3456 7890</td>
                <td><span class="badge inactivo">Inactivo</span></td>
                <td class="actions">
                  <a href="view.php?id=3" class="btn small">👁️ Ver</a>
                  <a href="edit.php?id=3" class="btn small">✏️ Editar</a>
                  <a href="delete.php?id=3" class="btn small danger">🗑️ Eliminar</a>
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
