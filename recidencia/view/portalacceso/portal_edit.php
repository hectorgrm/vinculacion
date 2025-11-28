<?php
declare(strict_types=1);

// Datos de ejemplo (reemplazar por handler real)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 901;
$empresaId = 45;
$email = 'admin@casadelbarrio.mx';
$rol = 'empresa_admin';
$estatus = 'activo';
$tfa = 0;
$pwdExpira = '2026-01-31';
$reqChange = 0;
$notas = '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar acceso - Residencias</title>
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
          <p class="lead">Actualiza los datos del usuario y controla el estado de su acceso.</p>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="portal_list.php">Portal de Acceso</a><span>›</span>
            <a href="portal_view.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Ver</a><span>›</span>
            <span>Editar</span>
          </nav>
        </div>
        <div class="actions">
          <a class="btn" href="portal_view.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Ver</a>
          <a class="btn warn" href="portal_reset.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Reset contraseña</a>
          <a class="btn danger" href="portal_delete.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Eliminar</a>
        </div>
      </header>

      <section class="card">
        <header>Datos del acceso</header>
        <div class="content">
          <form class="form" action="portal_edit_action.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">
            <div class="form-grid">
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
                <input id="email" name="email" type="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>" required>
              </div>

              <div class="field">
                <label for="rol" class="required">Rol *</label>
                <select id="rol" name="rol" required>
                  <option value="empresa_admin" <?php echo $rol === 'empresa_admin' ? 'selected' : ''; ?>>empresa_admin</option>
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
                <input id="pwd_expires_at" name="pwd_expires_at" type="date" value="<?php echo htmlspecialchars($pwdExpira, ENT_QUOTES, 'UTF-8'); ?>">
              </div>

              <div class="field inline">
                <label>
                  <input type="checkbox" name="require_change" value="1" <?php echo $reqChange ? 'checked' : ''; ?>>
                  <span>Requerir cambio de contraseña al próximo inicio de sesión.</span>
                </label>
              </div>

              <div class="field" style="grid-column: span 2;">
                <label for="notas">Notas</label>
                <textarea id="notas" name="notas" rows="3" placeholder="Observaciones internas..."><?php echo htmlspecialchars($notas, ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>

            <div class="actions form-actions">
              <a class="btn secondary" href="portal_view.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Cancelar</a>
              <button class="btn primary" type="submit">Guardar cambios</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>

</html>
