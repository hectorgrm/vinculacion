<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Eliminar Empresa · Residencias Profesionales</title>

  <!-- Estilo global del módulo -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
    <link rel="stylesheet" href="../../assets/css/empresas/empresadelete.css">



</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar Empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="empresa_list.php">Empresas</a>
            <span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <div class="top-actions">
          <a href="empresa_view.php?id=45" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>
            Estás a punto de <strong>eliminar definitivamente</strong> la empresa
            <strong>“Casa del Barrio”</strong> (ID: <strong>#45</strong>). Esta acción no se puede deshacer.
          </p>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no existan convenios vigentes</strong> o por firmar.</li>
              <li>Que <strong>no existan documentos</strong> pendientes de validar.</li>
              <li>Que <strong>no tenga accesos de portal</strong> activos.</li>
            </ul>

            <!-- Accesos rápidos a secciones relacionadas -->
            <div class="links-inline" style="margin-top:10px;">
              <a class="btn" href="../convenio/convenio_list.php?empresa=45">📑 Ver convenios</a>
              <a class="btn" href="../documentos/documento_list.php?empresa=45">📂 Ver documentos</a>
              <a class="btn" href="../portalacceso/portal_list.php?empresa=45">🔐 Ver accesos</a>
            </div>
          </div>

          <!-- Resumen (estático de ejemplo; luego será dinámico) -->
          <div class="grid-2" style="margin-top:16px;">
            <div class="card mini">
              <h3>Convenios asociados</h3>
              <p class="text-muted">Vigentes: 1 · En revisión: 0</p>
            </div>
            <div class="card mini">
              <h3>Documentos</h3>
              <p class="text-muted">Aprobados: 1 · Pendientes: 1</p>
            </div>
          </div>

          <form action="" method="post" style="margin-top:18px;">
            <input type="hidden" name="empresa_id" value="45">
            <!-- Si manejas CSRF: -->
            <!-- <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '';?>"> -->

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>He leído las advertencias y deseo <strong>eliminar permanentemente</strong> esta empresa.</span>
            </label>

            <div class="actions" style="justify-content:flex-end;">
              <a href="empresa_view.php?id=45" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger">🗑️ Eliminar Empresa</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px">
            Sugerencia: si quieres conservar historial, considera <em>archivar</em> la empresa en lugar de eliminarla.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
