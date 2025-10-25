<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_delete.php';

$requestedId = 0;
if (isset($_GET['id'])) {
    $filteredId = preg_replace('/[^0-9]/', '', (string) $_GET['id']);
    if ($filteredId !== null && $filteredId !== '') {
        $requestedId = (int) $filteredId;
    }
}

$requestedEmpresaId = null;
if (isset($_GET['empresa_id'])) {
    $filteredEmpresaId = preg_replace('/[^0-9]/', '', (string) $_GET['empresa_id']);
    if ($filteredEmpresaId !== null && $filteredEmpresaId !== '') {
        $requestedEmpresaId = (int) $filteredEmpresaId;
    }
}

$controllerData = convenioResolveControllerData();
$controller = $controllerData['controller'];
$controllerError = $controllerData['error'];

$errors = [];
$successMessage = null;
$sanitizedPost = null;
$convenio = null;

if ($controller !== null && $requestedId > 0) {
    try {
        $convenio = $controller->getConvenioById($requestedId);
        if ($convenio === null) {
            $errors[] = 'No se encontró el convenio solicitado.';
        }
    } catch (\RuntimeException $runtimeException) {
        $errors[] = $runtimeException->getMessage();
    }
}

if ($controller !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $handleResult = convenioHandleDeleteRequest($controller, $_POST);
    $sanitizedPost = $handleResult['sanitized'];
    $errors = array_merge($errors, $handleResult['errors']);
    $successMessage = $handleResult['successMessage'];

    if ($handleResult['convenioId'] > 0) {
        $requestedId = $handleResult['convenioId'];
    }

    if ($successMessage !== null && $requestedId > 0) {
        try {
            $refreshed = $controller->getConvenioById($requestedId);
            if ($refreshed !== null) {
                $convenio = $refreshed;
            }
        } catch (\RuntimeException $runtimeException) {
            $errors[] = $runtimeException->getMessage();
        }
    }
} elseif ($controller === null && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors[] = $controllerError ?? 'No se pudo procesar la solicitud.';
}

$convenioIdDisplay = $convenio !== null && isset($convenio['id'])
    ? (string) $convenio['id']
    : ($requestedId > 0 ? (string) $requestedId : '');

$empresaIdDisplay = '';
if ($convenio !== null && isset($convenio['empresa_id'])) {
    $empresaIdDisplay = (string) $convenio['empresa_id'];
} elseif ($sanitizedPost !== null && $sanitizedPost['empresa_id'] !== null) {
    $empresaIdDisplay = (string) $sanitizedPost['empresa_id'];
} elseif ($requestedEmpresaId !== null) {
    $empresaIdDisplay = (string) $requestedEmpresaId;
}

$empresaNombreDisplay = '';
if ($convenio !== null && isset($convenio['empresa_nombre'])) {
    $empresaNombreDisplay = (string) $convenio['empresa_nombre'];
}

$motivoValue = '';
if ($sanitizedPost !== null && $successMessage === null && $sanitizedPost['motivo'] !== null) {
    $motivoValue = (string) $sanitizedPost['motivo'];
}

$confirmChecked = $sanitizedPost !== null && $sanitizedPost['confirmado'] === true;

$isAlreadyInactive = $convenio !== null
    && isset($convenio['estatus'])
    && (string) $convenio['estatus'] === 'Inactiva';

$formDisabled = $successMessage !== null || $isAlreadyInactive || $controller === null;
?>
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
          <?php if ($convenioIdDisplay !== ''): ?>
          <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>" class="btn">👁️ Ver</a>
          <?php endif; ?>
          <a href="convenio_list.php" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <?php if ($controllerError !== null): ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($errors !== []): ?>
          <div class="alert alert-danger" style="margin-bottom:16px;">
            <ul style="margin:0; padding-left:18px;">
              <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>

          <?php if ($successMessage !== null): ?>
          <div class="alert alert-success" style="margin-bottom:16px;">
            <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
          </div>
          <?php endif; ?>

          <?php if ($isAlreadyInactive && $successMessage === null): ?>
          <p class="text-muted" style="margin-bottom:16px;">
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
              <a class="btn" href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">🏢 Ver empresa</a>.
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

            <!-- Accesos rápidos a secciones relacionadas -->
            <div class="links-inline" style="margin-top:10px;">
              <?php if ($empresaIdDisplay !== '' && $convenioIdDisplay !== ''): ?>
              <a class="btn" href="../documentos/documento_list.php?empresa=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>&convenio=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">📂 Ver documentos</a>
              <a class="btn" href="../machote/revisar.php?id_empresa=<?php echo htmlspecialchars($empresaIdDisplay, ENT_QUOTES, 'UTF-8'); ?>&convenio=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>">📝 Revisar machote</a>
              <?php endif; ?>
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

          <form action="" method="post" style="margin-top:18px;">
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
              <a href="convenio_view.php?id=<?php echo htmlspecialchars($convenioIdDisplay, ENT_QUOTES, 'UTF-8'); ?>" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger" <?php echo $formDisabled ? 'disabled' : ''; ?>>🚫 Desactivar Convenio</button>
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
