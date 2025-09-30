<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header>
    <h1>Detalle del Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Detalle</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <!-- 🧑‍🎓 Información del estudiante -->
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
        <div class="field">
          <label>Correo electrónico</label>
          <p>juan.perez@tectijuana.edu.mx</p>
        </div>
        <div class="field">
          <label>Teléfono</label>
          <p>55 1234 5678</p>
        </div>
      </div>
    </section>

    <!-- 🏢 Información de la plaza -->
    <section class="card">
      <h2>Plaza Asignada</h2>
      <div class="grid cols-2">
        <div class="field">
          <label>Nombre de la plaza</label>
          <p>Auxiliar de Soporte TI</p>
        </div>
        <div class="field">
          <label>Dependencia / Empresa</label>
          <p>Secretaría de Innovación</p>
        </div>
        <div class="field">
          <label>Modalidad</label>
          <p>Presencial</p>
        </div>
        <div class="field">
          <label>Cupo</label>
          <p>3</p>
        </div>
        <div class="field">
          <label>Periodo de plaza</label>
          <p>01/02/2025 – 30/07/2025</p>
        </div>
      </div>
    </section>

    <!-- 📅 Información del servicio -->
    <section class="card">
      <h2>Detalles del Servicio</h2>
      <div class="grid cols-2">
        <div class="field">
          <label>ID del Servicio</label>
          <p>12</p>
        </div>
        <div class="field">
          <label>Estado actual</label>
          <p><span class="status activo">Activo</span></p>
        </div>
        <div class="field">
          <label>Horas acumuladas</label>
          <p>320 / 480 horas</p>
        </div>
        <div class="field">
          <label>Fecha de creación</label>
          <p>01/02/2025</p>
        </div>
      </div>
    </section>

    <!-- 🧾 Periodos asociados -->
    <section class="card">
      <h2>Periodos del Servicio</h2>
      <table class="styled-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Estatus</th>
            <th>Abierto en</th>
            <th>Cerrado en</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><span class="status completado">Completado</span></td>
            <td>01/02/2025</td>
            <td>30/03/2025</td>
          </tr>
          <tr>
            <td>2</td>
            <td><span class="status en_revision">En revisión</span></td>
            <td>01/04/2025</td>
            <td>-</td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- 📌 Observaciones -->
    <section class="card">
      <h2>Observaciones</h2>
      <p>
        El estudiante ha mostrado excelente desempeño durante el primer periodo.  
        Se recomienda asignar tareas más complejas en el siguiente módulo.
      </p>
    </section>

    <!-- 🔧 Acciones -->
    <div class="actions">
      <a href="servicio_edit.php?id=12" class="btn btn-warning">Editar</a>
      <a href="servicio_close.php?id=12" class="btn btn-danger">Finalizar Servicio</a>
    </div>

  </main>

</body>
</html>
