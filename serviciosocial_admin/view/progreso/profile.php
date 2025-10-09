<?php require_once __DIR__ . '/../../common/auth.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil del Estudiante · Servicio Social</title>
   <link rel="stylesheet" href="../../assets/css/estudiante/estudianteprofile.css">
   <link rel="stylesheet" href="../../assets/css/dashboard.css">
   
  </head>
<body>
  <div class="app">

      <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <h2>Perfil de Estudiante - María López</h2>
        <a href="list.php" class="btn secondary">⬅ Volver al listado</a>
        <a href="progreso.php?id=2030456" class="btn primary">📊 Ver Progreso</a>

      </header>

      <!-- 🧑‍🎓 Datos Personales -->
      <section class="card">
        <header>Información Personal</header>
        <div class="content info-grid">
          <div class="info-item"><label>Nombre</label><p>María López</p></div>
          <div class="info-item"><label>Matrícula</label><p>2030456</p></div>
          <div class="info-item"><label>Carrera</label><p>Ingeniería en Informática</p></div>
          <div class="info-item"><label>Semestre</label><p>8°</p></div>
          <div class="info-item"><label>Correo</label><p>maria.lopez@correo.com</p></div>
          <div class="info-item"><label>Teléfono</label><p>3312345678</p></div>
          <div class="info-item"><label>Estado del servicio</label><p><span class="status pendiente">Pendiente</span></p></div>
        </div>
      </section>

      <!-- 🏢 Asignación de Servicio -->
      <section class="card">
        <header>Asignación de Servicio</header>
        <div class="content">
          <p><strong>Empresa:</strong> No asignada</p>
          <p><strong>Convenio:</strong> - </p>
          <p><strong>Plaza:</strong> - </p>
          <p><strong>Fechas:</strong> - </p>
          <a href="../asignaciones/add.php?id=2030456" class="btn primary">+ Asignar Empresa y Plaza</a>
        </div>
      </section>

      <!-- 📆 Periodos -->
      <section class="card">
        <header>Periodos</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Fecha de inicio</th>
                <th>Fecha de cierre</th>
                <th>Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>-</td>
                <td>-</td>
                <td><span class="status pendiente">No iniciado</span></td>
              </tr>
            </tbody>
          </table>
          <a href="../periodos/add.php?id=2030456" class="btn primary" style="margin-top:15px;">+ Crear nuevo periodo</a>
        </div>
      </section>

      <!-- 📁 Documentos -->
      <section class="card">
        <header>Documentos</header>
        <div class="content">
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Última actualización</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Carta de presentación</td>
                <td><span class="status pendiente">Pendiente</span></td>
                <td>-</td>
              </tr>
              <tr>
                <td>Plan de trabajo</td>
                <td><span class="status pendiente">Pendiente</span></td>
                <td>-</td>
              </tr>
            </tbody>
          </table>
          <a href="../documentos/asignar.php" class="btn primary">📄 Asignar Documentos</a>
        </div>
      </section>

  


    </main>
  </div>
</body>
</html>
