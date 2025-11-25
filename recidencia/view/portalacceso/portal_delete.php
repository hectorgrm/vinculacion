<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Acceso · Residencias</title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/modules/portalacceso.css" />

</head>

<body>
  <?php
  $id = isset($_GET['id']) ? (int) $_GET['id'] : 901;
  $email = 'admin@casadelbarrio.mx';
  $empresaId = 45;
  $empresa = 'Casa del Barrio';
  ?>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar acceso</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="portal_list.php">Portal de Acceso</a><span>›</span>
            <a href="portal_view.php?id=<?php echo $id; ?>">Ver</a><span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">⬅ Volver</a>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>
            Estás a punto de <strong>eliminar definitivamente</strong> el acceso de
            <strong><?php echo htmlspecialchars($email); ?></strong> asociado a
            <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢
              <?php echo htmlspecialchars($empresa); ?></a>.
            Esta acción <strong>no se puede deshacer</strong>.
          </p>
          <p class="text-muted">Nota: esto no elimina la empresa ni sus convenios/documentos; solo las credenciales de
            acceso al portal.</p>

          <form action="portal_delete_action.php?id=<?php echo $id; ?>" method="post" style="margin-top:12px;">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <label style="display:flex; gap:10px; align-items:flex-start; margin:10px 0;">
              <input type="checkbox" name="confirm" required>
              <span>Confirmo que deseo eliminar permanentemente este acceso.</span>
            </label>
            <div class="actions" style="justify-content:flex-end;">
              <a class="btn" href="portal_view.php?id=<?php echo $id; ?>">Cancelar</a>
              <button class="btn danger" type="submit">🗑️ Eliminar</button>
            </div>
          </form>
        </div>
      </section>

      <section class="card">
        <header>Alternativas sugeridas</header>
        <div class="content">
          <ul style="margin:0;padding-left:18px;">
            <li>Marcar como <strong>Bloqueado</strong> temporalmente.</li>
            <li>Cambiar estatus a <strong>Inactivo</strong> (mantiene historial).</li>
            <li>Aplicar <strong>reset de contraseña</strong> y habilitar 2FA.</li>
          </ul>
        </div>
      </section>
    </main>
  </div>
</body>

</html>