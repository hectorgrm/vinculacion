<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Comentarios de Machote - Residencia Profesional</title>
  <link rel="stylesheet" href="../../assets/css/machote/machoteglobalstyles.css" />
</head>
<body>

  <!-- HEADER -->
  <header>
    <h1>Gestión de Comentarios del Machote</h1>
    <nav class="breadcrumb">
      <a href="../dashboard.php">Inicio</a> <span>›</span>
      <a href="../convenios/convenio_list.php">Convenios</a> <span>›</span>
      <span>Comentarios Machote</span>
    </nav>
  </header>

  <main>
    <div class="card">
      <h2>Listado de Comentarios</h2>
      <p>En esta sección puedes revisar, filtrar, editar o eliminar los comentarios realizados sobre las cláusulas de los convenios.</p>

      <!-- 🔍 FILTROS Y BÚSQUEDA -->
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
        <form class="search-form" style="display: flex; flex-wrap: wrap; gap: 10px;">
          <input type="text" placeholder="Buscar por cláusula, comentario o usuario..." />

          <select name="convenio_filtro">
            <option value="">-- Filtrar por convenio --</option>
            <option value="1">Convenio #1 - v1.2</option>
            <option value="2">Convenio #2 - v1.0</option>
            <option value="3">Convenio #3 - pendiente</option>
          </select>

          <select name="estatus_filtro">
            <option value="">-- Filtrar por estatus --</option>
            <option value="pendiente">Pendiente</option>
            <option value="resuelto">Resuelto</option>
          </select>

          <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>

        <a href="machote_add.php" class="btn btn-success">+ Nuevo Comentario</a>
      </div>

      <!-- 📋 TABLA DE COMENTARIOS -->
      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Convenio</th>
              <th>Cláusula</th>
              <th>Comentario</th>
              <th>Usuario</th>
              <th>Estatus</th>
              <th>Fecha de creación</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- 🔁 Ejemplos estáticos (se reemplazarán con datos dinámicos del backend) -->
            <tr>
              <td>4</td>
              <td>#1 - v1.2</td>
              <td>Cláusula 3</td>
              <td><div class="comment-box">Se solicita aclarar las obligaciones de la empresa.</div></td>
              <td>Admin Residencia</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>2025-09-19</td>
              <td>
                <a href="machote_edit.php?id=4" class="btn btn-primary">Editar</a>
                <a href="machote_delete.php?id=4" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>5</td>
              <td>#1 - v1.2</td>
              <td>Cláusula 5</td>
              <td><div class="comment-box">Modificar la vigencia a 18 meses en lugar de 12.</div></td>
              <td>Admin Residencia</td>
              <td><span class="status resuelto">Resuelto</span></td>
              <td>2025-09-19</td>
              <td>
                <a href="machote_edit.php?id=5" class="btn btn-primary">Editar</a>
                <a href="machote_delete.php?id=5" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>6</td>
              <td>#2 - v1.0</td>
              <td>Cláusula 2</td>
              <td><div class="comment-box">Se requiere ajustar la redacción sobre confidencialidad.</div></td>
              <td>Editor Jurídico</td>
              <td><span class="status pendiente">Pendiente</span></td>
              <td>2025-09-19</td>
              <td>
                <a href="machote_edit.php?id=6" class="btn btn-primary">Editar</a>
                <a href="machote_delete.php?id=6" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>

            <tr>
              <td>7</td>
              <td>#2 - v1.0</td>
              <td>Cláusula 7</td>
              <td><div class="comment-box">Eliminar apartado de penalizaciones por retraso.</div></td>
              <td>Admin Residencia</td>
              <td><span class="status resuelto">Resuelto</span></td>
              <td>2025-09-19</td>
              <td>
                <a href="machote_edit.php?id=7" class="btn btn-primary">Editar</a>
                <a href="machote_delete.php?id=7" class="btn btn-danger">Eliminar</a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
