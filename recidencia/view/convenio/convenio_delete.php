<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Eliminar Convenio · Residencias Profesionales</title>

  <!-- Estilo global del módulo -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
      <link rel="stylesheet" href="../../assets/css/convenios/convenio_delete.css" />



</head>
<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="convenio_list.php">Convenios</a>
            <span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="convenio_view.php?id=12" class="btn">👁️ Ver</a>
          <a href="convenio_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>
            Estás a punto de <strong>eliminar definitivamente</strong> el convenio
            <strong>#12</strong> (versión <strong>v1.2</strong>) de la empresa
            <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Casa del Barrio (ID 45)</a>.
            Esta acción <strong>no se puede deshacer</strong>.
          </p>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no existan documentos</strong> asociados pendientes (anexos, oficios, etc.).</li>
              <li>Que <strong>no haya observaciones de machote</strong> pendientes de resolver.</li>
              <li>Que <strong>no esté en uso</strong> en algún flujo activo.</li>
            </ul>

            <!-- Accesos rápidos a secciones relacionadas -->
            <div class="links-inline" style="margin-top:10px;">
              <a class="btn" href="../documentos/documento_list.php?empresa=45&convenio=12">📂 Ver documentos</a>
              <a class="btn" href="../machote/revisar.php?id_empresa=45&convenio=12">📝 Revisar machote</a>
              <a class="btn" href="../empresa/empresa_view.php?id=45">🏢 Ir a la empresa</a>
            </div>
          </div>

          <!-- Resumen (estático de ejemplo; luego será dinámico) -->
          <div class="grid-2" style="margin-top:16px;">
            <div class="card mini">
              <h3>Documentos del convenio</h3>
              <p class="text-muted">Aprobados: 1 · Pendientes: 1</p>
            </div>
            <div class="card mini">
              <h3>Observaciones de machote</h3>
              <p class="text-muted">Aprobadas: 2 · En revisión: 1</p>
            </div>
          </div>

          <form action="convenio_delete_action.php" method="post" style="margin-top:18px;">
            <input type="hidden" name="id" value="12">
            <input type="hidden" name="empresa_id" value="45">
            <!-- Si manejas CSRF:
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? '';?>">
            -->

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>He leído las advertencias y deseo <strong>eliminar permanentemente</strong> este convenio.</span>
            </label>

            <!-- (Opcional) Motivo -->
            <div class="field" style="margin-top:10px;">
              <label for="motivo">Motivo (opcional)</label>
              <textarea id="motivo" name="motivo" rows="3" placeholder="Breve explicación para la bitácora..."></textarea>
            </div>

            <div class="actions" style="justify-content:flex-end;">
              <a href="convenio_view.php?id=12" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger">🗑️ Eliminar Convenio</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px">
            Sugerencia: si quieres conservar historial, considera <em>archivar</em> el convenio (cambiar estatus a <strong>Vencido</strong>) en lugar de eliminarlo.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
