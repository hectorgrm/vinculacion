<?php
declare(strict_types=1);

/**
 * @var array{
 *     portalId: ?int,
 *     portal: ?array<string, mixed>,
 *     empresa: ?array<string, mixed>,
 *     errors: array<int, string>
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/portalacceso/portal_view_handler.php';

$portalId = $handlerResult['portalId'];
$portal = $handlerResult['portal'];
$empresa = $handlerResult['empresa'];
$viewErrors = $handlerResult['errors'] ?? [];
$successMessage = $handlerResult['successMessage'] ?? null;
$actionError = $handlerResult['actionError'] ?? null;

$isValid = $portal !== null && !in_array('invalid_id', $viewErrors, true);

$token = $isValid && isset($portal['token']) ? (string) $portal['token'] : '';
$nip = $isValid && isset($portal['nip']) ? (string) $portal['nip'] : '';
$nipLabel = $isValid && isset($portal['nip_label']) ? (string) $portal['nip_label'] : ($nip !== '' ? $nip : 'No asignado');
$statusLabel = $isValid && isset($portal['status_label']) ? (string) $portal['status_label'] : 'Sin estatus';
$statusClass = $isValid && isset($portal['status_class']) ? (string) $portal['status_class'] : 'badge secondary';
$expiracionLabel = $isValid && isset($portal['expiracion_label']) ? (string) $portal['expiracion_label'] : 'Sin fecha';
$creadoEnLabel = $isValid && isset($portal['creado_en_label']) ? (string) $portal['creado_en_label'] : 'Sin fecha';
$activo = $isValid && isset($portal['activo']) ? (bool) $portal['activo'] : false;
$empresaId = is_array($empresa) && isset($empresa['id']) ? $empresa['id'] : null;
$empresaLabel = is_array($empresa) && isset($empresa['label']) && $empresa['label'] !== null
    ? (string) $empresa['label']
    : 'Sin empresa';
$expiracionValue = '';

if ($isValid && isset($portal['expiracion']) && $portal['expiracion'] !== null) {
    $timestamp = strtotime((string) $portal['expiracion']);
    $expiracionValue = $timestamp !== false ? date('Y-m-d\TH:i', $timestamp) : '';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Ver acceso - Portal Empresa</title>
  <link rel="stylesheet" href="../../assets/css/modules/portalacceso/portalview.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <p class="eyebrow">Portal de acceso</p>
          <h2>Detalle de acceso</h2>
          <p class="lead">Consulta el token, NIP y estatus del portal asignado a la empresa correspondiente.</p>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>&gt;</span>
            <a href="portal_list.php">Portal</a>
            <span>&gt;</span>
            <span>Ver</span>
          </nav>
        </div>

        <div class="actions">
          <a class="btn secondary" href="portal_list.php">Volver a la lista</a>
        </div>
      </header>

      <?php if ($successMessage !== null || $actionError !== null || $viewErrors !== []): ?>
        <div class="message-stack">
          <?php if ($successMessage !== null): ?>
            <div class="alert success"><?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?></div>
          <?php endif; ?>

          <?php if ($actionError !== null): ?>
            <div class="alert error"><?php echo htmlspecialchars($actionError, ENT_QUOTES, 'UTF-8'); ?></div>
          <?php endif; ?>

          <?php if (in_array('invalid_id', $viewErrors, true)): ?>
            <div class="alert alert-error">El identificador proporcionado no es v&aacute;lido.</div>
          <?php endif; ?>

          <?php if (in_array('database_error', $viewErrors, true)): ?>
            <div class="alert alert-error">Ocurri&oacute; un problema al consultar la base de datos. Intenta nuevamente m&aacute;s tarde.</div>
          <?php endif; ?>

          <?php if (in_array('not_found', $viewErrors, true) && !in_array('invalid_id', $viewErrors, true)): ?>
            <div class="alert alert-warning">No se encontr&oacute; un acceso al portal con el identificador solicitado.</div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($isValid): ?>
        <section class="card">
          <header>
            <span>Datos del acceso</span>
            <span class="<?php echo htmlspecialchars($statusClass, ENT_QUOTES, 'UTF-8'); ?>">
              <?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?>
            </span>
          </header>

          <div class="content">
            <form class="form" action="portal_view_action.php?id=<?php echo urlencode((string) $portalId); ?>" method="post" autocomplete="off">
              <div class="form-grid">
                <div class="field">
                  <label>Empresa</label>
                  <div>
                    <?php if ($empresaId !== null): ?>
                      <a class="btn secondary" href="../empresa/empresa_view.php?id=<?php echo urlencode((string) $empresaId); ?>">
                        <?php echo htmlspecialchars($empresaLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </a>
                    <?php else: ?>
                      <?php echo htmlspecialchars($empresaLabel, ENT_QUOTES, 'UTF-8'); ?>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="field">
                  <label>Token</label>
                  <input type="text" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>" readonly>
                </div>

                <div class="field">
                  <label for="nip">NIP</label>
                  <input id="nip" name="nip" type="text" pattern="[0-9]{4,6}" maxlength="6"
                    value="<?php echo htmlspecialchars($nip, ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="Ingresa un NIP nuevo (4-6 dígitos)">
                </div>

                <div class="field">
                  <label for="expiracion">Expiraci&oacute;n</label>
                  <input id="expiracion" name="expiracion" type="datetime-local"
                    value="<?php echo htmlspecialchars($expiracionValue, ENT_QUOTES, 'UTF-8'); ?>">
                  <div class="hint">Usa esta fecha para extender o limitar la vigencia.</div>
                </div>

                <div class="switch-row">
                  <div>
                    <strong>Acceso activo</strong><br>
                    <span>Desact&iacute;valo para bloquear el ingreso inmediatamente.</span>
                  </div>
                  <label class="switch">
                    <input type="checkbox" name="activo" <?php echo $activo ? 'checked' : ''; ?>>
                    <span class="slider"></span>
                  </label>
                </div>

                <div style="grid-column: span 2; display:flex; gap:10px; flex-wrap:wrap;">
                  <button name="action" value="regenerate" class="btn outline">🔄 Regenerar token</button>
                  <button name="action" value="save" class="btn primary">Guardar cambios</button>
                </div>
              </div>
            </form>

            <div class="info-grid">
              <div class="info-item">
                <label>Expiraci&oacute;n actual</label>
                <div><?php echo htmlspecialchars($expiracionLabel, ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
              <div class="info-item">
                <label>Creado en</label>
                <div><?php echo htmlspecialchars($creadoEnLabel, ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
              <div class="info-item">
                <label>ID de acceso</label>
                <div>#<?php echo htmlspecialchars((string) $portalId, ENT_QUOTES, 'UTF-8'); ?></div>
              </div>
            </div>

            <form action="portal_view_action.php?id=<?php echo urlencode((string) $portalId); ?>" method="post" onsubmit="return confirm('¿Seguro que deseas eliminar este acceso?');">
              <input type="hidden" name="action" value="delete">
              <div class="actions tight" style="justify-content:flex-end; margin-top:12px;">
                <a class="btn secondary" href="portal_list.php">Regresar</a>
                <button class="btn danger" type="submit">Eliminar acceso</button>
              </div>
            </form>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
</html>
