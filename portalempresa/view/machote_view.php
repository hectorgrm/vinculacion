<?php
// === Variables simuladas (se llenarán desde el handler) ===
$empresaNombre = "Casa del Barrio";
$versionMachote = "v1.2";
$estatus = "En revisión"; // En revisión | Aprobado | Con observaciones
$comentAbiertos = 1;
$comentResueltos = 3;
$avancePct = 75;
$confirmado = false;

$hilos = [
  [
    'id' => 101,
    'estatus' => 'pendiente',
    'autor_rol' => 'empresa',
    'asunto' => 'Ajuste en la vigencia',
    'resumen' => 'Proponemos 18 meses por motivos de calendario…',
    'hace' => 'hace 2 días',
    'mensajes' => [
      ['autor_rol' => 'empresa', 'fecha' => '2025-10-01 10:12', 'texto' => 'Proponemos 18 meses...', 'archivos' => ['propuesta.pdf']],
      ['autor_rol' => 'admin', 'fecha' => '2025-10-01 12:20', 'texto' => 'De acuerdo, actualizaremos.']
    ]
  ],
  [
    'id' => 102,
    'estatus' => 'resuelto',
    'autor_rol' => 'admin',
    'asunto' => 'Confidencialidad',
    'resumen' => 'Se integró el anexo de confidencialidad.',
    'hace' => 'hace 5 días',
    'mensajes' => [
      ['autor_rol' => 'empresa', 'fecha' => '2025-09-29 09:40', 'texto' => 'Por favor reforzar el apartado.'],
      ['autor_rol' => 'admin', 'fecha' => '2025-09-29 13:10', 'texto' => 'Listo: agregado anexo estándar.']
    ]
  ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal Empresa · Revisión del Acuerdo</title>
  <link rel="stylesheet" href="../assets/css/portal/machoteview.css">
</head>
<body>

<header class="portal-header">
  <div class="brand">
    <h1>Portal de Empresa</h1>
    <small>Revisión del acuerdo (machote)</small>
  </div>
  <div class="userbox">
    <span class="company"><?= htmlspecialchars($empresaNombre) ?></span>
    <a href="../portalacceso/portal_list.php" class="btn small">🏠 Inicio</a>
    <a href="../../common/logout.php" class="btn small danger">Salir</a>
  </div>
</header>

<main class="layout">

  <!-- Columna izquierda -->
  <section class="left">

    <!-- Estado general -->
    <div class="card">
      <header>Estado general</header>
      <div class="content kpis">
        <div class="metric"><div class="num"><?= $comentAbiertos ?></div><div class="lbl">Pendientes</div></div>
        <div class="metric"><div class="num"><?= $comentResueltos ?></div><div class="lbl">Atendidos</div></div>
        <div class="metric"><div class="num"><?= $avancePct ?>%</div><div class="lbl">Avance</div></div>
        <div class="metric status">
          <?php if ($estatus === 'Aprobado'): ?>
            <span class="badge ok">Aprobado</span>
          <?php else: ?>
            <span class="badge warn"><?= htmlspecialchars($estatus) ?></span>
          <?php endif; ?>
        </div>
      </div>
      <div class="progressbox">
        <div class="progress"><div class="bar" style="width:<?= $avancePct ?>%"></div></div>
      </div>
    </div>

    <!-- Documento -->
    <div class="card">
      <header>Documento a revisar · <?= htmlspecialchars($versionMachote) ?></header>
      <div class="content">
        <div class="pdf-frame">
          <iframe src="../../uploads/machote_v12_borrador.pdf#view=FitH" title="Machote PDF"></iframe>
        </div>
        <div class="file-actions">
          <a class="btn" href="../../uploads/machote_v12_borrador.pdf" target="_blank">📄 Ver PDF</a>
          <a class="btn" download href="../../uploads/machote_v12_borrador.pdf">⬇️ Descargar</a>
        </div>
      </div>
    </div>

    <!-- Confirmación -->
    <div class="card">
      <header>Confirmación de Empresa</header>
      <div class="content approval">
        <form action="../handler/machote/machote_confirm_handler.php" method="post">
          <input type="hidden" name="machote_id" value="<?= (int)$_GET['id'] ?>">
          <label class="switch">
            <input type="checkbox" name="confirmacion_empresa" value="1" <?= $confirmado ? 'checked disabled' : '' ?>>
            <span class="slider"></span>
            <span class="label">Estoy de acuerdo con el contenido del documento</span>
          </label>
          <?php if ($confirmado): ?>
            <p class="ok-note">✅ Confirmación registrada el <?= date("d/m/Y") ?>.</p>
          <?php else: ?>
            <div class="actions">
              <button type="submit" class="btn primary">💾 Guardar confirmación</button>
            </div>
          <?php endif; ?>
        </form>
      </div>
    </div>

  </section>

  <!-- Columna derecha -->
  <section class="right">

    <!-- Nuevo comentario -->
    <div class="card">
      <header>Agregar comentario</header>
      <div class="content">
        <form action="../handler/machote/machote_comentario_add_handler.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="machote_id" value="<?= (int)$_GET['id'] ?>">
          <div class="field">
            <label for="asunto">Tema</label>
            <input type="text" id="asunto" name="asunto" required>
          </div>
          <div class="field">
            <label for="comentario">Descripción</label>
            <textarea id="comentario" name="comentario" rows="3" required></textarea>
          </div>
          <div class="field">
            <label for="adjunto">Adjunto (opcional)</label>
            <input type="file" name="adjunto" accept=".pdf,.jpg,.png,.docx">
          </div>
          <div class="actions">
            <button class="btn primary">💬 Publicar comentario</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Lista de comentarios -->
    <div class="card">
      <header>Comentarios recientes</header>
      <div class="content threads">
        <?php foreach ($hilos as $h): ?>
          <article class="thread">
            <div class="meta">
              <span class="badge <?= $h['estatus'] === 'resuelto' ? 'resuelto' : 'abierto' ?>">
                <?= ucfirst($h['estatus']) ?>
              </span>
              <span class="author <?= $h['autor_rol'] === 'admin' ? 'admin' : 'empresa' ?>">
                <?= $h['autor_rol'] === 'admin' ? 'Residencias' : 'Empresa' ?>
              </span>
              <span class="time"><?= htmlspecialchars($h['hace']) ?></span>
            </div>
            <h4><?= htmlspecialchars($h['asunto']) ?></h4>
            <p><?= htmlspecialchars($h['resumen']) ?></p>

            <?php foreach ($h['mensajes'] as $m): ?>
              <div class="message">
                <div class="head">
                  <span class="pill <?= $m['autor_rol'] ?>"><?= ucfirst($m['autor_rol']) ?></span>
                  <time><?= htmlspecialchars($m['fecha']) ?></time>
                </div>
                <p><?= htmlspecialchars($m['texto']) ?></p>
                <?php if (!empty($m['archivos'])): ?>
                  <div class="files">
                    <?php foreach ($m['archivos'] as $f): ?>
                      <a href="#"><?= htmlspecialchars($f) ?></a>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </article>
        <?php endforeach; ?>
      </div>
    </div>

  </section>
</main>

<footer class="portal-foot">
  <small>Portal de Empresa · Área de Vinculación</small>
</footer>
</body>
</html>
