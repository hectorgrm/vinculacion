<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Acceso · Residencias Profesionales</title>

  <!-- Estilos globales -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />

  <!-- Estilos locales -->
  <style>
    .info-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px 16px;
    }
    .info-item label {
      font-weight: 700;
      color: #334155;
      display: block;
      margin-bottom: 4px;
    }
    .badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 700;
      color: #fff;
      line-height: 1;
    }
    .badge.ok { background: #2db980; }
    .badge.warn { background: #ffb400; }
    .badge.err { background: #e44848; }
    .badge.secondary { background: #64748b; }
    .actions { display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
    @media (max-width: 860px) {
      .info-grid { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>
<?php
// === Datos de ejemplo (simular datos reales desde BD) ===
$id           = $_GET['id'] ?? 901;
$empresaId    = 45;
$empresa      = "Casa del Barrio";
$email        = "admin@casadelbarrio.mx";
$rol          = "empresa_admin";
$estatus      = "activo";        // valores: activo | bloqueado | inactivo
$tfa          = 0;               // 1 = sí, 0 = no
$ultimoAcceso = "2025-10-01 11:23";
$pwdExpira    = "2026-01-31";
$intentos     = 0;
$notas        = "Acceso principal de la empresa para revisión de convenios y documentos.";
$creado       = "2025-09-15 09:02";
$actualizado  = "2025-09-30 14:41";

// Badge dinámico
switch ($estatus) {
  case 'activo': $badge = '<span class="badge ok">Activo</span>'; break;
  case 'bloqueado': $badge = '<span class="badge warn">Bloqueado</span>'; break;
  case 'inactivo': $badge = '<span class="badge secondary">Inactivo</span>'; break;
  default: $badge = '<span class="badge err">Desconocido</span>';
}
?>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>👁️ Detalle del Acceso</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="portal_list.php">Portal de Acceso</a>
            <span>›</span>
            <span>Ver</span>
          </nav>
        </div>

        <div class="actions">
          <a class="btn" href="portal_edit.php?id=<?php echo $id; ?>">✏️ Editar</a>
          <a class="btn" href="portal_reset.php?id=<?php echo $id; ?>">🔁 Reset contraseña</a>
          <?php if ($estatus === 'activo'): ?>
            <a class="btn" href="portal_toggle.php?id=<?php echo $id; ?>&to=bloquear">🚫 Bloquear</a>
          <?php elseif ($estatus === 'bloqueado'): ?>
            <a class="btn" href="portal_toggle.php?id=<?php echo $id; ?>&to=activar">✅ Activar</a>
          <?php endif; ?>
          <a class="btn danger" href="portal_delete.php?id=<?php echo $id; ?>">🗑️ Eliminar</a>
        </div>
      </header>

      <!-- Información principal -->
      <section class="card">
        <header>🧾 Información del Acceso</header>
        <div class="content">
          <div class="info-grid">
            <div class="info-item">
              <label>Empresa</label>
              <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 <?php echo $empresa; ?></a>
            </div>

            <div class="info-item">
              <label>Correo (usuario)</label>
              <div><?php echo htmlspecialchars($email); ?></div>
            </div>

            <div class="info-item">
              <label>Rol</label>
              <div><?php echo htmlspecialchars($rol); ?></div>
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
              <div><?php echo $ultimoAcceso ?: '<em>Sin registro</em>'; ?></div>
            </div>

            <div class="info-item">
              <label>Intentos fallidos</label>
              <div><?php echo $intentos; ?></div>
            </div>

            <div class="info-item">
              <label>Expiración de contraseña</label>
              <div><?php echo $pwdExpira ?: '<em>No establecido</em>'; ?></div>
            </div>

            <div class="info-item">
              <label>Fecha de creación</label>
              <div><?php echo $creado; ?></div>
            </div>

            <div class="info-item">
              <label>Última actualización</label>
              <div><?php echo $actualizado; ?></div>
            </div>

            <div class="info-item col-span-2">
              <label>Notas</label>
              <div><?php echo $notas ?: '<em>Sin notas registradas</em>'; ?></div>
            </div>
          </div>
        </div>
      </section>

      <!-- Accesos rápidos -->
      <section class="card">
        <header>⚡ Accesos rápidos</header>
        <div class="content" style="display:flex; gap:8px; flex-wrap:wrap;">
          <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 Ver empresa</a>
          <a class="btn" href="portal_edit.php?id=<?php echo $id; ?>">✏️ Editar</a>
          <a class="btn" href="portal_list.php">🔐 Volver al listado</a>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
