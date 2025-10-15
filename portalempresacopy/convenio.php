<?php
declare(strict_types=1);

// Simulación de sesión empresa (después vendrá validación con portal_acceso)
$empresaId = $_GET['empresa'] ?? 45;

// Datos simulados (reemplazar con consulta real a la BD)
$empresa = [
  'nombre' => 'Casa del Barrio',
  'representante' => 'José Velador',
];
$convenio = [
  'id' => 12,
  'folio' => 'CVN-2025-09-012',
  'estado' => 'En revisión', // En revisión, Aprobado, Rechazado
  'version' => 'v1.3',
  'archivo' => 'convenio_casadelbarrio_v1.3.pdf',
  'comentarios' => [
    [
      'autor' => 'Vinculación',
      'fecha' => '2025-10-10 11:20',
      'texto' => 'Favor de revisar la firma del representante.',
      'tipo' => 'observacion'
    ],
    [
      'autor' => 'Casa del Barrio',
      'fecha' => '2025-10-10 12:15',
      'texto' => 'Se sube la nueva versión firmada. Gracias.',
      'tipo' => 'respuesta'
    ]
  ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Convenio · <?php echo htmlspecialchars($empresa['nombre']); ?></title>
  <link rel="stylesheet" href="../../assets/stylesrecidencia.css"/>
  <link rel="stylesheet" href="./assets/css/portalempresa.css"/>
  <style>
    .status-badge {
      display:inline-block; padding:6px 12px; border-radius:12px;
      color:#fff; font-weight:600; font-size:14px;
    }
    .status-revision { background:#f59e0b; }
    .status-aprobado { background:#22c55e; }
    .status-rechazado { background:#ef4444; }
    .comments { margin-top:20px; }
    .comment {
      background:#f8fafc; border:1px solid #e2e8f0;
      border-radius:12px; padding:12px; margin-bottom:12px;
    }
    .comment.observacion { border-left:4px solid #eab308; }
    .comment.respuesta { border-left:4px solid #2563eb; }
    .comment .meta {
      font-size:13px; color:#64748b; margin-bottom:4px;
    }
    .comment .texto { font-size:15px; color:#1e293b; }
    .upload-form input[type=file] {
      margin-top:8px; display:block;
    }
  </style>
</head>
<body>
  <main class="portal">
    <header class="portal-header">
      <div class="brand">
        <h1>📑 Convenio y Machote</h1>
        <p>Empresa: <?php echo htmlspecialchars($empresa['nombre']); ?></p>
      </div>
      <div class="actions">
        <a href="index.php?empresa=<?php echo $empresaId; ?>" class="btn">⬅ Volver al panel</a>
      </div>
    </header>

    <section class="card">
      <header>🧾 Detalles del Convenio</header>
      <div class="content">
        <ul style="list-style:none; padding:0; margin:0 0 20px 0;">
          <li><strong>Folio:</strong> <?php echo htmlspecialchars($convenio['folio']); ?></li>
          <li><strong>Versión:</strong> <?php echo htmlspecialchars($convenio['version']); ?></li>
          <li>
            <strong>Estatus:</strong>
            <?php
              $cls = match($convenio['estado']){
                'Aprobado'=>'status-aprobado',
                'Rechazado'=>'status-rechazado',
                default=>'status-revision'
              };
            ?>
            <span class="status-badge <?php echo $cls; ?>"><?php echo htmlspecialchars($convenio['estado']); ?></span>
          </li>
        </ul>

        <?php if($convenio['archivo']): ?>
          <div style="margin-bottom:12px;">
            <p>📎 Archivo actual:</p>
            <a class="btn" href="../../uploads/<?php echo urlencode($convenio['archivo']); ?>" target="_blank">📄 Ver Machote</a>
          </div>
        <?php else: ?>
          <p><em>No se ha cargado ningún machote.</em></p>
        <?php endif; ?>

        <form class="upload-form" action="convenio_upload_action.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="convenio_id" value="<?php echo $convenio['id']; ?>">
          <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
          <label for="archivo">Subir versión firmada (PDF):</label>
          <input id="archivo" type="file" name="archivo" accept=".pdf" required>
          <button class="btn primary" type="submit" style="margin-top:8px;">⬆️ Subir Machote</button>
        </form>
      </div>
    </section>

    <section class="card comments">
      <header>💬 Comentarios y Revisión</header>
      <div class="content">
        <?php foreach($convenio['comentarios'] as $com): ?>
          <div class="comment <?php echo $com['tipo']; ?>">
            <div class="meta">
              <strong><?php echo htmlspecialchars($com['autor']); ?></strong> — 
              <?php echo htmlspecialchars($com['fecha']); ?>
            </div>
            <div class="texto"><?php echo htmlspecialchars($com['texto']); ?></div>
          </div>
        <?php endforeach; ?>

        <form class="form" action="convenio_comentario_action.php" method="post" style="margin-top:20px;">
          <input type="hidden" name="convenio_id" value="<?php echo $convenio['id']; ?>">
          <input type="hidden" name="empresa_id" value="<?php echo $empresaId; ?>">
          <label for="comentario">Agregar comentario:</label>
          <textarea id="comentario" name="comentario" rows="3" required placeholder="Escribe tu mensaje..."></textarea>
          <button class="btn primary" type="submit" style="margin-top:8px;">💬 Enviar</button>
        </form>
      </div>
    </section>

    <footer class="portal-footer">
      <p>Universidad Tecnológica · Residencias Profesionales</p>
    </footer>
  </main>
</body>
</html>
