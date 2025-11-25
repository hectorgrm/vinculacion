<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Machote · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/machote.css" />
</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar danger">
        <h2>🗑️ Eliminar Machote</h2>
        <p class="subtitle">Esta acción es irreversible. Asegúrate de que este machote no esté en uso.</p>
      </header>

      <section class="card danger-zone">
        <h3>⚠️ Confirmar eliminación</h3>
        <p>
          Estás a punto de <strong>eliminar definitivamente</strong> el machote
          <strong>"Institucional v1.2"</strong> asociado a la empresa <strong>"Casa del Barrio"</strong>.
        </p>
        <p>
          Antes de continuar, asegúrate de:
        </p>
        <ul>
          <li>Que no existan revisiones activas.</li>
          <li>Que no esté vinculado a ningún convenio aprobado.</li>
        </ul>

        <form action="delete_action.php" method="post">
          <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
          <div class="actions">
            <a href="machote_list.php" class="btn secondary">⬅ Cancelar</a>
            <button type="submit" class="btn danger" onclick="return confirm('¿Estás seguro de eliminar este machote?')">🗑️ Eliminar Machote</button>
          </div>
        </form>
      </section>
    </main>
  </div>
</body>
</html>
