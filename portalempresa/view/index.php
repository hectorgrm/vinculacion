<?php
declare(strict_types=1);

require_once __DIR__ . '/../handler/portal_empresa_handler.php';
require_once __DIR__ . '/../helpers/empresaconveniofunction.php';

use PortalEmpresa\Helpers\EmpresaConvenioHelper;

$empresaNombre = isset($empresaNombre) ? (string) $empresaNombre : 'Empresa';
$ultimoAccesoLabel = isset($ultimoAccesoLabel) ? (string) $ultimoAccesoLabel : '';
$convenio = is_array($convenio ?? null) ? $convenio : null;
$machoteResumen = is_array($machoteResumen ?? null) ? $machoteResumen : null;
$machoteStats = is_array($machoteStats ?? null)
    ? array_merge([
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ], $machoteStats)
    : [
        'comentarios_total' => 0,
        'comentarios_pendientes' => 0,
        'comentarios_resueltos' => 0,
        'avance' => 0,
        'estado_revision' => 'Sin documento',
    ];
$dashboardError = isset($dashboardError) && is_string($dashboardError) && trim((string) $dashboardError) !== ''
    ? trim((string) $dashboardError)
    : null;

$machoteEstado = (string) ($machoteStats['estado_revision'] ?? 'Sin documento');
$estadoNormalizado = function_exists('mb_strtolower') ? mb_strtolower($machoteEstado, 'UTF-8') : strtolower($machoteEstado);
$revisionVariant = match ($estadoNormalizado) {
    'aprobado' => 'ok',
    'en revisión', 'en revision', 'pendiente de confirmación', 'pendiente de confirmacion' => 'warn',
    default => 'warn',
};

$machoteId = is_array($machoteResumen) ? (int) ($machoteResumen['id'] ?? 0) : 0;
$machoteRevisionLink = $machoteId > 0 ? 'machote_view.php?id=' . urlencode((string) $machoteId) : null;
$machoteAprobadoLink = $machoteId > 0 ? 'machote_view_aprobado.php?id=' . urlencode((string) $machoteId) : null;
$documentoPdfUrl = null;
$documentoFuente = null;
$isMachoteConfirmado = is_array($machoteResumen) && !empty($machoteResumen['confirmado']);

if (is_array($machoteResumen)) {
    $documentoPdfUrl = isset($machoteResumen['documento_pdf_url']) && $machoteResumen['documento_pdf_url'] !== null
        ? (string) $machoteResumen['documento_pdf_url']
        : null;
    $documentoFuente = isset($machoteResumen['documento_fuente']) ? (string) $machoteResumen['documento_fuente'] : null;
}

$machoteActualizado = '';

if (is_array($machoteResumen) && isset($machoteResumen['actualizado_en'])) {
    $rawValue = trim((string) $machoteResumen['actualizado_en']);
    if ($rawValue !== '') {
        try {
            $machoteActualizado = (new DateTimeImmutable($rawValue))->format('d/m/Y H:i');
        } catch (\Throwable) {
            $machoteActualizado = $rawValue;
        }
    }
}

$folioConvenio = $convenio !== null ? EmpresaConvenioHelper::valueOrDefault($convenio['folio'] ?? null, 'Sin folio') : 'Sin convenio';
$fechaInicio = $convenio !== null ? EmpresaConvenioHelper::formatDate($convenio['fecha_inicio'] ?? null, 'N/D') : 'N/D';
$fechaFin = $convenio !== null ? EmpresaConvenioHelper::formatDate($convenio['fecha_fin'] ?? null, 'N/D') : 'N/D';
$estatusConvenio = $convenio !== null ? trim((string) ($convenio['estatus'] ?? '')) : '';
$badgeConvenioVariant = $estatusConvenio !== '' ? EmpresaConvenioHelper::mapConvenioBadgeVariant($estatusConvenio) : 'warn';

if ($estatusConvenio === '') {
    $estatusConvenio = 'Sin convenio';
}

