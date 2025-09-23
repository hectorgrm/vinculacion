<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Social - Nueva Plaza</title>
    <link rel="stylesheet" href="../../assets/css/nuevaplazastyles.css">
    
</head>
<body>

  <header>
    <div class="title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 7h18M5 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
        <rect x="7" y="3" width="10" height="4" rx="1.3" stroke="#fff" stroke-width="1.7"/>
      </svg>
      <h1>Servicio Social · Nueva Plaza</h1>
    </div>
    <nav class="breadcrumb">
      <a href="../../dashboard.php">Inicio</a>
      <span class="sep">›</span>
      <a href="../gestionplaza/plaza_list.php">Gestión de Plazas</a>
      <span class="sep">›</span>
      <span>Nueva</span>
    </nav>
  </header>

  <main>
    <div class="card">
      <header>
        <h2>Registrar Plaza</h2>
        <p>Completa los datos de la plaza que estará disponible para estudiantes de Servicio Social.</p>
      </header>

      <form method="post" action="../../controller/PlazaController.php?action=create" autocomplete="off">
        <!-- Si usas CSRF, coloca tu token aquí -->
        <!-- <input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf ?? '') ?>"> -->

        <!-- Datos generales -->
        <div class="grid cols-2">
          <div class="field">
            <label for="plaza_nombre" class="required">Nombre de la plaza</label>
            <input id="plaza_nombre" name="plaza_nombre" type="text" required placeholder="Ej. Auxiliar de Soporte TI">
            <div class="hint">Nombre corto y claro de la vacante/plaza.</div>
          </div>

          <div class="field">
            <label for="dependencia_id" class="required">Dependencia / Empresa</label>
            <select id="dependencia_id" name="dependencia_id" required>
              <option value="">Selecciona…</option>
              <!-- Rellenar con PHP: foreach ($empresas as $e) { echo "<option value='{$e['id']}'>{$e['nombre']}</option>"; } -->
            </select>
            <div class="hint">Entidad donde se realizará el servicio.</div>
          </div>

          <div class="field">
            <label for="programa_id">Programa</label>
            <select id="programa_id" name="programa_id">
              <option value="">Selecciona…</option>
              <!-- Opcional: lista de programas -->
            </select>
          </div>

          <div class="field">
            <label for="modalidad" class="required">Modalidad</label>
            <select id="modalidad" name="modalidad" required>
              <option value="">Selecciona…</option>
              <option value="presencial">Presencial</option>
              <option value="hibrida">Híbrida</option>
              <option value="remota">Remota</option>
            </select>
          </div>

          <div class="field">
            <label for="horario_texto">Horario</label>
            <input id="horario_texto" name="horario_texto" type="text" placeholder="Ej. L–V 9:00–14:00">
          </div>

          <div class="field">
            <label for="cupo" class="required">Cupo</label>
            <input id="cupo" name="cupo" type="number" min="1" step="1" required placeholder="Ej. 3">
          </div>

          <div class="field">
            <label for="periodo_inicio" class="required">Periodo — Inicio</label>
            <input id="periodo_inicio" name="periodo_inicio" type="date" required>
          </div>

          <div class="field">
            <label for="periodo_fin" class="required">Periodo — Fin</label>
            <input id="periodo_fin" name="periodo_fin" type="date" required>
          </div>
        </div>

        <!-- Descripción -->
        <div class="section">
          <h3>Descripción y requisitos</h3>
          <div class="grid cols-2">
            <div class="field">
              <label for="actividades" class="required">Actividades a realizar</label>
              <textarea id="actividades" name="actividades" required placeholder="Describe las tareas principales…"></textarea>
            </div>
            <div class="field">
              <label for="requisitos" class="required">Requisitos</label>
              <textarea id="requisitos" name="requisitos" required placeholder="Conocimientos, habilidades, documentos…"></textarea>
            </div>
          </div>
        </div>

        <!-- Contacto -->
        <div class="section">
          <h3>Contacto</h3>
          <div class="grid cols-2">
            <div class="field">
              <label for="responsable_nombre" class="required">Responsable</label>
              <input id="responsable_nombre" name="responsable_nombre" type="text" required placeholder="Nombre completo">
            </div>
            <div class="field">
              <label for="responsable_puesto">Puesto</label>
              <input id="responsable_puesto" name="responsable_puesto" type="text" placeholder="Ej. Jefe de Sistemas">
            </div>
            <div class="field">
              <label for="responsable_email" class="required">Correo</label>
              <input id="responsable_email" name="responsable_email" type="email" required placeholder="correo@empresa.com">
            </div>
            <div class="field">
              <label for="responsable_tel">Teléfono</label>
              <input id="responsable_tel" name="responsable_tel" type="tel" placeholder="Ej. 55 1234 5678">
            </div>
          </div>
        </div>

        <!-- Ubicación y estado -->
        <div class="section">
          <h3>Ubicación y estado</h3>
          <div class="grid cols-2">
            <div class="field">
              <label for="ubicacion">Ubicación</label>
              <input id="ubicacion" name="ubicacion" type="text" placeholder="Dirección o ciudad">
            </div>
            <div class="field">
              <label for="estado_plaza" class="required">Estado</label>
              <select id="estado_plaza" name="estado_plaza" required>
                <option value="">Selecciona…</option>
                <option value="activa">Activa</option>
                <option value="inactiva">Inactiva</option>
              </select>
            </div>
            <div class="field" style="grid-column:1/-1">
              <label for="observaciones">Observaciones</label>
              <textarea id="observaciones" name="observaciones" placeholder="Notas internas, consideraciones, etc."></textarea>
            </div>
          </div>
        </div>

        <div class="actions">
          <span class="note">Revisa los campos marcados con <strong style="color:var(--danger)">*</strong> antes de guardar.</span>
          <a class="btn ghost" href="./plaza_list.php">Cancelar</a>
          <button type="submit" class="btn primary">Guardar plaza</button>
        </div>
      </form>
    </div>
  </main>

</body>
</html>