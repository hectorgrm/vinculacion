<?php
declare(strict_types=1);

// Datos de ejemplo (reemplazar con handler real)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 901;

$portal = [
  'empresa_id' => 45,
  'token' => 'ab1f34da-9c33-4b18-b55c-8370ceea0077',
  'nip' => '482913',
  'expiracion' => '2025-12-31 23:59:00',
  'activo' => 1
];

$empresaId = $portal['empresa_id'];
$token = $portal['token'];
$nip = $portal['nip'];
$expira = $portal['expiracion'];
$activo = $portal['activo'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar acceso - Portal Empresa</title>
  <link rel="stylesheet" href="../../assets/css/modules/portalacceso/portaledit.css" />

</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">

      <header class="topbar">
        <div class="page-titles">
          <p class="eyebrow">Portal de acceso</p>
          <h2>Editar acceso</h2>

          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a> ›
            <a href="portal_list.php">Portal</a> ›
            <span>Editar</span>
          </nav>
        </div>

        <div class="actions">
          <a class="btn" href="portal_view.php?id=<?= $id ?>">Ver</a>
          <a class="btn danger" href="portal_delete.php?id=<?= $id ?>">Eliminar</a>
        </div>
      </header>

      <section class="card">
        <header><h3>Datos del acceso</h3></header>
        <div class="content">

          <form class="form" action="portal_edit_action.php?id=<?= $id ?>" method="post">

            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="form-grid">

              <!-- Empresa -->
              <div class="field">
                <label for="empresa_id">Empresa *</label>
                <select id="empresa_id" name="empresa_id" required>
                  <option value="">— Selecciona —</option>
                  <option value="45" <?= $empresaId == 45 ? 'selected' : '' ?>>Casa del Barrio</option>
                  <option value="22">Tequila ECT</option>
                  <option value="31">Industrias Yakumo</option>
                </select>
              </div>

              <!-- Token -->
              <div class="field">
                <label>Token (solo lectura)</label>
                <input type="text" value="<?= htmlspecialchars($token) ?>" readonly>
              </div>

              <!-- NIP -->
              <div class="field">
                <label>NIP (solo lectura)</label>
                <input type="text" value="<?= htmlspecialchars($nip) ?>" readonly>
              </div>

              <!-- Expiración -->
              <div class="field">
                <label>Expiración *</label>
                <input type="datetime-local" name="expiracion"
                  value="<?= date('Y-m-d\TH:i', strtotime($expira)) ?>">
              </div>

              <!-- Switch activo -->
              <div class="switch-row">
                <div>
                  <strong>Acceso Activo:</strong><br>
                  <span>Si lo desactivas, no podrá entrar aunque tenga token y NIP.</span>
                </div>

                <label class="switch">
                  <input type="checkbox" name="activo" <?= $activo ? 'checked' : '' ?>>
                  <span class="slider"></span>
                </label>
              </div>

              <!-- Regenerar -->
              <div style="grid-column: span 2;">
                <button name="action" value="regenerate" class="btn outline">🔄 Regenerar token y NIP</button>
              </div>

            </div>

            <div class="actions form-actions">
              <a class="btn secondary" href="portal_view.php?id=<?= $id ?>">Cancelar</a>
              <button class="btn primary" type="submit" name="action" value="save">Guardar cambios</button>
            </div>

          </form>

        </div>
      </section>

    </main>
  </div>
</body>
</html>
