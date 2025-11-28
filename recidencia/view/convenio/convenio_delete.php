<?php
declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_delete_handler.php';

$controllerError = $handlerResult['controllerError'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$convenioIdDisplay = $handlerResult['convenioIdDisplay'];
$empresaIdDisplay = $handlerResult['empresaIdDisplay'];
$empresaNombreDisplay = $handlerResult['empresaNombreDisplay'];
$motivoValue = $handlerResult['motivoValue'];
$confirmChecked = $handlerResult['confirmChecked'];
$isAlreadyInactive = $handlerResult['isAlreadyInactive'];
$formDisabled = $handlerResult['formDisabled'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
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
          <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>" class="btn">Ver</a>
          <?php endif; ?>
          <a href="convenio_list.php" class="btn secondary">Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>Confirmación requerida</header>
        <div class="content">
          <?php if ($controllerError !== null): ?>
          <div class="alert error" role="alert">
            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($errors !== []): ?>
          <div class="alert error" role="alert">
            <ul>
              <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
          <div class="alert success" role="alert">
            <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($isAlreadyInactive && $successMessage === null): ?>
          <p class="text-muted">
            Este convenio ya se encuentra marcado como <strong>Inactiva</strong>.
          </p>
          <?php endif; ?>

          <p>
            Estás a punto de <strong>desactivar</strong> el convenio
            <strong>#<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?></strong>
            <?php if ($empresaNombreDisplay !== ''): ?>
              de la empresa <strong><?php echo htmlspecialchars($empresaNombreDisplay, ENT_QUOTES, 'UTF-8'); ?></strong>
            <?php endif; ?>
            <?php if ($empresaIdDisplay !== ''): ?>
              <a class="btn secondary" href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Ver empresa</a>.
            <?php else: ?>
              .
            <?php endif; ?>
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

            <div class="links-inline" style="margin-top:10px;">
              <?php if ($empresaIdDisplay !== '' && $convenioIdDisplay !== ''): ?>
              <a class="btn secondary" href="../documentos/documento_list.php?empresa=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>&convenio=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Ver documentos</a>
              <a class="btn secondary" href="../machote/revisar.php?id_empresa=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>&convenio=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">Revisar machote</a>
              <?php endif; ?>
            </div>
          </div>

          <div class="grid-2" style="margin-top:16px;">
            <div class="card mini">
              <h3>Documentos del convenio</h3>
              <p class="text-muted">Aprobados: - · Pendientes: -</p>
            </div>
            <div class="card mini">
              <h3>Observaciones de machote</h3>
              <p class="text-muted">Aprobadas: - · En revisión: -</p>
            </div>
          </div>

          <form action="" method="post">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" value="1" <?php echo $confirmChecked ? 'checked' : ''; ?> <?php echo $formDisabled ? 'disabled' : ''; ?>>
              <span>He leído las advertencias y deseo <strong>desactivar</strong> este convenio.</span>
            </label>

            <div class="field" style="margin-top:10px;">
              <label for="motivo">Motivo (opcional)</label>
              <textarea id="motivo" name="motivo" rows="3" placeholder="Describe brevemente el motivo de la desactivación..." <?php echo $formDisabled ? 'disabled' : ''; ?>><?php echo htmlspecialchars($motivoValue, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>

            <div class="actions" style="justify-content:flex-end;">
              <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>" class="btn secondary">Cancelar</a>
              <button type="submit" class="btn danger" <?php echo $formDisabled ? 'disabled' : ''; ?>>Desactivar Convenio</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px;">
            Consejo: si el convenio ya concluyó, también puedes actualizar su fecha de fin o marcarlo como <em>"Vencido"</em>
            en lugar de desactivarlo completamente.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
