<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Eliminar Tipo de Documento · Residencias</title>

  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
    <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css"/>


  <style>
    .danger-zone{ background:#fff; border:1px solid #fee2e2; border-radius:18px; box-shadow:var(--shadow-sm) }
    .danger-zone > header{ padding:16px 20px; border-bottom:1px solid #fee2e2; font-weight:700; color:#b91c1c }
    .danger-zone .content{ padding:20px }
    .btn.danger{ background:#e44848; color:#fff; border-color:#e44848 }
    .btn.danger:hover{ filter:brightness(.95) }
  </style>
</head>
<body>
<?php $id = $_GET['id'] ?? 1; ?>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar Tipo de Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="documentotipo_list.php">Documento Tipo</a><span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <a class="btn" href="documentotipo_list.php">⬅ Volver</a>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>Vas a eliminar el tipo de documento <strong>#<?php echo htmlspecialchars($id); ?></strong>.
            Esta acción no se puede deshacer.</p>

          <p class="text-muted">Sugerencia: si quieres conservar historial y deshabilitarlo del flujo, marca el tipo como <em>Inactivo</em> en lugar de eliminarlo.</p>

          <form action="documentotipo_delete_action.php" method="post" style="margin-top:12px;">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>Confirmo que deseo eliminar permanentemente este registro.</span>
            </label>
            <div class="actions" style="justify-content:flex-end;">
              <a class="btn" href="documentotipo_list.php">Cancelar</a>
              <button class="btn danger" type="submit">🗑️ Eliminar</button>
            </div>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
