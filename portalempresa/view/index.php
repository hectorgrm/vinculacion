<?php
declare(strict_types=1);

require_once __DIR__ . '/../handler/portal_empresa_handler.php';

$portalViewModel = $portalDashboardViewModel ?? [];
$empresaNombre = $portalViewModel['empresaNombre'] ?? 'Empresa';
$ultimoAccesoLabel = $portalViewModel['ultimoAccesoLabel'] ?? '';
$dashboardError = $portalViewModel['dashboardError'] ?? null;
$convenio = $portalViewModel['convenio'] ?? [];
$machote = $portalViewModel['machote'] ?? [];
$stats = $portalViewModel['stats'] ?? [];

$machoteId = (int) ($machote['id'] ?? 0);
$machoteRevisionLink = $machote['revisionLink'] ?? null;
$machoteAprobadoLink = $machote['aprobadoLink'] ?? null;
$documentoPdfUrl = $machote['documentoPdfUrl'] ?? null;
$documentoFuenteLabel = $machote['documentoFuenteLabel'] ?? null;
$isMachoteConfirmado = !empty($machote['confirmado']);
$machoteActualizado = $machote['actualizadoLabel'] ?? '';
$revisionVariant = $machote['revisionVariant'] ?? 'warn';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal de Empresa – Inicio</title>

  <link rel="stylesheet" href="../assets/css/portal/index.css">
  

  <style>
    .alert-card{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:14px;padding:14px;margin-bottom:16px;}
    .small-meta{color:#64748b;font-size:13px;margin-top:4px;}
    .cards .card.disabled{opacity:.7;pointer-events:none}
    .stats.triple{grid-template-columns:repeat(3,1fr)}
  </style>
</head>
<body>
<?php include __DIR__ . '/../layout/portal_empresa_header.php'; ?>

<main class="container">
  <?php if ($dashboardError !== null): ?>
    <div class="alert-card">
      <strong>Ups, algo salió mal:</strong>
      <p><?= htmlspecialchars($dashboardError) ?></p>
    </div>
  <?php endif; ?>

  <section class="welcome">
    <div>
      <h1>Hola, Bienvenido a Portal de Empresa ITSJ</h1>
      <p>Desde aquí puedes consultar tu convenio, documentos, estudiantes y reportes.</p>
      <?php if ($ultimoAccesoLabel !== ''): ?>
        <p class="small-meta">Último acceso registrado: <?= htmlspecialchars($ultimoAccesoLabel) ?></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="strip">
    <div class="left">
      <h3>Estado del convenio</h3>
      <p><strong>Folio:</strong> <?= htmlspecialchars($convenio['folio'] ?? 'Sin folio') ?></p>
      <p><strong>Vigencia:</strong> <?= htmlspecialchars($convenio['vigenciaInicio'] ?? 'N/D') ?> – <?= htmlspecialchars($convenio['vigenciaFin'] ?? 'N/D') ?></p>
      <p>
        <strong>Estatus:</strong>
        <span class="badge <?= htmlspecialchars($convenio['badgeVariant'] ?? 'warn') ?>"><?= htmlspecialchars($convenio['estatus'] ?? 'Sin convenio') ?></span>
      </p>
      <div class="hint">Verifica tu machote para conocer los comentarios y confirmar el documento final.</div>
    </div>
    <div class="right">
      <div class="stats triple">
        <div class="kpi">
          <div class="num"><?= htmlspecialchars((string) ($stats['comentariosPendientes'] ?? 0)) ?></div>
          <div class="lbl">Comentarios pendientes</div>
        </div>
        <div class="kpi">
          <div class="num"><?= htmlspecialchars((string) ($stats['comentariosResueltos'] ?? 0)) ?></div>
          <div class="lbl">Comentarios resueltos</div>
        </div>
        <div class="kpi">
          <div class="num"><?= htmlspecialchars((string) ($stats['avancePct'] ?? 0)) ?>%</div>
          <div class="lbl">Avance de revisión</div>
        </div>
      </div>
    </div>
  </section>

  <section class="cards">
    <article class="card">
      <h3>Machote en revisión</h3>
      <p>Estatus actual: <span class="badge <?= htmlspecialchars($revisionVariant) ?>"><?= htmlspecialchars($machote['estado'] ?? 'Sin documento') ?></span></p>
      <?php if ($machoteId > 0): ?>
        <p><strong>Versión:</strong> <?= htmlspecialchars($machote['version'] ?? 'v1.0') ?></p>
        <?php if ($machoteActualizado !== ''): ?>
          <p class="small-meta">Última actualización: <?= htmlspecialchars($machoteActualizado) ?></p>
        <?php endif; ?>
        <?php if (!empty($documentoFuenteLabel)): ?>
          <p class="small-meta">Documento disponible: <?= htmlspecialchars($documentoFuenteLabel) ?></p>
        <?php endif; ?>
      <?php else: ?>
        <p class="small-meta">Aún no hay un machote asignado a tu empresa.</p>
      <?php endif; ?>
      <div class="actions">
        <?php if ($machoteRevisionLink !== null): ?>
          <a class="btn primary" href="<?= htmlspecialchars($machoteRevisionLink) ?>">Revisar machote</a>
        <?php else: ?>
          <span class="btn" style="pointer-events:none;opacity:0.6;">Sin machote disponible</span>
        <?php endif; ?>
        <?php if ($isMachoteConfirmado && $machoteAprobadoLink !== null): ?>
          <a class="btn" href="<?= htmlspecialchars($machoteAprobadoLink) ?>">Documento final</a>
        <?php endif; ?>
        <?php if ($documentoPdfUrl !== null): ?>
          <a class="btn" href="<?= htmlspecialchars($documentoPdfUrl) ?>" target="_blank" rel="noopener">Descargar PDF</a>
        <?php endif; ?>
      </div>
    </article>

    <article class="card">
      <h3>Convenio</h3>
      <p>Datos del convenio vigente, anexos y renovación.</p>
      <div class="actions">
        <a class="btn primary" href="convenio_view.php">Ver convenio</a>
        <a class="btn" href="convenio_view.php#renovar">Solicitar renovación</a>
      </div>
    </article>

    <article class="card">
      <h3>Documentos</h3>
      <p>Consulta el estado de los documentos solicitados por Residencias.</p>
      <div class="actions">
        <a class="btn primary" href="documentos_list.php">Ver documentos</a>
        <a class="btn" href="documentos_list.php#subir">Subir actualización</a>
      </div>
    </article>

    <article class="card">
      <h3>Estudiantes</h3>
      <p>Revisa residencias activas e histórico de estudiantes.</p>
      <div class="actions">
        <a class="btn primary" href="estudiantes_list.php">Ver estudiantes</a>
      </div>
    </article>

    <article class="card">
      <h3>Perfil de la empresa</h3>
      <p>Datos de contacto y responsables con la universidad.</p>
      <div class="actions">
        <a class="btn primary" href="perfil_empresa.php">Ver perfil</a>
        <a class="btn" href="soporte.php">Soporte / Ayuda</a>
      </div>
    </article>
  </section>

  <p class="hint">Si tienes dudas o necesitas asistencia, visita la sección <a href="soporte.php">Soporte</a>.</p>
</main>

</body>
</html>
