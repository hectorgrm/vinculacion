<?php

declare(strict_types=1);

/**
 * @var array{
 *     estudianteId: ?int,
 *     estudiante: ?array<string, mixed>,
 *     empresa: ?array<string, mixed>,
 *     convenio: ?array<string, mixed>,
 *     errors: array<int, string>,
 *     success: bool,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     loadError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/estudiante/estudiante_deactivate_handler.php';

$estudiante = is_array($handlerResult['estudiante']) ? $handlerResult['estudiante'] : [];
$empresa = is_array($handlerResult['empresa']) ? $handlerResult['empresa'] : null;
$convenio = is_array($handlerResult['convenio']) ? $handlerResult['convenio'] : null;
$errors = $handlerResult['errors'];
$success = $handlerResult['success'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$loadError = $handlerResult['loadError'];
$estudianteId = $handlerResult['estudianteId'];

$nombreCompleto = estudianteDeactivateFormatNombreCompleto($estudiante);
$estatus = isset($estudiante['estatus']) ? (string) $estudiante['estatus'] : '';
$estatusNormalizado = strtolower($estatus);

$badgeClass = 'badge inactivo';
if ($estatusNormalizado === 'activo') {
    $badgeClass = 'badge activo';
} elseif ($estatusNormalizado === 'finalizado') {
    $badgeClass = 'badge finalizado';
}

$canSubmit = $controllerError === null
    && $loadError === null
    && !$success
    && $estatusNormalizado !== 'inactivo';

$returnUrl = $estudianteId !== null
    ? 'estudiante_view.php?id=' . rawurlencode((string) $estudianteId)
    : 'estudiante_list.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Desactivar Estudiante · Residencia Profesional</title>

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
      max-width: 650px;
      margin: 4rem auto;
      padding: 0 1.5rem;
    }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 3px 8px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 1rem 1.5rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.5rem;
      text-align: center;
    }
    .warning-icon {
      font-size: 3rem;
      color: #e74c3c;
      margin-bottom: 1rem;
    }
    .alert-title {
      font-weight: 700;
      color: #c0392b;
      font-size: 1.3rem;
      margin-bottom: 0.5rem;
    }
    .alert-text {
      color: #555;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }
    .data-box {
      background: #fafafa;
      border: 1px solid #e1e1e1;
      border-radius: 6px;
      padding: 0.8rem 1.2rem;
      text-align: left;
      font-size: 0.95rem;
      margin-bottom: 1.5rem;
    }
    .data-box p {
      margin: 0.3rem 0;
    }
    .badge {
      padding: 4px 8px;
      border-radius: 6px;
      color: #fff;
      font-size: 0.85rem;
    }
    .badge.activo { background: #27ae60; }
    .badge.finalizado { background: #2980b9; }
    .badge.inactivo { background: #c0392b; }
    .actions {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
      flex-wrap: wrap;
    }
    .btn {
      padding: 0.6rem 1.4rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.95rem;
      font-weight: 600;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
    }
    .btn.danger {
      background: #e74c3c;
      color: #fff;
    }
    .btn.danger:hover {
      background: #c0392b;
    }
    .btn.secondary {
      background: #95a5a6;
      color: #fff;
    }
    .btn.secondary:hover {
      background: #7f8c8d;
    }
    .btn:disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }
    .foot {
      text-align: center;
      color: #888;
      margin-top: 2rem;
      font-size: 0.9rem;
    }
    .message {
      margin-bottom: 1rem;
      padding: 0.9rem 1rem;
      border-radius: 6px;
      text-align: left;
      font-size: 0.95rem;
    }
    .message.success {
      background: #eaf8ee;
      border: 1px solid #27ae60;
      color: #2e7d32;
    }
    .message.error {
      background: #fcebea;
      border: 1px solid #e74c3c;
      color: #c0392b;
    }
    .message ul {
      margin: 0.6rem 0 0;
      padding-left: 1.2rem;
    }
  </style>
</head>

<body>
 <div class="app">
         <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

  <main class="main">
    <section class="card">
      <header>🗑️ Confirmar desactivación</header>
      <div class="content">
        <div class="warning-icon">⚠️</div>
        <p class="alert-title">¿Estás seguro de desactivar este estudiante?</p>
        <p class="alert-text">
          Esta acción no elimina registros, pero cambiará el estatus a <strong>Inactivo</strong> y el estudiante dejará de participar en los procesos activos.
        </p>

        <?php if ($errors !== []): ?>
          <div class="message error">
            <strong>Se encontraron los siguientes problemas:</strong>
            <ul>
              <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <?php if ($success && $successMessage !== null): ?>
          <div class="message success">
            <?= htmlspecialchars($successMessage) ?>
          </div>
        <?php endif; ?>

        <?php if ($controllerError !== null && !in_array($controllerError, $errors, true)): ?>
          <div class="message error">
            <?= htmlspecialchars($controllerError) ?>
          </div>
        <?php endif; ?>

        <?php if ($loadError !== null && !in_array($loadError, $errors, true)): ?>
          <div class="message error">
            <?= htmlspecialchars($loadError) ?>
          </div>
        <?php endif; ?>

        <div class="data-box">
          <p><strong>Nombre:</strong> <?= $nombreCompleto !== '' ? htmlspecialchars($nombreCompleto) : 'Sin nombre registrado' ?></p>
          <p><strong>Matrícula:</strong> <?= isset($estudiante['matricula']) && $estudiante['matricula'] !== '' ? htmlspecialchars((string) $estudiante['matricula']) : 'Sin matrícula' ?></p>
          <p><strong>Carrera:</strong> <?= isset($estudiante['carrera']) && $estudiante['carrera'] !== '' ? htmlspecialchars((string) $estudiante['carrera']) : 'Sin carrera registrada' ?></p>
          <p><strong>Empresa:</strong> <?= $empresa !== null && isset($empresa['nombre']) && $empresa['nombre'] !== '' ? htmlspecialchars((string) $empresa['nombre']) : 'Sin empresa asignada' ?></p>
          <p><strong>Convenio:</strong>
            <?php if ($convenio !== null): ?>
              <?= isset($convenio['folio']) && $convenio['folio'] !== '' ? htmlspecialchars((string) $convenio['folio']) : 'Convenio #' . htmlspecialchars((string) $convenio['id']) ?>
              <?php if (isset($convenio['estatus']) && $convenio['estatus'] !== ''): ?>
                · <span><?= htmlspecialchars((string) $convenio['estatus']) ?></span>
              <?php endif; ?>
            <?php else: ?>
              Sin convenio asignado
            <?php endif; ?>
          </p>
          <p><strong>Estatus:</strong> <span class="<?= htmlspecialchars($badgeClass) ?>"><?= $estatus !== '' ? htmlspecialchars($estatus) : 'Desconocido' ?></span></p>
        </div>

        <div class="actions">
          <form method="post" action="<?= htmlspecialchars('estudiante_deactivate.php' . ($estudianteId !== null ? '?id=' . rawurlencode((string) $estudianteId) : '')) ?>">
            <input type="hidden" name="id" value="<?= $estudianteId !== null ? htmlspecialchars((string) $estudianteId) : '' ?>" />
            <button class="btn danger" type="submit" name="confirm" value="1" <?= $canSubmit ? '' : 'disabled' ?>>✅ Sí, desactivar</button>
          </form>
          <a href="<?= htmlspecialchars($returnUrl) ?>" class="btn secondary">Cancelar</a>
        </div>
      </div>
    </section>

    <p class="foot">Área de Vinculación · Residencias Profesionales</p>
  </main>
</div>
</body>
</html>
