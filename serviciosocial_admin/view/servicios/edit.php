<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicios/serviciosedit.css">
      <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>✏️ Editar Servicio Social</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📋 Actualizar Información del Servicio</header>
        <div class="content">
          <form>

            <div>
              <label for="estudiante">Estudiante</label>
              <select id="estudiante">
                <option value="">Seleccionar estudiante</option>
                <option value="1" selected>Laura Méndez - 2056764</option>
                <option value="2">Juan Pérez - 2049821</option>
                <option value="3">María López - 2030456</option>
              </select>
            </div>

            <div>
              <label for="empresa">Empresa / Dependencia</label>
              <select id="empresa">
                <option value="">Seleccionar empresa</option>
                <option value="1" selected>Secretaría de Innovación</option>
                <option value="2">Municipio de Guadalajara</option>
              </select>
            </div>

            <div class="full">
              <label for="proyecto">Proyecto Asignado</label>
              <input type="text" id="proyecto" value="Sistema de Gestión Documental">
            </div>

            <div>
              <label for="fecha_inicio">Fecha de Inicio</label>
              <input type="date" id="fecha_inicio" value="2025-01-15">
            </div>

            <div>
              <label for="fecha_fin">Fecha de Término</label>
              <input type="date" id="fecha_fin" value="2025-06-15">
            </div>

            <div>
              <label for="horas_totales">Horas Totales Requeridas</label>
              <input type="number" id="horas_totales" value="480">
            </div>

            <div>
              <label for="horas_acumuladas">Horas Acumuladas</label>
              <input type="number" id="horas_acumuladas" value="320">
            </div>

            <div>
              <label for="periodo">Periodo</label>
              <select id="periodo">
                <option value="">Seleccionar periodo</option>
                <option value="2025-01" selected>Enero - Junio 2025</option>
                <option value="2025-02">Agosto - Diciembre 2025</option>
              </select>
            </div>

            <div>
              <label for="estado">Estado del Servicio</label>
              <select id="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en_curso" selected>En curso</option>
                <option value="concluido">Concluido</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>

            <div class="full">
              <label for="observaciones">Observaciones</label>
              <textarea id="observaciones" rows="4">El proyecto avanza según lo programado. El estudiante ha completado el 70% de las actividades.</textarea>
            </div>

            <div class="actions">
              <button type="submit" class="btn primary">💾 Guardar Cambios</button>
              <a href="list.php" class="btn secondary">Cancelar</a>
            </div>

          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