$comentPendientes = (int) ($machoteStats['comentarios_pendientes'] ?? 0);
$comentResueltos = (int) ($machoteStats['comentarios_resueltos'] ?? 0);
$avancePct = max(0, min(100, (int) ($machoteStats['avance'] ?? 0)));
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Portal de Empresa · Inicio</title>

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
      <strong>⚠️ <?= htmlspecialchars($dashboardError) ?></strong>
      <p>Intenta nuevamente más tarde o contacta a Residencias.</p>
    </div>
  <?php endif; ?>

  <section class="welcome">
    <div>
      <h1>¡Hola, <?= htmlspecialchars($empresaNombre) ?>!</h1>
      <p>Desde aquí puedes consultar tu convenio, documentos, estudiantes y reportes.</p>
      <?php if ($ultimoAccesoLabel !== ''): ?>
        <p class="small-meta">Último acceso registrado: <?= htmlspecialchars($ultimoAccesoLabel) ?></p>
      <?php endif; ?>
    </div>
  </section>

  <section class="strip">
    <div class="left">
      <h3>Estado del convenio</h3>
      <p><strong>Folio:</strong> <?= htmlspecialchars($folioConvenio) ?></p>
      <p><strong>Vigencia:</strong> <?= htmlspecialchars($fechaInicio) ?> — <?= htmlspecialchars($fechaFin) ?></p>
      <p>
        <strong>Estatus:</strong>
        <span class="badge <?= htmlspecialchars($badgeConvenioVariant) ?>"><?= htmlspecialchars($estatusConvenio) ?></span>
      </p>
      <div class="hint">Verifica tu machote para conocer los comentarios y confirmar el documento final.</div>
    </div>
    <div class="right">
      <div class="stats triple">
        <div class="kpi">
          <div class="num"><?= $comentPendientes ?></div>
          <div class="lbl">Comentarios pendientes</div>
        </div>
        <div class="kpi">
          <div class="num"><?= $comentResueltos ?></div>
          <div class="lbl">Comentarios resueltos</div>
        </div>
        <div class="kpi">
          <div class="num"><?= $avancePct ?>%</div>
          <div class="lbl">Avance de revisión</div>
        </div>
      </div>
    </div>
  </section>

  <section class="cards">
    <article class="card">
      <h3>Machote en revisión</h3>
      <p>Estatus actual: <span class="badge <?= htmlspecialchars($revisionVariant) ?>"><?= htmlspecialchars($machoteEstado) ?></span></p>
      <?php if ($machoteId > 0): ?>
        <p><strong>Versión:</strong> <?= htmlspecialchars((string) ($machoteResumen['version'] ?? 'v1.0')) ?></p>
        <?php if ($machoteActualizado !== ''): ?>
          <p class="small-meta">Última actualización: <?= htmlspecialchars($machoteActualizado) ?></p>
        <?php endif; ?>
        <?php if ($documentoFuente !== null): ?>
          <p class="small-meta">Documento disponible: <?= $documentoFuente === 'firmado' ? 'firmado' : 'borrador' ?></p>
        <?php endif; ?>
      <?php else: ?>
        <p class="small-meta">Aún no hay un machote asignado a tu empresa.</p>
      <?php endif; ?>
      <div class="actions">
        <?php if ($machoteRevisionLink !== null): ?>
          <a class="btn primary" href="<?= htmlspecialchars($machoteRevisionLink) ?>">👁️ Revisar machote</a>
        <?php else: ?>
          <span class="btn" style="pointer-events:none;opacity:0.6;">Sin machote disponible</span>
        <?php endif; ?>
        <?php if ($isMachoteConfirmado && $machoteAprobadoLink !== null): ?>
          <a class="btn" href="<?= htmlspecialchars($machoteAprobadoLink) ?>">✅ Documento final</a>
        <?php endif; ?>
        <?php if ($documentoPdfUrl !== null): ?>
          <a class="btn" href="<?= htmlspecialchars($documentoPdfUrl) ?>" target="_blank" rel="noopener">⬇️ Descargar PDF</a>
        <?php endif; ?>
      </div>
    </article>



    <article class="card">
      <h3>Convenio</h3>
      <p>Datos del convenio vigente, anexos y renovación.</p>
      <div class="actions">
        <a class="btn primary" href="convenio_view.php">📑 Ver convenio</a>
        <a class="btn" href="convenio_view.php#renovar">↺ Solicitar renovación</a>
      </div>
    </article>

    <article class="card">
      <h3>Documentos</h3>
      <p>Consulta el estado de los documentos solicitados por Residencias.</p>
      <div class="actions">
        <a class="btn primary" href="documentos_list.php">📂 Ver documentos</a>
        <a class="btn" href="documentos_list.php#subir">⬆️ Subir actualización</a>
      </div>
    </article>

    <article class="card">
      <h3>Estudiantes</h3>
      <p>Revisa residencias activas e histórico de estudiantes.</p>
      <div class="actions">
        <a class="btn primary" href="estudiantes_list.php">👨‍🎓 Ver estudiantes</a>
      </div>
    </article>

    <article class="card">
      <h3>Perfil de la empresa</h3>
      <p>Datos de contacto y responsables con la universidad.</p>
      <div class="actions">
        <a class="btn primary" href="perfil_empresa.php">🏢 Ver perfil</a>
        <a class="btn" href="soporte.php">❓ Soporte / Ayuda</a>
      </div>
    </article>
  </section>

  <p class="hint">Si tienes dudas o necesitas asistencia, visita la sección <a href="soporte.php">Soporte</a>.</p>
</main>

</body>
</html>
