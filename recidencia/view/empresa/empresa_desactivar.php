<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Desactivar Empresa · Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="../../assets/css/empresas/empresadelete.css">
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Desactivar Empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="empresa_list.php">Empresas</a>
            <span>›</span>
            <span>Desactivar</span>
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
            Vas a <strong>desactivar</strong> la empresa
            <strong>“Casa del Barrio”</strong> (ID: <strong>#45</strong>).
            Esta acción suspenderá su acceso y convenios asociados.
          </p>

          <ul class="danger-list">
            <li>Los convenios quedarán inactivos.</li>
            <li>Los documentos se bloquearán.</li>
            <li>El acceso al portal será deshabilitado.</li>
          </ul>





          <form action="" method="post" style="margin-top:18px;">
            <input type="hidden" name="empresa_id" value="45">

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>Confirmo que deseo <strong>desactivar</strong> esta empresa.</span>
            </label>

            <div class="actions" style="justify-content:flex-end;">
              <a href="empresa_view.php?id=45" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger">🚫 Desactivar Empresa</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px">
            La empresa podrá reactivarse desde el panel de administración.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
