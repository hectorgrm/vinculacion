<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Nuevo Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Registrar Nuevo Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Registrar</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card">
      <h2>Registrar un nuevo servicio</h2>
      <p>Completa los siguientes campos para dar de alta un nuevo servicio social para un estudiante.</p>

      <form action="" method="post" class="form">
        <div class="grid cols-2">

          <!-- 🧑‍🎓 Seleccionar estudiante -->
          <div class="field">
            <label for="estudiante" class="required">Estudiante</label>
            <select id="estudiante" name="estudiante" required>
              <option value="">-- Seleccionar estudiante --</option>
              <option value="1">Juan Carlos Pérez López</option>
              <option value="2">María Fernanda López</option>
              <option value="3">Pedro Hernández</option>
            </select>
            <div class="hint">Selecciona el estudiante registrado en el sistema.</div>
          </div>

          <!-- 🏢 Plaza asignada -->
          <div class="field">
            <label for="plaza">Plaza asignada</label>
            <select id="plaza" name="plaza">
              <option value="">-- Seleccionar plaza --</option>
              <option value="1">Auxiliar de Soporte TI</option>
              <option value="2">Análisis de Datos</option>
              <option value="3">Desarrollo Web</option>
            </select>
            <div class="hint">Puedes dejarlo vacío si aún no se ha asignado una plaza.</div>
          </div>

          <!-- 🔄 Estado inicial -->
          <div class="field">
            <label for="estatus" class="required">Estado inicial</label>
            <select id="estatus" name="estatus" required>
              <option value="prealta">Pre-alta</option>
              <option value="activo">Activo</option>
            </select>
            <div class="hint">El estado inicial suele ser "Pre-alta" si aún no ha comenzado.</div>
          </div>

          <!-- 🕐 Horas acumuladas -->
          <div class="field">
            <label for="horas">Horas acumuladas</label>
            <input type="number" id="horas" name="horas" placeholder="Ej. 0" value="0" min="0" />
            <div class="hint">Puedes dejarlo en 0 si apenas se registrará.</div>
          </div>

          <!-- 📅 Fecha de creación (opcional o automática) -->
          <div class="field">
            <label for="fecha_creacion">Fecha de inicio</label>
            <input type="date" id="fecha_creacion" name="fecha_creacion" />
            <div class="hint">Puedes dejarlo vacío para asignar la fecha automáticamente.</div>
          </div>
        </div>

        <!-- 📝 Observaciones -->
        <div class="field" style="grid-column: 1 / -1;">
          <label for="observaciones">Observaciones</label>
          <textarea id="observaciones" name="observaciones" placeholder="Notas adicionales sobre el servicio..."></textarea>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="servicio_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">Registrar Servicio</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
