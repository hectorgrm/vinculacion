<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Servicios · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Gestión de Servicios · Servicio Social</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <span>Gestión de Servicios</span>
    </nav>
  </header>

  <main>
    <a href="../../index.php" class="btn btn-secondary">⬅ Regresar</a>

    <!-- 🔍 Barra de búsqueda -->
    <div class="search-bar">
      <form method="get" action="">
        <input type="text" name="q" placeholder="Buscar por nombre, matrícula o plaza..." />
        <button type="submit" class="btn btn-primary">Buscar</button>
      </form>
    </div>

    <!-- ✅ Acciones superiores -->
    <div class="top-actions">
      <h2>Servicios registrados</h2>
      <a href="servicio_add.php" class="btn btn-primary">+ Nuevo Servicio</a>
    </div>

    <!-- 📋 Tabla de servicios -->
    <table class="styled-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Estudiante</th>
          <th>Matrícula</th>
          <th>Plaza</th>
          <th>Estado</th>
          <th>Horas</th>
          <th>Creado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Juan Pérez</td>
          <td>20214567</td>
          <td>Desarrollo Web</td>
          <td><span class="status activo">Activo</span></td>
          <td>320</td>
          <td>2025-02-01</td>
          <td class="actions">
            <a href="servicio_view.php?id=1" class="btn btn-info">Ver</a>
            <a href="servicio_edit.php?id=1" class="btn btn-warning">Editar</a>
            <a href="servicio_close.php?id=1" class="btn btn-danger">Cerrar</a>
          </td>
        </tr>

        <tr>
          <td>2</td>
          <td>María López</td>
          <td>20214568</td>
          <td>Soporte Técnico</td>
          <td><span class="status prealta">Pre-alta</span></td>
          <td>0</td>
          <td>2025-03-12</td>
          <td class="actions">
            <a href="servicio_view.php?id=2" class="btn btn-info">Ver</a>
            <a href="servicio_edit.php?id=2" class="btn btn-warning">Editar</a>
            <a href="servicio_close.php?id=2" class="btn btn-danger">Cerrar</a>
          </td>
        </tr>

        <tr>
          <td>3</td>
          <td>Pedro Ramírez</td>
          <td>20214569</td>
          <td>Investigación de datos</td>
          <td><span class="status concluido">Concluido</span></td>
          <td>480</td>
          <td>2025-01-20</td>
          <td class="actions">
            <a href="servicio_view.php?id=3" class="btn btn-info">Ver</a>
            <a href="servicio_edit.php?id=3" class="btn btn-warning">Editar</a>
            <a href="servicio_close.php?id=3" class="btn btn-danger">Cerrar</a>
          </td>
        </tr>
      </tbody>
    </table>
  </main>

</body>
</html>
