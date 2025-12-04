<?php
declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_delete_handler.php';

$controllerError = $handlerResult['controllerError'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$convenioIdDisplay = $handlerResult['convenioIdDisplay'];
$empresaIdDisplay = $handlerResult['empresaIdDisplay'];
$machoteIdDisplay = $handlerResult['machoteIdDisplay'] ?? '';
$empresaNombreDisplay = $handlerResult['empresaNombreDisplay'];
$motivoValue = $handlerResult['motivoValue'];
$confirmChecked = $handlerResult['confirmChecked'];
$isAlreadyInactive = $handlerResult['isAlreadyInactive'];
$formDisabled = $handlerResult['formDisabled'];
$empresaIsCompletada = $handlerResult['empresaIsCompletada'] ?? false;
$empresaIsInactiva = $handlerResult['empresaIsInactiva'] ?? false;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Desactivar Convenio | Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/convenio/conveniodelete.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Desactivar Convenio</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>&gt;</span>
            <a href="convenio_list.php">Convenios</a>
            <span>&gt;</span>
            <span>Desactivar</span>
          </nav>
        </div>
        <div class="top-actions">
          <?php if ($convenioIdDisplay !== ''): ?>
            <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>"
              class="btn">Ver</a>
          <?php endif; ?>
          <a href="convenio_list.php" class="btn secondary">Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>Confirmación requerida</header>

        <div class="content">

          <p>
            Estás a punto de <strong>archivar permanentemente</strong> el convenio
            <strong>#<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?></strong>
            <?php if ($empresaNombreDisplay !== ''): ?>
              de la empresa <strong><?php echo htmlspecialchars($empresaNombreDisplay, ENT_QUOTES, 'UTF-8'); ?></strong>
            <?php endif; ?>
            <?php if ($empresaIdDisplay !== ''): ?>
              <a class="btn secondary"
                href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Ver
                empresa</a>.
            <?php endif; ?>
          </p>

          <p>
            Esta acción <strong>no eliminará datos</strong>, pero el convenio será
            <strong>archivado de forma definitiva</strong>.
            Una vez archivado:
          </p>

          <ul class="danger-list" style="margin:10px 0 20px 20px;">
            <li>No podrá reactivarse ni volver a estar disponible.</li>
            <li>No podrá usarse para asignaciones, ediciones o nuevos flujos.</li>
            <li>Su machote hijo será cerrado permanentemente.</li>
            <li>Las revisiones activas serán canceladas.</li>
            <li>Los comentarios quedarán bloqueados para siempre.</li>
          </ul>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no existan documentos pendientes</strong> asociados al convenio.</li>
              <li>Que <strong>no haya observaciones de machote</strong> en revisión.</li>
              <li>Que <strong>no esté en uso</strong> en algún flujo activo.</li>
            </ul>

            <div class="links-inline" style="margin-top:10px;">
              <?php if ($empresaIdDisplay !== '' && $convenioIdDisplay !== ''): ?>
                <a class="btn secondary"
                  href="../documentos/documento_list.php?empresa=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>&convenio=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Ver
                  documentos</a>
              <?php endif; ?>
              <?php if ($machoteIdDisplay !== ''): ?>
                <a class="btn secondary"
                  href="../machote/machote_revisar.php?id=<?php echo htmlspecialchars($machoteIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Revisar
                  machote</a>
              <?php endif; ?>
            </div>
          </div>

          <form action="" method="post">
            <input type="hidden" name="id"
              value="<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="empresa_id"
              value="<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" value="1" <?php echo $confirmChecked ? 'checked' : ''; ?>>
              <span>Confirmo que comprendo que esta acción es <strong>permanente</strong> y deseo archivar este convenio
                definitivamente.</span>
            </label>

            <div class="field" style="margin-top:10px;">
              <label for="motivo">Motivo (opcional)</label>
              <textarea id="motivo" name="motivo" rows="3"
                placeholder="Describe brevemente el motivo..."><?php echo htmlspecialchars($motivoValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="actions" style="justify-content:flex-end;">
              <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>"
                class="btn secondary">Cancelar</a>
              <button type="submit" class="btn danger">Archivar Convenio</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px;">
            Consejo: si el convenio solo expiró, puedes actualizar su vigencia
            en lugar de archivarlo de forma definitiva.
          </p>

        </div>

      </section>
    </main>
  </div>
</body>

</html>