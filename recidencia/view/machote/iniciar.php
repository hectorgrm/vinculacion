<?php require_once __DIR__ . '/../../common/auth.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar revisión de machote · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css">
  <link rel="stylesheet" href="../../assets/css/machote/revision.css">
</head>
<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <h2>📝 Iniciar revisión de machote</h2>
        <a href="../empresas/list.php" class="btn secondary">⬅ Volver</a>
      </header>

      <section class="card">
        <header>Datos para la revisión</header>
        <div class="content">
          <form class="grid-2" action="revision_create_action.php" method="post">
            <!-- Si llegas desde empresa_view.php, puedes renderizar el nombre y enviar el id oculto -->
            <div class="full">
              <label for="empresa">Empresa</label>
              <input type="text" id="empresa" value="Casa del Barrio" disabled>
              <input type="hidden" name="empresa_id" value="1">
            </div>

            <div>
              <label for="machote_version">Versión del machote</label>
              <input type="text" id="machote_version" name="machote_version" placeholder="Institucional v1.2" required>
            </div>

            <div>
              <label for="nota">Nota interna (opcional)</label>
              <input type="text" id="nota" name="nota" placeholder="Alguna instrucción breve…">
            </div>

            <div class="actions full">
              <button type="submit" class="btn primary">🚀 Crear revisión</button>
              <a href="../empresas/view.php?id=1" class="btn">Cancelar</a>
            </div>
          </form>
        </div>
      </section>

      <section class="hint">
        <p>Al crear la revisión, podrás abrir hilos de comentario, adjuntar archivos y activar la aprobación dual.</p>
      </section>
    </main>
  </div>
</body>
</html>
