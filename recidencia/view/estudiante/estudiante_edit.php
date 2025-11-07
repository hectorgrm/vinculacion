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
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />

  <style>
    body {
      font-family: "Inter", sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #2c3e50;
    }
    .main {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    .btn {
      background: #0055aa;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
      border: none;
      cursor: pointer;
    }
    .btn.secondary {
      background: #7f8c8d;
    }
    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.8rem;
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 0.8rem 1.2rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.2rem;
    }
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 1.2rem;
    }
    .form-group {
      display: flex;
      flex-direction: column;
    }
    .form-group label {
      font-weight: 600;
      margin-bottom: 0.3rem;
      color: #2c3e50;
    }
    .form-group input,
    .form-group select {
      padding: 8px 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 0.95rem;
    }
    .form-group input:focus,
    .form-group select:focus {
      border-color: #0074e4;
      outline: none;
    }
    .form-actions {
      grid-column: 1 / -1;
      display: flex;
      justify-content: flex-end;
      gap: 0.8rem;
      margin-top: 1.2rem;
    }
    .successbox,
    .dangerbox {
      padding: 0.9rem 1.2rem;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }
    .successbox {
      background: #eaf8ee;
      border: 1px solid #27ae60;
      color: #2e7d32;
    }
    .dangerbox {
      background: #fcebea;
      border: 1px solid #e74c3c;
      color: #c0392b;
    }
    .dangerbox ul {
      margin: 0.5rem 0 0;
      padding-left: 1.2rem;
    }
    .foot {
      text-align: center;
      color: #888;
      margin-top: 2rem;
      font-size: 0.9rem;
    }
    .empty-state {
      padding: 1.5rem;
      text-align: center;
      color: #555;
      background: #fff8f6;
      border: 1px solid #f2d7d5;
      border-radius: 8px;
    }
  </style>
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
        <form method="post" class="form-grid">
          <input type="hidden" name="estudiante_id" value="<?= htmlspecialchars((string) ($estudianteId ?? '')) ?>">

          <div class="form-group">
            <label for="nombre">Nombre(s)</label>
            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($formData['nombre']) ?>" required>
          </div>

          <div class="form-group">
            <label for="apellido_paterno">Apellido paterno</label>
            <input type="text" id="apellido_paterno" name="apellido_paterno" value="<?= htmlspecialchars($formData['apellido_paterno']) ?>">
          </div>

          <div class="form-group">
            <label for="apellido_materno">Apellido materno</label>
            <input type="text" id="apellido_materno" name="apellido_materno" value="<?= htmlspecialchars($formData['apellido_materno']) ?>">
          </div>

          <div class="form-group">
            <label for="matricula">Matrícula</label>
            <input type="text" id="matricula" name="matricula" value="<?= htmlspecialchars($formData['matricula']) ?>" required>
          </div>

          <div class="form-group">
            <label for="carrera">Carrera</label>
            <input type="text" id="carrera" name="carrera" value="<?= htmlspecialchars($formData['carrera']) ?>">
          </div>

          <div class="form-group">
            <label for="correo_institucional">Correo institucional</label>
            <input type="email" id="correo_institucional" name="correo_institucional" value="<?= htmlspecialchars($formData['correo_institucional']) ?>">
          </div>

          <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($formData['telefono']) ?>">
          </div>

          <div class="form-group">
            <label for="empresa_id">Empresa</label>
            <select id="empresa_id" name="empresa_id" required <?= $empresas === [] ? 'disabled' : '' ?>>
              <option value="">Selecciona una empresa...</option>
              <?php foreach ($empresas as $empresa): ?>
                <option value="<?= htmlspecialchars($empresa['id']) ?>" <?= $formData['empresa_id'] === $empresa['id'] ? 'selected' : '' ?>>
                  <?= htmlspecialchars($empresa['nombre']) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="form-group">
            <label for="convenio_id">Convenio</label>
            <select id="convenio_id" name="convenio_id" <?= $convenios === [] ? 'disabled' : '' ?>>
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
            <button type="submit" class="btn primary" <?= $empresas === [] ? 'disabled' : '' ?>>💾 Guardar cambios</button>
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
