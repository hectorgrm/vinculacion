<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Machote · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/machote/machote_edit.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <h2>✏️ Editar Machote</h2>
        <p class="subtitle">Modifica el nombre, versión o archivo base de este machote.</p>
      </header>

      <section class="card">
        <form action="edit_action.php" method="post" enctype="multipart/form-data" class="form-grid">
          <!-- ID oculto -->
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

          <!-- Empresa (solo lectura) -->
          <div class="form-group">
            <label for="empresa">Empresa</label>
            <input type="text" id="empresa" value="Casa del Barrio" readonly>
          </div>

          <!-- Nombre -->
          <div class="form-group">
            <label for="nombre_machote">Nombre del machote</label>
            <input type="text" name="nombre_machote" id="nombre_machote" value="Institucional Base" required>
          </div>

          <!-- Versión -->
          <div class="form-group">
            <label for="version_machote">Versión</label>
            <input type="text" name="version_machote" id="version_machote" value="v1.2" required>
          </div>

          <!-- Reemplazar archivo -->
          <div class="form-group full">
            <label for="archivo">Reemplazar archivo (opcional)</label>
            <input type="file" name="archivo" id="archivo">
            <small>Si no seleccionas archivo, se conservará el actual.</small>
            <div class="current-file">
              📄 <a href="../../uploads/machote_v12.pdf" target="_blank">Ver archivo actual</a>
            </div>
          </div>

          <!-- Acciones -->
          <div class="form-actions">
            <button type="submit" class="btn primary">💾 Guardar Cambios</button>
            <a href="machote_list.php" class="btn secondary">Cancelar</a>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>
