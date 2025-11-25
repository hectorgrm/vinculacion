<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Acceso · Residencias</title>
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/portalacceso.css" />
</head>

<body>
  <?php
  $id = isset($_GET['id']) ? (int) $_GET['id'] : 901;
  // Demostración (reemplazar con DB)
  $empresaId = 45;
  $email = 'admin@casadelbarrio.mx';
  $rol = 'empresa_admin';
  $estatus = 'activo';
  $tfa = 0;
  $pwdExpira = '2026-01-31';
  $reqChange = 0;
  $notas = '';
  ?>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    <main class="main">
      <header class="topbar">
        <div>
          <h2>✏️ Editar acceso</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="portal_list.php">Portal de Acceso</a><span>›</span>
            <a href="portal_view.php?id=<?php echo $id; ?>">Ver</a><span>›</span>
            <span>Editar</span>
          </nav>
        </div>
        <div class="actions" style="gap:8px;">
          <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">👁️ Ver</a>
          <a class="btn" href="portal_reset.php?id=<?php echo $id; ?>">🔁 Reset contraseña</a>
          <a class="btn danger" href="portal_delete.php?id=<?php echo $id; ?>">🗑️ Eliminar</a>
        </div>
      </header>

      <section class="card">
        <header>🧾 Datos del acceso</header>
        <div class="content">
          <form class="form" action="portal_edit_action.php?id=<?php echo $id; ?>" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="grid">
              <div class="field">
                <label for="empresa_id" class="required">Empresa *</label>
                <select id="empresa_id" name="empresa_id" required>
                  <option value="">— Selecciona una empresa —</option>
                  <option value="45" <?php echo $empresaId == 45 ? 'selected' : ''; ?>>Casa del Barrio</option>
                  <option value="22">Tequila ECT</option>
                  <option value="31">Industrias Yakumo</option>
                </select>
              </div>

              <div class="field">
                <label for="email" class="required">Correo (usuario) *</label>
                <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email); ?>" required>
              </div>

              <div class="field">
                <label for="rol" class="required">Rol *</label>
                <select id="rol" name="rol" required>
                  <option value="empresa_admin" <?php echo $rol === 'empresa_admin' ? 'selected' : ''; ?>>empresa_admin
                  </option>
                  <option value="empresa_user" <?php echo $rol === 'empresa_user' ? 'selected' : ''; ?>>empresa_user</option>
                </select>
              </div>

              <div class="field">
                <label for="estatus" class="required">Estatus *</label>
                <select id="estatus" name="estatus" required>
                  <option value="activo" <?php echo $estatus === 'activo' ? 'selected' : ''; ?>>Activo</option>
                  <option value="inactivo" <?php echo $estatus === 'inactivo' ? 'selected' : ''; ?>>Inactivo</option>
                  <option value="bloqueado" <?php echo $estatus === 'bloqueado' ? 'selected' : ''; ?>>Bloqueado</option>
                </select>
              </div>

              <div class="field">
                <label for="tfa" class="required">Autenticación 2FA *</label>
                <select id="tfa" name="tfa" required>
                  <option value="0" <?php echo !$tfa ? 'selected' : ''; ?>>Deshabilitado</option>
                  <option value="1" <?php echo $tfa ? 'selected' : ''; ?>>Habilitado</option>
                </select>
              </div>

              <div class="field">
                <label for="pwd_expires_at">Vencimiento de contraseña</label>
                <input id="pwd_expires_at" name="pwd_expires_at" type="date"
                  value="<?php echo htmlspecialchars($pwdExpira); ?>">
              </div>

              <div class="field">
                <label style="display:flex; gap:10px; align-items:flex-start;">
                  <input type="checkbox" name="require_change" value="1" <?php echo $reqChange ? 'checked' : ''; ?>>
                  <span>Requerir cambio de contraseña al próximo inicio de sesión.</span>
                </label>
              </div>

              <div class="field col-span-2">
                <label for="notas">Notas</label>
                <textarea id="notas" name="notas" rows="3"
                  placeholder="Observaciones internas…"><?php echo htmlspecialchars($notas); ?></textarea>
              </div>
            </div>

            <div class="actions">
              <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">Cancelar</a>
              <button class="btn primary" type="submit">💾 Guardar cambios</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>