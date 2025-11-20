<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     empresaOptions: array<int, array<string, string>>,
 *     errors: array<int, string>,
 *     successMessage: ?string,
 *     controllerError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/portalacceso/portal_add_handler.php';

$formData = $handlerResult['formData'];
$empresaOptions = $handlerResult['empresaOptions'];
$errors = $handlerResult['errors'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Crear Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/css/portalacceso/portaladd.css"/>

  <!-- (Opcional) Mini estilos locales -->
  <style>
    .hint{ color:#64748b; font-size:13px; margin-top:6px }
    .grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px }
    .col-span-2{ grid-column:1/3 }
    .field label{ display:block; font-weight:700; color:#334155; margin-bottom:6px }
    .field label.required::after{ content:" *"; color:#e44848; font-weight:800 }
    .actions{ display:flex; gap:10px; justify-content:flex-end; margin-top:12px }
    .inline{ display:flex; gap:10px; align-items:center; flex-wrap:wrap }
    @media (max-width: 860px){ .grid{ grid-template-columns:1fr } .col-span-2{ grid-column:1/2 } }
  </style>
</head>
<body>
 <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>➕ Crear acceso para empresa</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="portal_list.php">Portal de Acceso</a>
            <span>›</span>
            <span>Nuevo</span>
          </nav>
        </div>
        <a class="btn" href="portal_list.php">⬅ Volver</a>
      </header>

      <section class="card">
        <header>🧾 Datos del acceso</header>
        <div class="content">
          <?php if ($controllerError !== null) : ?>
            <div class="alert alert-danger" style="margin-bottom:16px;">
              <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($successMessage !== null) : ?>
            <div class="alert alert-success" style="margin-bottom:16px;">
              <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
            </div>
          <?php endif; ?>

          <?php if ($errors !== []) : ?>
            <div class="alert alert-danger" style="margin-bottom:16px;">
              <p style="margin:0 0 8px 0; font-weight:600;">Por favor corrige los siguientes errores:</p>
              <ul style="margin:0 0 0 18px; padding:0;">
                <?php foreach ($errors as $error) : ?>
                  <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form class="form" action="" method="post" autocomplete="off">
            <div class="grid">

              <!-- Empresa -->
              <div class="field">
                <label for="empresa_id" class="required">Empresa *</label>
                <select id="empresa_id" name="empresa_id" required <?php echo $empresaOptions === [] ? 'disabled' : ''; ?>>
                  <option value="">— Selecciona una empresa —</option>
                  <?php foreach ($empresaOptions as $empresa) : ?>
                    <?php
                      $value = htmlspecialchars($empresa['id'], ENT_QUOTES, 'UTF-8');
                      $numeroControl = trim($empresa['numero_control']) !== ''
                        ? ' · ' . htmlspecialchars($empresa['numero_control'], ENT_QUOTES, 'UTF-8')
                        : '';
                      $label = htmlspecialchars($empresa['nombre'], ENT_QUOTES, 'UTF-8') . $numeroControl;
                      $selected = $formData['empresa_id'] === (string) $empresa['id'] ? 'selected' : '';
                    ?>
                    <option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
                  <?php endforeach; ?>
                </select>
                <?php if ($empresaOptions === []) : ?>
                  <div class="hint">Registra al menos una empresa antes de crear accesos.</div>
                <?php endif; ?>
              </div>

              <!-- Token -->
              <div class="field">
                <label for="token" class="required">Token *</label>
                <div style="display:flex; gap:10px;">
                  <input id="token" name="token" type="text" placeholder="Generar token" readonly required style="flex:1;" value="<?php echo htmlspecialchars($formData['token'], ENT_QUOTES, 'UTF-8'); ?>">
                  <button type="button" class="btn" onclick="genToken()">🔑 Generar</button>
                </div>
                <div class="hint">Identificador único que se usará en la URL de acceso.</div>
              </div>

              <!-- NIP -->
              <div class="field">
                <label for="nip" class="required">NIP *</label>
                <input id="nip" name="nip" type="text" maxlength="6" placeholder="Ej: 4567" required pattern="[0-9]{4,6}" value="<?php echo htmlspecialchars($formData['nip'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="hint">Código corto (4–6 dígitos) que la empresa deberá ingresar.</div>
              </div>

              <!-- Activo -->
              <div class="field">
                <label for="activo" class="required">Estatus *</label>
                <select id="activo" name="activo" required>
                  <option value="1" <?php echo $formData['activo'] === '1' ? 'selected' : ''; ?>>Activo</option>
                  <option value="0" <?php echo $formData['activo'] === '0' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
              </div>

              <!-- Expiración -->
              <div class="field">
                <label for="expiracion">Expiración (opcional)</label>
                <input id="expiracion" name="expiracion" type="datetime-local" value="<?php echo htmlspecialchars($formData['expiracion'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="hint">Define una fecha de vencimiento del acceso si se desea limitar su duración.</div>
              </div>

            </div>

            <div class="actions">
              <a class="btn" href="portal_list.php">Cancelar</a>
              <button class="btn primary" type="submit" <?php echo $empresaOptions === [] ? 'disabled' : ''; ?>>💾 Crear acceso</button>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content" style="display:flex; gap:8px; flex-wrap:wrap;">
          <a class="btn" href="../empresa/empresa_list.php">🏢 Empresas</a>
          <a class="btn" href="portal_list.php">🔐 Portal de Acceso</a>
        </div>
      </section>
    </main>
  </div>
  <script>
    function genToken(){
      const uuid = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
      );
      const tokenInput = document.getElementById('token');
      if(tokenInput){
        tokenInput.value = uuid;
      }
    }

    document.addEventListener('DOMContentLoaded', function(){
      const tokenInput = document.getElementById('token');
      if(tokenInput && tokenInput.value.trim() === ''){
        genToken();
      }
    });
  </script>
</body>
</html>
