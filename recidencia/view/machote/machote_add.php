<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nuevo Machote · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/machote/machote_add.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>➕ Registrar nuevo Machote</h2>
        <p class="subtitle">Crea una nueva revisión de machote para una empresa y da inicio al proceso de aprobación.</p>
      </header>

      <section class="card">
        <form action="add_action.php" method="post" enctype="multipart/form-data" class="form-grid">
          
          <!-- Empresa -->
          <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select name="empresa_id" id="empresa_id" required>
              <option value="">-- Selecciona una empresa --</option>
              <!-- 🔁 Este listado vendrá dinámicamente desde la BD -->
              <option value="1">Casa del Barrio</option>
              <option value="2">Tequila ECT</option>
              <option value="3">Industrias Yakumo</option>
            </select>
          </div>

          <!-- Nombre / Tipo Machote -->
          <div class="form-group">
            <label for="nombre_machote">Nombre del machote</label>
            <input type="text" name="nombre_machote" id="nombre_machote" placeholder="Ej. Machote institucional base" required>
          </div>

          <!-- Versión -->
          <div class="form-group">
            <label for="version_machote">Versión</label>
            <input type="text" name="version_machote" id="version_machote" placeholder="Ej. v1.0" required>
          </div>

          <!-- Archivo -->
          <div class="form-group full">
            <label for="archivo">Archivo inicial</label>
            <input type="file" name="archivo" id="archivo" required>
            <small>Formatos permitidos: PDF o DOCX · Máx. 10 MB</small>
          </div>

          <!-- Acciones -->
          <div class="form-actions">
            <button type="submit" class="btn primary">💾 Guardar Machote</button>
            <a href="machote_list.php" class="btn secondary">Cancelar</a>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>
