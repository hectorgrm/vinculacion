<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodos/periodosadd.css">
  <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Registrar Nuevo Periodo</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📅 Información del Nuevo Periodo</header>
        <div class="content">
          <form>
            <div class="full">
              <label for="nombre">Nombre del Periodo</label>
              <input type="text" id="nombre" placeholder="Ej: Servicio Social Enero - Junio 2026">
            </div>

            <div>
              <label for="fecha_inicio">Fecha de Inicio</label>
              <input type="date" id="fecha_inicio">
            </div>

            <div>
              <label for="fecha_fin">Fecha de Finalización</label>
              <input type="date" id="fecha_fin">
            </div>

            <div>
              <label for="estado">Estado</label>
              <select id="estado">
                <option value="pendiente">Pendiente</option>
                <option value="activo">Activo</option>
                <option value="finalizado">Finalizado</option>
              </select>
            </div>

            <div>
              <label for="capacidad">Capacidad Máxima de Estudiantes</label>
              <input type="number" id="capacidad" placeholder="Ej: 150">
            </div>

            <div class="full">
              <label for="descripcion">Descripción del Periodo</label>
              <textarea id="descripcion" rows="4"
                placeholder="Ej: Periodo de servicio social correspondiente al primer semestre del ciclo escolar 2026."></textarea>
            </div>

            <div class="actions">
              <button type="submit" class="btn primary">💾 Guardar Periodo</button>
              <a href="list.php" class="btn secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>