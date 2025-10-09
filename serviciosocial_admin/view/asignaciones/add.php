<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nueva Asignación · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/asignaciones/asignacionesadd.css">
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>Nueva Asignación</h2>
        <a href="list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <section class="card">
        <header>📋 Asignar Estudiante a Plaza</header>
        <div class="content">
          <form>
            <!-- 🧑‍🎓 Estudiante -->
            <div>
              <label for="estudiante">Estudiante</label>
              <select id="estudiante">
                <option disabled selected>Seleccione un estudiante</option>
                <option value="1">Laura Méndez – 2056764</option>
                <option value="2">Juan Pérez – 2049821</option>
                <option value="3">Ana Rodríguez – 2059982</option>
              </select>
            </div>

            <div>
              <label for="matricula">Matrícula</label>
              <input type="text" id="matricula" placeholder="Se llenará automáticamente" readonly>
            </div>

            <!-- 🏢 Empresa -->
            <div>
              <label for="empresa">Empresa</label>
              <select id="empresa">
                <option disabled selected>Seleccione la empresa</option>
                <option value="1">Casa del Barrio</option>
                <option value="2">Tequila ECT</option>
                <option value="3">Industrias Yakumo</option>
              </select>
            </div>

            <!-- 📜 Convenio -->
            <div>
              <label for="convenio">Convenio</label>
              <select id="convenio">
                <option disabled selected>Seleccione el convenio</option>
                <option value="1">Convenio Marco v1.0</option>
                <option value="2">Convenio Específico v2.0</option>
              </select>
            </div>

            <!-- 💼 Plaza -->
            <div>
              <label for="plaza">Plaza</label>
              <select id="plaza">
                <option disabled selected>Seleccione la plaza</option>
                <option value="1">Desarrollador Web</option>
                <option value="2">Soporte Técnico</option>
                <option value="3">Analista de Datos</option>
              </select>
            </div>

            <div>
              <label for="estado">Estado de Asignación</label>
              <select id="estado">
                <option value="pendiente">Pendiente</option>
                <option value="en_curso" selected>En curso</option>
                <option value="concluido">Concluido</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>

            <div>
              <label for="fecha_asignacion">Fecha de Asignación</label>
              <input type="date" id="fecha_asignacion" value="2025-01-20">
            </div>

            <div class="full">
              <label for="observaciones">Observaciones</label>
              <textarea id="observaciones" rows="4" placeholder="Notas adicionales o comentarios sobre la asignación"></textarea>
            </div>

            <div class="actions">
              <button type="submit" class="btn primary">💾 Guardar Asignación</button>
              <a href="list.php" class="btn secondary">Cancelar</a>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
