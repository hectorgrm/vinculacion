<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Convenios - Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/convenios/conveniostyles.css" />
</head>
<body>

  <header>
    <h1>Gestión de Convenios</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Convenios</span>
    </nav>
  </header>

  <main>
    <section class="card">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Listado de Convenios</h2>
        <a href="convenio_add.php" class="btn btn-primary">➕ Nuevo Convenio</a>
      </div>

      <!-- 🔍 Buscador -->
      <form action="" method="get" style="margin: 20px 0; display: flex; gap: 10px;">
        <input type="text" name="search" placeholder="Buscar por empresa o estado..." style="flex: 1;" />
        <button type="submit" class="btn btn-primary">Buscar</button>
        <a href="convenio_list.php" class="btn btn-secondary">Restablecer</a>
      </form>

      <!-- 📋 Tabla de convenios -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Empresa</th>
              <th>Estatus</th>
              <th>Fecha de Inicio</th>
              <th>Fecha de Fin</th>
              <th>Versión</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🔁 Ejemplos estáticos - aquí luego se renderizan con PHP -->
            <tr>
              <td>1</td>
              <td>Universidad Tecnológica del Centro</td>
              <td><span class="status vigente">Vigente</span></td>
              <td>2025-01-01</td>
              <td>2025-12-31</td>
              <td>v1</td>
              <td>
                <a href="convenio_view.php?id=1" class="btn btn-secondary">🔍 Ver</a>
                <a href="convenio_edit.php?id=1" class="btn btn-primary">✏️ Editar</a>
                <a href="convenio_delete.php?id=1" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>2</td>
              <td>Hospital General San José</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>2025-02-01</td>
              <td>2025-12-31</td>
              <td>v1</td>
              <td>
                <a href="convenio_view.php?id=2" class="btn btn-secondary">🔍 Ver</a>
                <a href="convenio_edit.php?id=2" class="btn btn-primary">✏️ Editar</a>
                <a href="convenio_delete.php?id=2" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>3</td>
              <td>Biblioteca Municipal Central</td>
              <td><span class="status vencido">Vencido</span></td>
              <td>2025-03-01</td>
              <td>2025-08-30</td>
              <td>v0.9</td>
              <td>
                <a href="convenio_view.php?id=3" class="btn btn-secondary">🔍 Ver</a>
                <a href="convenio_edit.php?id=3" class="btn btn-primary">✏️ Editar</a>
                <a href="convenio_delete.php?id=3" class="btn btn-danger">🗑️ Eliminar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </main>

</body>
</html>
