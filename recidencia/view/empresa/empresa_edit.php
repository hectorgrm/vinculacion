<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>✏️ Editar Empresa · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/empresas/empresaedit.css" />
</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>✏️ Editar Empresa</h2>
          <p class="subtitle">Actualiza la información institucional y de contacto</p>
        </div>
        <div class="top-actions">
          <a href="empresa_list.php" class="btn secondary">⬅ Volver</a>
        </div>
      </header>

      <!-- Aviso contextual -->
      <section class="card">
        <header>📌 Contexto</header>
        <div class="content">
          <div class="alert info">
            Estás editando la empresa <strong>Casa del Barrio</strong> (ID <strong>#45</strong>).
          </div>
        </div>
      </section>

      <!-- Formulario principal -->
      <form class="form-grid" method="post" action="update.php">
        <input type="hidden" name="empresa_id" value="45" />

        <!-- 📄 Datos generales -->
        <section class="card">
          <header>🏢 Datos Generales</header>
          <div class="content grid">
            <div class="field">
              <label for="nombre" class="required">Nombre de la empresa *</label>
              <input id="nombre" name="nombre" type="text" required placeholder="Ej: Casa del Barrio" value="Casa del Barrio" />
            </div>

            <div class="field">
              <label for="rfc">RFC</label>
              <input id="rfc" name="rfc" type="text" maxlength="20" placeholder="Ej: CDB810101AA1" value="CDB810101AA1" />
            </div>

            <div class="field">
              <label for="representante">Representante legal</label>
              <input id="representante" name="representante" type="text" placeholder="Ej: José Manuel Velador" value="José Manuel Velador" />
            </div>

            <div class="field">
              <label for="cargo_representante">Cargo del representante</label>
              <input id="cargo_representante" name="cargo_representante" type="text" placeholder="Ej: Director General" />
            </div>

            <div class="field">
              <label for="sector">Sector / Giro</label>
              <input id="sector" name="sector" type="text" placeholder="Ej: Educación / Social" value="Educación / Social" />
            </div>

            <div class="field">
              <label for="sitio_web">Sitio web</label>
              <input id="sitio_web" name="sitio_web" type="url" placeholder="https://www.empresa.mx" />
            </div>
          </div>
        </section>

        <!-- 📬 Contacto y Dirección -->
        <section class="card">
          <header>📬 Contacto y Dirección</header>
          <div class="content grid">
            <div class="field">
              <label for="contacto_nombre">Nombre de contacto</label>
              <input id="contacto_nombre" name="contacto_nombre" type="text" placeholder="Ej: Responsable RH" value="José Manuel Velador" />
            </div>

            <div class="field">
              <label for="contacto_email">Correo electrónico</label>
              <input id="contacto_email" name="contacto_email" type="email" placeholder="contacto@empresa.mx" value="contacto@casadelbarrio.mx" />
            </div>

            <div class="field">
              <label for="telefono">Teléfono</label>
              <input id="telefono" name="telefono" type="tel" placeholder="(33) 1234 5678" value="(33) 1234 5678" />
            </div>

            <div class="field">
              <label for="estado">Estado</label>
              <input id="estado" name="estado" type="text" placeholder="Ej: Jalisco" value="Jalisco" />
            </div>

            <div class="field">
              <label for="municipio">Municipio / Alcaldía</label>
              <input id="municipio" name="municipio" type="text" placeholder="Ej: Guadalajara" />
            </div>

            <div class="field">
              <label for="cp">Código Postal</label>
              <input id="cp" name="cp" type="text" placeholder="Ej: 44100" />
            </div>

            <div class="field col-span-2">
              <label for="direccion">Dirección (calle y número)</label>
              <input id="direccion" name="direccion" type="text" placeholder="Ej: Calle Independencia 321" />
            </div>
          </div>
        </section>

        <!-- ⚙️ Configuración -->
        <section class="card">
          <header>⚙️ Configuración</header>
          <div class="content grid">
            <div class="field">
              <label for="estatus">Estatus</label>
              <select id="estatus" name="estatus">
                <option value="Activa" selected>Activa</option>
                <option value="En revisión">En revisión</option>
                <option value="Inactiva">Inactiva</option>
                <option value="Suspendida">Suspendida</option>
              </select>
            </div>

            <div class="field">
              <label for="regimen_fiscal">Régimen fiscal</label>
              <input id="regimen_fiscal" name="regimen_fiscal" type="text" placeholder="Opcional" />
            </div>

            <div class="field col-span-2">
              <label for="notas">Notas / Observaciones</label>
              <textarea id="notas" name="notas" rows="4" placeholder="Comentarios internos del área de vinculación..."></textarea>
            </div>
          </div>

          <div class="actions">
            <a href="view.php?id=45" class="btn secondary">Cancelar</a>
            <button type="submit" class="btn primary">💾 Guardar Cambios</button>
          </div>
        </section>
      </form>
    </main>
  </div>
</body>
</html>
