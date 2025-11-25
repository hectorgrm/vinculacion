<?php
declare(strict_types=1);

/** @var array{
 *     estudianteId: ?int,
 *     formData: array<string, string>,
 *     empresas: array<int, array<string, string>>,
 *     convenios: array<int, array<string, string>>,
 *     errors: array<int, string>,
 *     success: bool,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     loadError: ?string,
 *     estatusOptions: array<int, string>
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/estudiante/estudiante_edit_handler.php';

$formData = $handlerResult['formData'];
$empresas = $handlerResult['empresas'];
$convenios = $handlerResult['convenios'];
$viewErrors = $handlerResult['errors'];
$viewSuccess = $handlerResult['success'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$loadError = $handlerResult['loadError'];
$estatusOptions = $handlerResult['estatusOptions'];
$estudianteId = $handlerResult['estudianteId'];

$canEdit = $controllerError === null && $loadError === null;
$returnUrl = $estudianteId !== null
    ? 'estudiante_view.php?id=' . rawurlencode((string) $estudianteId)
    : 'estudiante_list.php';
$isLocked = $canEdit && isset($formData['estatus']) && $formData['estatus'] === 'Inactivo';
$empresaSelectDisabled = ($empresas === []) || $isLocked;
$convenioSelectDisabled = ($convenios === []) || $isLocked;
$submitDisabled = ($empresas === [] && !$isLocked);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />


</head>

<body>
 <div class="app">
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

  <main class="main">

    <header class="topbar">
      <div>
        <h2>✏️ Editar Estudiante</h2>
        <p class="subtitle">Modifica los datos de un estudiante y su asignación a empresa y convenio.</p>
      </div>
      <div class="actions">
        <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn secondary">← Regresar</a>
      </div>
    </header>

    <?php if ($controllerError !== null): ?>
      <div class="dangerbox">
        <strong>⚠️ Error:</strong>
        <span><?= htmlspecialchars($controllerError) ?></span>
      </div>
    <?php endif; ?>

    <?php if ($loadError !== null): ?>
      <div class="dangerbox">
        <strong>⚠️ No se pudo cargar la información.</strong>
        <span><?= htmlspecialchars($loadError) ?></span>
      </div>
    <?php endif; ?>

    <?php if ($viewSuccess && $successMessage !== null): ?>
      <div class="successbox">
        <strong>✅ Cambios guardados.</strong>
        <span><?= htmlspecialchars($successMessage) ?></span>
      </div>
    <?php endif; ?>

    <?php if ($viewErrors !== []): ?>
      <div class="dangerbox">
        <strong>⚠️ Corrige los siguientes puntos:</strong>
        <ul>
          <?php foreach ($viewErrors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <section class="card">
      <header>Datos generales del estudiante</header>
      <div class="content">
        <?php if ($canEdit): ?>
        <?php if ($isLocked): ?>
          <div class="infobox">
            <strong>Estudiante inactivo.</strong>
            Solo puedes modificar el estatus para reactivarlo; los demás campos permanecen bloqueados.
          </div>
        <?php endif; ?>
        <form method="post" class="form-grid">
          <input type="hidden" name="estudiante_id" value="<?= htmlspecialchars((string) ($estudianteId ?? '')) ?>">

          <div class="form-group">
            <label for="nombre">Nombre(s)</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($formData['nombre']) ?>" required<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="apellido_paterno">Apellido paterno</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($formData['apellido_paterno']) ?>"<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="apellido_materno">Apellido materno</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($formData['apellido_materno']) ?>"<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="matricula">Matrícula</label>
            <input type="text" id="matricula" name="matricula" value="<?= htmlspecialchars($formData['matricula']) ?>" required<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="carrera">Carrera</label>
            <input type="text" id="carrera" name="carrera" value="<?= htmlspecialchars($formData['carrera']) ?>"<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="correo_institucional">Correo institucional</label>
            <input type="email" id="correo_institucional" name="correo_institucional" value="<?= htmlspecialchars($formData['correo_institucional']) ?>"<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($formData['telefono']) ?>"<?= $isLocked ? ' readonly' : '' ?>>
          </div>

          <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select id="empresa_id" name="empresa_id" required <?= $empresaSelectDisabled ? 'disabled' : '' ?>>
              <option value="">Selecciona una empresa...</option>
              <?php foreach ($empresas as $empresa): ?>
                <option value="<?= htmlspecialchars($empresa['id']) ?>" <?= $formData['empresa_id'] === $empresa['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($empresa['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if ($isLocked): ?>
              <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($formData['empresa_id']) ?>">
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="convenio_id">Convenio</label>
            <select id="convenio_id" name="convenio_id" <?= $convenioSelectDisabled ? 'disabled' : '' ?>>
              <option value="">Sin asignar</option>
              <?php foreach ($convenios as $convenio): ?>
                <option value="<?= htmlspecialchars($convenio['id']) ?>" <?= $formData['convenio_id'] === $convenio['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($convenio['folio'] !== '' ? $convenio['folio'] : 'Sin folio') ?>
                  <?php if ($convenio['empresa_nombre'] !== ''): ?>
                    · <?= htmlspecialchars($convenio['empresa_nombre']) ?>
                  <?php endif; ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?php if ($isLocked): ?>
              <input type="hidden" name="convenio_id" value="<?= htmlspecialchars($formData['convenio_id']) ?>">
            <?php endif; ?>
          </div>

          <div class="form-group">
            <label for="estatus">Estatus</label>
            <select id="estatus" name="estatus">
              <?php foreach ($estatusOptions as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>" <?= $formData['estatus'] === $option ? 'selected' : '' ?>>
                  <?= htmlspecialchars($option) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn primary" <?= $submitDisabled ? 'disabled' : '' ?>>💾 Guardar cambios</button>
            <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn secondary">Cancelar</a>
          </div>
        </form>
        <?php else: ?>
          <div class="empty-state">
            No es posible editar este registro en este momento.
          </div>
        <?php endif; ?>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
