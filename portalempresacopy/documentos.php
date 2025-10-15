<?php
declare(strict_types=1);

// 🔐 Simulación de sesión/empresa (más adelante validación real con portal_acceso)
$empresaId = $_GET['empresa'] ?? 45;

// 🔹 Ejemplo de datos (simulados desde BD)
$empresa = [
  'nombre' => 'Casa del Barrio',
];

$documentos = [
  ['id'=>1,'tipo'=>'Carta de Presentación','archivo'=>'carta_presentacion.pdf','estatus'=>'Aprobado','comentario'=>'Todo correcto.'],
  ['id'=>2,'tipo'=>'Plan de Trabajo','archivo'=>'','estatus'=>'Pendiente','comentario'=>'Falta subir documento.'],
  ['id'=>3,'tipo'=>'Evaluación Final','archivo'=>'evaluacion_final.pdf','estatus'=>'En revisión','comentario'=>'Revisando formato de firma.'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Documentos · <?php echo htmlspecialchars($empresa['nombre']); ?></title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="./assets/css/portalempresa.css"/>
  <style>
    .doc-table { width:100%; border-collapse: collapse; }
    .doc-table th, .doc-table td {
      border-bottom:1px solid #e2e8f0; padding:10px 8px; text-align:left;
    }
    .doc-table th { background:#f1f5f9; color:#1e3a8a; font-weight:600; }
    .badge {
      padding:4px 10px; border-radius:8px; font-size:13px; color:#fff; font-weight:600;
    }
    .ok { background:#22c55e; }
    .warn { background:#f59e0b; }
    .pend { background:#94a3b8; }
    .err { background:#ef4444; }
    .upload-form input[type=file] {
      display:block; margin-top:6px; font-size:14px;
    }
    .comment {
      font-size:13px; color:#475569; background:#f8fafc; padding:8px; border-radius:8px;
      border:1px solid #e2e8f0; margin-top:6px;
    }
    .actions a { text-decoration:none; }
  </style>
</head>
<body>
  <main class="portal">
    <header class="portal-header">
      <div class="brand">
        <h1>📂 Documentos</h1>
        <p>Empresa: <?php echo htmlspecialchars($empresa['nombre']); ?></p>
      </div>
      <div class="actions">
        <a href="index.php?empresa=<?php echo $empresaId; ?>" class="btn">⬅ Volver al panel</a>
      </div>
    </header>

    <section class="card">
      <header>📋 Documentos requeridos</header>
      <div class="content">
        <table class="doc-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Tipo de Documento</th>
              <th>Archivo Subido</th>
              <th>Estatus</th>
              <th>Comentario</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($documentos as $i => $doc): ?>
            <tr>
              <td><?php echo $i+1; ?></td>
              <td><?php echo htmlspecialchars($doc['tipo']); ?></td>
              <td>
                <?php if($doc['archivo']): ?>
                  <a href="../../uploads/<?php echo urlencode($doc['archivo']); ?>" target="_blank">
                    <?php echo htmlspecialchars($doc['archivo']); ?>
                  </a>
                <?php else: ?>
                  <em>No subido</em>
                <?php endif; ?>
              </td>
              <td>
                <?php
                  $cls = match($doc['estatus']) {
                    'Aprobado' => 'ok',
                    'En revisión' => 'warn',
                    'Pendiente' => 'pend',
                    'Rechazado' => 'err',
                    default => 'pend'
                  };
                ?>
                <span class="badge <?php echo $cls; ?>"><?php echo htmlspecialchars($doc['estatus']); ?></span>
              </td>
              <td>
                <?php if($doc['comentario']): ?>
                  <div class="comment"><?php echo htmlspecialchars($doc['comentario']); ?></div>
                <?php else: ?>
                  <em>—</em>
                <?php endif; ?>
              </td>
              <td>
                <form class="upload-form" action="documentos_upload_action.php" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="documento_id" value="<?php echo $doc['id']; ?>">
                  <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
                  <input type="file" name="archivo" accept=".pdf,.docx" required>
                  <button class="btn primary" type="submit" style="margin-top:6px;">⬆️ Subir</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </section>

    <footer class="portal-footer">
      <p>Universidad Tecnológica · Residencias Profesionales</p>
    </footer>
  </main>
</body>
</html>
