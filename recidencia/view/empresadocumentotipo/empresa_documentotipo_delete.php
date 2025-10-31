<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Eliminar Documento Individual · Residencias Profesionales</title>

  <!-- Estilos -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="../../assets/css/documentotipo/documentotipo.css"/>

  <style>
    .danger-zone {
      background: #fff;
      border: 1px solid #fee2e2;
      border-radius: 18px;
      box-shadow: var(--shadow-sm);
    }

    .danger-zone > header {
      padding: 16px 20px;
      border-bottom: 1px solid #fee2e2;
      font-weight: 700;
      color: #b91c1c;
    }

    .danger-zone .content {
      padding: 20px;
    }

    .btn.danger {
      background: #e44848;
      color: #fff;
      border-color: #e44848;
    }

    .btn.danger:hover {
      filter: brightness(.95);
    }

    .badge.warn {
      background: #fff3cd;
      color: #856404;
      padding: 3px 8px;
      border-radius: 5px;
      font-size: 13px;
      font-weight: 600;
    }

    .summary {
      background: #f9fafb;
      border-radius: 8px;
      padding: 12px 16px;
      border: 1px solid #eee;
      margin-bottom: 12px;
    }
  </style>
</head>

<body>
  <?php
    $empresa_id = $_GET['id_empresa'] ?? 0;
    $doc_id = $_GET['id'] ?? 0;

    // Datos simulados (luego provendrán del Controller)
    $doc = [
      'nombre' => 'Certificado de Seguridad Interna',
      'descripcion' => 'Documento expedido por el área de seguridad para verificar cumplimiento interno.',
      'obligatorio' => 1,
      'activo' => 1
    ];
  ?>

  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="topbar">
        <div>
          <h2>🗑️ Eliminar Documento Individual</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a><span>›</span>
            <a href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>">Documentos de Empresa</a><span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <a class="btn" href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>">⬅ Volver</a>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <div class="summary">
            <strong>Documento:</strong> <?= htmlspecialchars($doc['nombre']) ?><br>
            <strong>Descripción:</strong> <?= htmlspecialchars($doc['descripcion']) ?><br>
            <strong>Obligatorio:</strong> <?= $doc['obligatorio'] ? 'Sí' : 'No' ?><br>
            <strong>Estado actual:</strong>
            <?= $doc['activo'] ? '<span class="badge ok">Activo</span>' : '<span class="badge warn">Inactivo</span>' ?>
          </div>

          <p>
            Estás a punto de eliminar el documento <strong>"<?= htmlspecialchars($doc['nombre']) ?>"</strong> del sistema
            para la empresa #<?= htmlspecialchars($empresa_id) ?>.
          </p>

          <p class="text-muted">
            ⚠️ Esta acción <strong>no se puede deshacer</strong>.<br>
            Si la empresa ya ha subido archivos o este documento tiene historial, se recomienda <strong>desactivarlo</strong> en lugar de eliminarlo.
          </p>

          <form action="empresa_documentotipo_delete_action.php" method="post" style="margin-top:12px;">
            <input type="hidden" name="id" value="<?= htmlspecialchars($doc_id) ?>">
            <input type="hidden" name="empresa_id" value="<?= htmlspecialchars($empresa_id) ?>">

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>Confirmo que deseo eliminar permanentemente este registro.</span>
            </label>

            <div class="actions" style="justify-content:flex-end;">
              <a class="btn" href="empresa_documentotipo_list.php?id_empresa=<?= htmlspecialchars($empresa_id) ?>">Cancelar</a>
              <button class="btn danger" type="submit">🗑️ Eliminar</button>
            </div>
          </form>
        </div>
      </section>

      <!-- ℹ️ Nota -->
      <section class="card" style="margin-top:20px;">
        <header>ℹ️ Información adicional</header>
        <div class="content">
          <p>
            Los documentos eliminados se removerán completamente del listado de requerimientos de esta empresa.
            Si necesitas mantener un registro histórico, considera marcarlo como <strong>inactivo</strong> en lugar de eliminarlo.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
