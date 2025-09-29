<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodo_edit.css" />
</head>
<body>

<header>
  <h1>Editar Periodo</h1>
  <nav class="breadcrumb">
    <a href="#">Inicio</a>
    <span>›</span>
    <a href="#">Gestión de Periodos</a>
    <span>›</span>
    <span>Editar</span>
  </nav>
</header>

<main>
  <div class="card">
    <header>
      <h2>Actualizar información del periodo</h2>
      <p>Modifica los datos necesarios y guarda los cambios.</p>
    </header>

    <form action="#" method="post" autocomplete="off">
      <!-- Campo oculto ID del periodo -->
      <input type="hidden" name="id" value="4">

      <div class="grid cols-2">
        <div class="field">
          <label for="servicio_id" class="required">ID del Servicio</label>
          <input type="text" id="servicio_id" name="servicio_id" value="12345" readonly>
          <div class="hint">Este campo no se puede editar.</div>
        </div>

        <div class="field">
          <label for="numero" class="required">Número de periodo</label>
          <input type="number" id="numero" name="numero" min="1" value="2" required>
        </div>

        <div class="field">
          <label for="estatus" class="required">Estatus actual</label>
          <select id="estatus" name="estatus" required>
            <option value="">Selecciona…</option>
            <option value="abierto" selected>Abierto</option>
            <option value="en_revision">En revisión</option>
            <option value="completado">Completado</option>
          </select>
        </div>

        <div class="field">
          <label for="abierto_en" class="required">Fecha de apertura</label>
          <input type="datetime-local" id="abierto_en" name="abierto_en" value="2025-02-01T09:00" required>
        </div>

        <div class="field">
          <label for="cerrado_en">Fecha de cierre</label>
          <input type="datetime-local" id="cerrado_en" name="cerrado_en" value="">
        </div>
      </div>

      <div class="actions">
        <a href="#" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Guardar cambios</button>
      </div>

    </form>
  </div>
</main>

</body>
</html>
