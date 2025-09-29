<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registrar Nuevo Periodo · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/periodo/periodoadd.css" />
</head>
<body>

<header>
  <h1>Registrar Nuevo Periodo</h1>
  <nav class="breadcrumb">
    <a href="#">Inicio</a>
    <span>›</span>
    <a href="#">Gestión de Periodos</a>
    <span>›</span>
    <span>Nuevo</span>
  </nav>
</header>

<main>
  <div class="card">
    <header>
      <h2>Información del periodo</h2>
      <p>Completa la información necesaria para crear un nuevo periodo del servicio social.</p>
    </header>

    <form action="#" method="post" autocomplete="off">

      <div class="grid cols-2">
        <div class="field">
          <label for="servicio_id" class="required">ID del Servicio</label>
          <input type="text" id="servicio_id" name="servicio_id" placeholder="Ej. 12345" required>
          <div class="hint">Identificador del servicio al que pertenece este periodo.</div>
        </div>

        <div class="field">
          <label for="numero" class="required">Número de periodo</label>
          <input type="number" id="numero" name="numero" placeholder="Ej. 1" min="1" required>
          <div class="hint">Número secuencial del periodo (ej. 1, 2, 3...).</div>
        </div>

        <div class="field">
          <label for="estatus" class="required">Estatus inicial</label>
          <select id="estatus" name="estatus" required>
            <option value="">Selecciona…</option>
            <option value="abierto">Abierto</option>
            <option value="en_revision">En revisión</option>
            <option value="completado">Completado</option>
          </select>
        </div>

        <div class="field">
          <label for="abierto_en" class="required">Fecha de apertura</label>
          <input type="datetime-local" id="abierto_en" name="abierto_en" required>
        </div>

        <div class="field">
          <label for="cerrado_en">Fecha de cierre</label>
          <input type="datetime-local" id="cerrado_en" name="cerrado_en">
          <div class="hint">Opcional — solo si el periodo ya está cerrado.</div>
        </div>
      </div>

      <div class="actions">
        <a href="#" class="btn-secondary">Cancelar</a>
        <button type="submit" class="btn-primary">Guardar Periodo</button>
      </div>

    </form>
  </div>
</main>

</body>
</html>
