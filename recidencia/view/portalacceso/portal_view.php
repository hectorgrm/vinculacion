<?php
declare(strict_types=1);

// Datos de ejemplo (sustituir por datos reales del handler)
$id           = $_GET['id'] ?? 901;
$empresaId    = 45;
$empresa      = "Casa del Barrio";
$email        = "admin@casadelbarrio.mx";
$rol          = "empresa_admin";
$estatus      = "activo";        // activo | bloqueado | inactivo
$tfa          = 0;               // 1 = sí, 0 = no
$ultimoAcceso = "2025-10-01 11:23";
$pwdExpira    = "2026-01-31";
$intentos     = 0;
$notas        = "Acceso principal de la empresa para revisión de convenios y documentos.";
$creado       = "2025-09-15 09:02";
$actualizado  = "2025-09-30 14:41";

$badge = '<span class="badge err">Desconocido</span>';
if ($estatus === 'activo') {
  $badge = '<span class="badge ok">Activo</span>';
} elseif ($estatus === 'bloqueado') {
  $badge = '<span class="badge warn">Bloqueado</span>';
} elseif ($estatus === 'inactivo') {
  $badge = '<span class="badge err">Inactivo</span>';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Acceso - Residencias Profesionales</title>

  <link rel="stylesheet" href="../../assets/css/modules/portalacceso/portalview.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div class="page-titles">
          <p class="eyebrow">Portal de acceso</p>
          <h2>Detalle del Acceso</h2>
          <p class="lead">Consulta la información del usuario de acceso y gestiona su estado.</p>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="portal_list.php">Portal de Acceso</a>
            <span>›</span>
            <span>Ver</span>
          </nav>
        </div>
        <div class="actions">
          <a class="btn" href="portal_edit.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Editar</a>
          <a class="btn warn" href="portal_reset.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Reset contraseña</a>
          <?php if ($estatus === 'activo'): ?>
            <a class="btn warn" href="portal_toggle.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>&to=bloquear">Bloquear</a>
          <?php elseif ($estatus === 'bloqueado'): ?>
            <a class="btn" href="portal_toggle.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>&to=activar">Activar</a>
          <?php endif; ?>
          <a class="btn danger" href="portal_delete.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Eliminar</a>
        </div>
      </header>

      <section class="card">
        <header>Información del Acceso</header>
        <div class="content">
          <div class="info-grid">
            <div class="info-item emphasize">
              <label>Empresa</label>
              <a class="btn" href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($empresa, ENT_QUOTES, 'UTF-8'); ?>
              </a>
            </div>

            <div class="info-item">
              <label>Correo (usuario)</label>
              <div><?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="info-item">
              <label>Rol</label>
              <div><?php echo htmlspecialchars($rol, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="info-item">
              <label>Estatus</label>
              <div><?php echo $badge; ?></div>
            </div>

            <div class="info-item">
              <label>Autenticación 2FA</label>
              <div><?php echo $tfa ? 'Habilitado' : 'Deshabilitado'; ?></div>
            </div>

            <div class="info-item">
              <label>Último acceso</label>
              <div><?php echo $ultimoAcceso !== '' ? htmlspecialchars($ultimoAcceso, ENT_QUOTES, 'UTF-8') : '<em>Sin registro</em>'; ?></div>
            </div>

            <div class="info-item">
              <label>Intentos fallidos</label>
              <div><?php echo htmlspecialchars((string) $intentos, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="info-item">
              <label>Expiración de contraseña</label>
              <div><?php echo $pwdExpira !== '' ? htmlspecialchars($pwdExpira, ENT_QUOTES, 'UTF-8') : '<em>No establecido</em>'; ?></div>
            </div>

            <div class="info-item">
              <label>Fecha de creación</label>
              <div><?php echo htmlspecialchars($creado, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="info-item">
              <label>Última actualización</label>
              <div><?php echo htmlspecialchars($actualizado, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>

            <div class="info-item" style="grid-column: span 2;">
              <label>Notas</label>
              <div><?php echo $notas !== '' ? htmlspecialchars($notas, ENT_QUOTES, 'UTF-8') : '<em>Sin notas registradas</em>'; ?></div>
            </div>
          </div>
        </div>
      </section>

      <section class="card">
        <header>Accesos rápidos</header>
        <div class="content actions">
          <a class="btn" href="../empresa/empresa_view.php?id=<?php echo htmlspecialchars((string) $empresaId, ENT_QUOTES, 'UTF-8'); ?>">Ver empresa</a>
          <a class="btn" href="portal_edit.php?id=<?php echo htmlspecialchars((string) $id, ENT_QUOTES, 'UTF-8'); ?>">Editar</a>
          <a class="btn" href="portal_list.php">Volver al listado</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
