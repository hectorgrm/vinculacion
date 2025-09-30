<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Editar Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card">
      <h2>Datos del Estudiante</h2>
      <div class="grid cols-2">
        <div class="field">
          <label>Nombre completo</label>
          <p>Juan Carlos Pérez López</p>
        </div>
        <div class="field">
          <label>Matrícula</label>
          <p>20214567</p>
        </div>
        <div class="field">
          <label>Carrera</label>
          <p>Ingeniería en Informática</p>
        </div>
      </div>
    </section>

    <section class="card">
      <h2>Editar Detalles del Servicio</h2>

      <form action="" method="post" class="form">
        <div class="grid cols-2">

          <!-- 🏢 Plaza asignada -->
          <div class="field">
            <label for="plaza">Plaza asignada</label>
            <select id="plaza" name="plaza">
              <option value="">-- Seleccionar plaza --</option>
              <option value="1" selected>Auxiliar de Soporte TI</option>
              <option value="2">Desarrollo Web</option>
              <option value="3">Análisis de Datos</option>
            </select>
          </div>

          <!-- 🔄 Estado del servicio -->
          <div class="field">
            <label for="estatus">Estado del servicio</label>
            <select id="estatus" name="estatus">
              <option value="prealta">Pre-alta</option>
              <option value="activo" selected>Activo</option>
              <option value="concluido">Concluido</option>
              <option value="cancelado">Cancelado</option>
            </select>
          </div>

          <!-- 🕐 Horas acumuladas -->
          <div class="field">
            <label for="horas">Horas acumuladas</label>
            <input type="number" id="horas" name="horas" placeholder="Ej. 320" value="320" min="0" />
          </div>

          <!-- 📅 Fecha de inicio (solo informativa) -->
          <div class="field">
            <label>Fecha de creación</label>
            <p>01/02/2025</p>
          </div>
        </div>

        <!-- 📝 Observaciones -->
        <div class="field" style="grid-column: 1 / -1;">
          <label for="observaciones">Observaciones</label>
          <textarea id="observaciones" name="observaciones" placeholder="Notas adicionales sobre el servicio...">El estudiante ha tenido un excelente desempeño y podría participar en proyectos más avanzados.</textarea>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="servicio_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
