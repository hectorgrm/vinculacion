<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cerrar / Finalizar Servicio · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/servicio/globalservicio.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Cerrar o Cancelar Servicio</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="servicio_list.php">Gestión de Servicios</a>
      <span>›</span>
      <span>Cerrar</span>
    </nav>
  </header>

  <main>
    <a href="servicio_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="card form-card--danger">
      <h2>Confirmar cierre del servicio</h2>
      <p>
        Estás a punto de <strong>cerrar o cancelar</strong> el siguiente servicio.  
        Esta acción es <strong>irreversible</strong> y cambiará su estado permanentemente.
      </p>

      <!-- 🧾 Información resumida del servicio -->
      <div class="grid cols-2 resumen-servicio">
        <div class="field">
          <label>ID del Servicio</label>
          <p>12</p>
        </div>
        <div class="field">
          <label>Estudiante</label>
          <p>Juan Carlos Pérez López</p>
        </div>
        <div class="field">
          <label>Matrícula</label>
          <p>20214567</p>
        </div>
        <div class="field">
          <label>Plaza asignada</label>
          <p>Auxiliar de Soporte TI</p>
        </div>
        <div class="field">
          <label>Estado actual</label>
          <p><span class="status activo">Activo</span></p>
        </div>
        <div class="field">
          <label>Horas acumuladas</label>
          <p>480 / 480</p>
        </div>
      </div>

      <!-- ⚠️ Formulario de cierre -->
      <form action="" method="post" class="form">
        <div class="field">
          <label for="nuevo_estado" class="required">Nuevo estado</label>
          <select id="nuevo_estado" name="nuevo_estado" required>
            <option value="">-- Seleccionar --</option>
            <option value="concluido">✅ Concluido (Servicio finalizado correctamente)</option>
            <option value="cancelado">❌ Cancelado (Servicio terminado sin concluir)</option>
          </select>
        </div>

        <div class="field">
          <label for="motivo">Motivo del cierre / comentarios</label>
          <textarea id="motivo" name="motivo" placeholder="Escribe una explicación breve del motivo..."></textarea>
        </div>

        <!-- 📅 Fecha de cierre -->
        <div class="field">
          <label for="fecha_cierre">Fecha de cierre</label>
          <input type="date" id="fecha_cierre" name="fecha_cierre" />
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="servicio_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">Cerrar Servicio</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
