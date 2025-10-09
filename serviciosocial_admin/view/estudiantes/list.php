<?php require_once __DIR__ . '/../../common/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Estudiantes · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Gestión de Estudiantes</h2>
        <button class="btn primary" onclick="window.location.href='add.php'">+ Nuevo Estudiante</button>
      </header>

      <section class="card">
        <header>Listado de Estudiantes</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Matrícula</th>
                <th>Carrera</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>María López</td>
                <td>2030456</td>
                <td>Ingeniería en Informática</td>
                <td><span class="status pendiente">Pendiente</span></td>
                <td class="actions">
                  <button class="btn small" onclick="window.location.href='view.php'">👁️ Ver</button>
                  <button class="btn small" onclick="window.location.href='edit.php'">✏️ Editar</button>
                  <button class="btn small danger" onclick="window.location.href='delete.php'">🗑️ Eliminar</button>
                </td>
              </tr>
              <tr>
                <td>Juan Pérez</td>
                <td>2049821</td>
                <td>Ingeniería en Sistemas</td>
                <td><span class="status ok">Activo</span></td>
                <td class="actions">
                  <button class="btn small">👁️ Ver</button>
                  <button class="btn small">✏️ Editar</button>
                  <button class="btn small danger">🗑️ Eliminar</button>
                </td>
              </tr>
              <tr>
                <td>Laura Méndez</td>
                <td>2056764</td>
                <td>Ingeniería en Informática</td>
                <td><span class="status ok">Activo</span></td>
                <td class="actions">
                  <button class="btn small">👁️ Ver</button>
                  <button class="btn small">✏️ Editar</button>
                  <button class="btn small danger">🗑️ Eliminar</button>
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
