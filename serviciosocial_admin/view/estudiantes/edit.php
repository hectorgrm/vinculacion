<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>✏️ Editar Estudiante · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/estudiante/estudianteedit.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>✏️ Editar Información del Estudiante</h2>
        <a href="view.php?id=2030456" class="btn secondary">⬅ Volver al perfil</a>
      </header>

      <section class="card">
        <header>📄 Datos Personales</header>
        <div class="content">
          <form>
            <!-- 🧑‍🎓 Información básica -->
            <div>
              <label for="nombre">Nombre completo</label>
              <input type="text" id="nombre" placeholder="Ej: Ana Rodríguez">
            </div>

            <div>
              <label for="matricula">Matrícula</label>
              <input type="text" id="matricula" placeholder="Ej: A012345">
            </div>

            <div>
              <label for="carrera">Carrera</label>
              <input type="text" id="carrera" placeholder="Ej: Ingeniería en Informática">
            </div>

            <div>
              <label for="semestre">Semestre</label>
              <input type="number" id="semestre" placeholder="Ej: 8">
            </div>

            <div>
              <label for="email">Correo electrónico</label>
              <input type="email" id="email" placeholder="Ej: ana.rodriguez@alumno.edu.mx">
            </div>

            <div>
              <label for="telefono">Teléfono</label>
              <input type="tel" id="telefono" placeholder="Ej: 3310000001">
            </div>

            <!-- 📝 Observaciones -->
            <div class="full">
              <label for="observaciones">Observaciones</label>
              <textarea id="observaciones" rows="4" placeholder="Notas o comentarios generales sobre el estudiante"></textarea>
            </div>

            <!-- 💾 Acciones -->
            <div class="actions">
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
              <a href="view.php?id=2030456" class="btn secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
