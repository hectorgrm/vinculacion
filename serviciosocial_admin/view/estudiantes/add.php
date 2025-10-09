
<?php require_once __DIR__ . '/../../common/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Estudiante · Servicio Social</title>
 <link rel="stylesheet" href="../../assets/css/dashboard.css"> 
 <link rel="stylesheet" href="../../assets/css/estudiante/estudianteadd.css"> 
</head>
<body>
  <div class="app">
     <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>


    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Registrar Nuevo Estudiante</h2>
        <a href="list.php" class="btn secondary">⬅ Volver al listado</a>
      </header>

      <section class="card">
        <header>Formulario de Registro</header>
        <div class="content">
          <form action="../../controller/EstudianteAddController.php" method="POST">
            <div>
              <label for="nombre">Nombre completo</label>
              <input type="text" id="nombre" name="nombre" placeholder="Ej. Juan Pérez" required />
            </div>
            <div>
              <label for="matricula">Matrícula</label>
              <input type="text" id="matricula" name="matricula" placeholder="Ej. 2049821" required />
            </div>
            <div>
              <label for="carrera">Carrera</label>
              <select id="carrera" name="carrera" required>
                <option value="">Seleccione una opción</option>
                <option value="Informática">Ingeniería en Informática</option>
                <option value="Sistemas">Ingeniería en Sistemas</option>
                <option value="Industrial">Ingeniería Industrial</option>
              </select>
            </div>
            <div>
              <label for="semestre">Semestre</label>
              <input type="number" id="semestre" name="semestre" min="1" max="12" placeholder="Ej. 8" />
            </div>
            <div>
              <label for="email">Correo electrónico</label>
              <input type="email" id="email" name="email" placeholder="ejemplo@correo.com" required />
            </div>
            <div>
              <label for="telefono">Teléfono</label>
              <input type="text" id="telefono" name="telefono" placeholder="Ej. 3312345678" />
            </div>
            <div class="full">
              <label for="direccion">Dirección</label>
              <input type="text" id="direccion" name="direccion" placeholder="Calle, número, colonia, ciudad" />
            </div>

            <div class="actions full">
              <button type="submit" class="btn primary">💾 Registrar Estudiante</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
