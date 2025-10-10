<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento · Residencias Profesionales</title>

  <!-- Estilo global del módulo -->
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
  <link rel="stylesheet" href="../../assets/css/documentos/documento_delete.css" />


  <!-- Estilos mínimos complementarios para la vista de eliminación -->
  <style>
    .danger-zone {
      background: #fff;
      border: 1px solid #fee2e2;
      border-radius: 18px;
      box-shadow: var(--shadow-sm);
    }

    .danger-zone>header {
      padding: 16px 20px;
      border-bottom: 1px solid #fee2e2;
      font-weight: 700;
      color: #b91c1c;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .danger-zone .content {
      padding: 20px
    }

    .danger-list {
      margin: 10px 0 0 0;
      padding-left: 18px;
      color: #334155
    }

    .danger-list li {
      margin: 6px 0
    }

    .checklist {
      margin-top: 14px;
      padding: 12px;
      border: 1px dashed #fecaca;
      border-radius: 12px;
      background: #fff7f7;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px
    }

    .btn.danger {
      background: #e44848;
      color: #fff;
      border-color: #e44848
    }

    .btn.danger:hover {
      filter: brightness(.95)
    }

    .links-inline {
      margin-top: 10px;
      display: flex;
      gap: 8px;
      flex-wrap: wrap
    }

    .card.mini {
      border-radius: 16px;
      border: 1px solid var(--border, #e5e7eb);
      box-shadow: var(--shadow-sm);
      padding: 16px;
    }

    .card.mini h3 {
      margin: 0 0 6px 0;
      font-size: 15px
    }

    .card.mini p {
      margin: 0
    }

    @media (max-width:768px) {
      .grid-2 {
        grid-template-columns: 1fr
      }
    }
  </style>
</head>

<body>
  <?php
  // Parámetros opcionales para contexto
  $id = isset($_GET['id']) ? (int) $_GET['id'] : 101;
  $empresaId = isset($_GET['empresa']) ? (int) $_GET['empresa'] : 45;
  $convenioId = isset($_GET['convenio']) ? (int) $_GET['convenio'] : 12;

  // Datos de ejemplo (reemplazar por consulta a BD)
  $empresaNombre = 'Casa del Barrio';
  $convenioLabel = '#12 (v1.2)';
  $tipoDocumento = 'INE Representante';
  $estatusDoc = 'Aprobado';
  $archivoURL = '../../uploads/ine_josevelador.pdf';
  $fechaCarga = '2025-09-10';
  $tamanoAprox = '0.9 MB';
  ?>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <header class="topbar">
        <div>
          <h2>Eliminar Documento</h2>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>›</span>
            <a href="documento_list.php">Documentos</a>
            <span>›</span>
            <span>Eliminar</span>
          </nav>
        </div>
        <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
          <a href="documento_view.php?id=<?php echo $id; ?>" class="btn">👁️ Ver</a>
          <a href="documento_list.php<?php echo $empresaId ? '?empresa=' . $empresaId : ''; ?>" class="btn">⬅ Volver</a>
        </div>
      </header>

      <section class="danger-zone">
        <header>⚠️ Confirmación requerida</header>
        <div class="content">
          <p>
            Estás a punto de <strong>eliminar definitivamente</strong> el documento
            <strong>#<?php echo $id; ?></strong> (<em><?php echo htmlspecialchars($tipoDocumento); ?></em>)
            de la empresa
            <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢
              <?php echo htmlspecialchars($empresaNombre); ?> (ID <?php echo $empresaId; ?>)</a>
            <?php if ($convenioId): ?>
              vinculado al convenio
              <a class="btn" href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">📑
                <?php echo htmlspecialchars($convenioLabel); ?></a>
            <?php endif; ?>
            . Esta acción <strong>no se puede deshacer</strong>.
          </p>

          <!-- Resumen del documento -->
          <div class="grid-2" style="margin-top:12px;">
            <div class="card mini">
              <h3>Resumen</h3>
              <p class="text-muted">Tipo: <?php echo htmlspecialchars($tipoDocumento); ?></p>
              <p class="text-muted">Estatus: <?php echo htmlspecialchars($estatusDoc); ?></p>
              <p class="text-muted">Fecha de carga: <?php echo htmlspecialchars($fechaCarga); ?></p>
              <p class="text-muted">Tamaño: <?php echo htmlspecialchars($tamanoAprox); ?></p>
            </div>
            <div class="card mini">
              <h3>Archivo</h3>
              <p><a class="btn" href="<?php echo htmlspecialchars($archivoURL); ?>" target="_blank">📄 Abrir archivo</a>
              </p>
              <p class="text-muted">Verifica el documento antes de eliminarlo.</p>
            </div>
          </div>

          <div class="checklist">
            <p><strong>Antes de continuar, verifica lo siguiente:</strong></p>
            <ul class="danger-list">
              <li>Que <strong>no sea requerido</strong> por un convenio en curso.</li>
              <li>Que <strong>no existan revisiones pendientes</strong> asociadas.</li>
              <li>Que <strong>tienes respaldo</strong> en caso de ser necesario.</li>
            </ul>

            <!-- Enlaces rápidos -->
            <div class="links-inline">
              <a class="btn" href="../empresa/empresa_view.php?id=<?php echo $empresaId; ?>">🏢 Ver empresa</a>
              <?php if ($convenioId): ?>
                <a class="btn" href="../convenio/convenio_view.php?id=<?php echo $convenioId; ?>">📑 Ver convenio</a>
              <?php endif; ?>
              <a class="btn" href="documento_list.php<?php echo $empresaId ? '?empresa=' . $empresaId : ''; ?>">📂 Volver
                a documentos</a>
            </div>
          </div>

          <form action="documento_delete_action.php" method="post" style="margin-top:18px;">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
            <?php if ($convenioId): ?>
              <input type="hidden" name="convenio_id" value="<?php echo $convenioId; ?>">
            <?php endif; ?>
            <!-- Si manejas CSRF:
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            -->

            <label style="display:flex; gap:8px; align-items:flex-start; margin:12px 0;">
              <input type="checkbox" name="confirm" required>
              <span>He leído las advertencias y deseo <strong>eliminar permanentemente</strong> este documento.</span>
            </label>

            <!-- Motivo opcional -->
            <div class="field" style="margin-top:10px;">
              <label for="motivo">Motivo (opcional)</label>
              <textarea id="motivo" name="motivo" rows="3"
                placeholder="Breve explicación para la bitácora..."></textarea>
            </div>

            <div class="actions" style="justify-content:flex-end;">
              <a href="documento_view.php?id=<?php echo $id; ?>" class="btn">⬅ Cancelar</a>
              <button type="submit" class="btn danger">🗑️ Eliminar Documento</button>
            </div>
          </form>

          <p class="text-muted" style="margin-top:10px">
            Sugerencia: si solo quieres ocultarlo del flujo, considera cambiar su estatus a <em>Rechazado</em> en lugar
            de eliminarlo.
          </p>
        </div>
      </section>
    </main>
  </div>
</body>

</html>