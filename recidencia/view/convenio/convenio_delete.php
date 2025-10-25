<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Desactivar Convenio · Residencias Profesionales</title>

  <!-- Estilos globales -->
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
          <h2>🚫 Desactivar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="convenio_list.php">Convenios</a>
            <span>›</span>
            <span>Desactivar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="convenio_view.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" class="btn">👁️ Ver</a>
          <a href="convenio_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>
            Estás a punto de <strong>desactivar</strong> el convenio 
            <strong>#<?php echo htmlspecialchars($_GET['id'] ?? ''); ?></strong> 
            de la empresa 
            <a class="btn" href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars($_GET['empresa_id'] ?? ''); ?>">🏢 Ver empresa</a>.
          </p>

          <p>
            Esta acción <strong>no eliminará datos</strong>, pero cambiará el estatus del convenio a <strong>Inactiva</strong>. 
            El convenio dejará de estar disponible para nuevas asignaciones o ediciones hasta su reactivación.
          </p>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no existan documentos</strong> asociados pendientes (anexos, oficios, etc.).</li>
              <li>Que <strong>no haya observaciones de machote</strong> en revisión.</li>
              <li>Que <strong>no esté en uso</strong> en algún flujo activo.</li>
            </ul>

            <!-- Accesos rápidos a secciones relacionadas -->
            <div class="links-inline" style="margin-top:10px;">
              <a class="btn" href="../documentos/documento_list.php?empresa=<?php echo htmlspecialchars($_GET['empresa_id'] ?? ''); ?>&convenio=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">📂 Ver documentos</a>
              <a class="btn" href="../machote/revisar.php?id_empresa=<?php echo htmlspecialchars($_GET['empresa_id'] ?? ''); ?>&convenio=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">📝 Revisar machote</a>
            </div>
          </div>

          <div class="grid-2" style="margin-top:16px;">
            <div class="card mini">
              <h3>Documentos del convenio</h3>
              <p class="text-muted">Aprobados: — · Pendientes: —</p>
            </div>
            <div class="card mini">
              <h3>Observaciones de machote</h3>
              <p class="text-muted">Aprobadas: — · En revisión: —</p>
            </div>
          </div>

          <form action="convenio_delete_action.php" method="post" style="margin-top:18px;">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>">
            <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars($_GET['empresa_id'] ?? ''); ?>">

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>He leído las advertencias y deseo <strong>desactivar</strong> este convenio.</span>
            </label>

            <div class="field" style="margin-top:10px;">
              <label for="motivo">Motivo (opcional)</label>
              <textarea id="motivo" name="motivo" rows="3" placeholder="Describe brevemente el motivo de la desactivación..."></textarea>
            </div>

            <div class="actions" style="justify-content:flex-end;">
              <a href="convenio_view.php?id=<?php echo htmlspecialchars($_GET['id'] ?? ''); ?>" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger">🚫 Desactivar Convenio</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px;">
            💡 Consejo: si el convenio ya concluyó, también puedes actualizar su fecha de fin o marcarlo como <em>"Vencido"</em> 
            en lugar de desactivarlo completamente.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
