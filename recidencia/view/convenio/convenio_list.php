<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Residencia Profesional - Convenios</title>
  <link rel="stylesheet" href="../../assets/css/convenios/convenioglobalstyles.css" />
</head>
<body>

<header>
  <h1>Gestión de Convenios - Residencia Profesional</h1>
  <nav class="breadcrumb">
    <a href="../dashboard.php">Inicio</a> <span>/</span>
    <span>Convenios</span>
  </nav>
</header>

<main>

  <section class="card">
    <h2>Listado de Convenios</h2>

    <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
      <form method="GET" class="search-bar" style="display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Buscar por empresa o estatus..." />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>

      <a href="convenio_add.php" class="btn btn-primary">+ Nuevo Convenio</a>
    </div>

    <div class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Empresa</th>
            <th>Estatus</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Versión</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- 📌 Ejemplo de datos estáticos (se reemplazarán con PHP dinámico después) -->
          <tr>
            <td>1</td>
            <td>Casa del Barrio</td>
            <td><span class="status vigente">Vigente</span></td>
            <td>2025-07-01</td>
            <td>2026-06-30</td>
            <td>v1.2</td>
            <td>
              <a href="convenio_view.php?id=1" class="btn btn-secondary">Ver</a>
              <a href="convenio_edit.php?id=1" class="btn btn-primary">Editar</a>
              <a href="convenio_delete.php?id=1" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este convenio?')">Eliminar</a>
            </td>
          </tr>

          <tr>
            <td>2</td>
            <td>Tequila ECT</td>
            <td><span class="status en_revision">En revisión</span></td>
            <td>2025-08-15</td>
            <td>-</td>
            <td>v1.0</td>
            <td>
              <a href="convenio_view.php?id=2" class="btn btn-secondary">Ver</a>
              <a href="convenio_edit.php?id=2" class="btn btn-primary">Editar</a>
              <a href="convenio_delete.php?id=2" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este convenio?')">Eliminar</a>
            </td>
          </tr>

          <tr>
            <td>3</td>
            <td>Industrias Yakumo</td>
            <td><span class="status pendiente">Pendiente</span></td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>
              <a href="convenio_view.php?id=3" class="btn btn-secondary">Ver</a>
              <a href="convenio_edit.php?id=3" class="btn btn-primary">Editar</a>
              <a href="convenio_delete.php?id=3" class="btn btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este convenio?')">Eliminar</a>
            </td>
          </tr>
          <!-- 🔁 Aquí se generarán dinámicamente más filas -->
        </tbody>
      </table>
    </div>
  </section>

</main>

</body>
</html>
