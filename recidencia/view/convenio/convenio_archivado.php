<?php
declare(strict_types=1);

$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_archivado_handler.php';

$errors = $handlerResult['errors'];
$archivo = $handlerResult['archivo'];

require_once __DIR__ . '/../../common/helpers/convenio/convenio_archivado_helper.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_archivar_helper.php';

use function Residencia\Common\Helpers\Convenio\renderSnapshotTable;
use function Residencia\Common\Helpers\Convenio\formatArchivoLabel;

$snapshot = $archivo['snapshot_data'] ?? [];
$metadataRows = isset($snapshot['metadata']) ? [$snapshot['metadata']] : [];
$snapshotSections = [
    ['title' => 'Convenio', 'data' => $snapshot['convenio'] ?? []],
    ['title' => 'Machote', 'data' => $snapshot['machote'] ?? []],
    ['title' => 'Revisiones de machote', 'data' => $snapshot['machote_revisiones'] ?? []],
    ['title' => 'Mensajes de revisión', 'data' => $snapshot['machote_revision_mensajes'] ?? []],
    ['title' => 'Archivos de revisión', 'data' => $snapshot['machote_revision_archivos'] ?? []],
    ['title' => 'Comentarios', 'data' => $snapshot['comentarios'] ?? []],
    ['title' => 'Documentos', 'data' => $snapshot['documentos'] ?? []],
    ['title' => 'Estudiantes', 'data' => $snapshot['estudiantes'] ?? []],
    ['title' => 'Asignaciones', 'data' => $snapshot['asignaciones'] ?? []],
    ['title' => 'Auditoría - Detalle', 'data' => $snapshot['auditoria_detalle'] ?? ($snapshot['bitacora'] ?? [])],
    ['title' => 'Metadatos de archivado', 'data' => $metadataRows],
];

$convenioResumen = $snapshot['convenio'][0] ?? [];
$empresaNombre = $archivo['empresa_nombre'] ?? ($convenioResumen['empresa'] ?? 'Empresa no disponible');
$folio = $convenioResumen['folio'] ?? ($convenioResumen['id'] ?? '—');
$estatusConvenio = $convenioResumen['estatus'] ?? ($convenioResumen['estatus_general'] ?? 'Archivado');
$estatusChip = $estatusConvenio !== '' ? $estatusConvenio : 'En revisión';
$fechaRegistro = $convenioResumen['creado_en'] ?? ($convenioResumen['fecha_registro'] ?? '—');
$fechaActualizacion = $convenioResumen['actualizado_en'] ?? ($convenioResumen['fecha_fin'] ?? '—');
$responsable = $convenioResumen['responsable_academico'] ?? '—';
$tipoConvenio = $convenioResumen['tipo_convenio'] ?? '—';
$fechaInicio = $convenioResumen['fecha_inicio'] ?? '—';
$fechaFin = $convenioResumen['fecha_fin'] ?? '—';

$snapshotConvenio = $snapshot['convenio'] ?? [];
$snapshotMachote = $snapshot['machote'] ?? [];
$snapshotRevisiones = $snapshot['machote_revisiones'] ?? [];
$snapshotRevisionMsgs = $snapshot['machote_revision_mensajes'] ?? [];
$snapshotRevisionFiles = $snapshot['machote_revision_archivos'] ?? [];
$snapshotComentarios = $snapshot['comentarios'] ?? [];
$snapshotDocumentos = $snapshot['documentos'] ?? [];
$snapshotEstudiantes = $snapshot['estudiantes'] ?? [];
$snapshotAsignaciones = $snapshot['asignaciones'] ?? [];
$snapshotAuditoria = $snapshot['auditoria_detalle'] ?? ($snapshot['bitacora'] ?? []);
$snapshotMetadataRaw = $snapshot['metadata'] ?? [];
$snapshotMetadata = [];
if ($snapshotMetadataRaw !== [] && is_array($snapshotMetadataRaw)) {
    $snapshotMetadata = array_values($snapshotMetadataRaw) === $snapshotMetadataRaw ? $snapshotMetadataRaw : [$snapshotMetadataRaw];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Convenio Archivado | Residencias Profesionales</title>
  <link rel="stylesheet" href="../../assets/css/modules/convenio/convenioarchiva.css" />
  <style>
    .accordion {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .accordion-item {
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      background: var(--panel);
      overflow: hidden;
    }

    .accordion-header {
      width: 100%;
      text-align: left;
      padding: 14px 16px;
      background: var(--panel-strong);
      font-weight: bold;
      cursor: pointer;
      border: none;
    }

    .accordion-body {
      padding: 16px;
      display: none;
    }

    .accordion-item.open .accordion-body {
      display: block;
    }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="main">
      <header class="page-header">
        <div class="page-title">
          <p class="eyebrow">Registro histórico</p>
          <h1>Convenio archivado</h1>
          <nav class="breadcrumb">
            <a href="../../index.php">Inicio</a>
            <span>&gt;</span>
            <a href="convenio_list.php">Convenios</a>
            <span>&gt;</span>
            <span>Archivado</span>
          </nav>
        </div>
        <div class="top-actions">
          <a href="convenio_list.php" class="btn secondary">Volver</a>
        </div>
      </header>

      <?php if ($archivo !== null): ?>
        <section class="convenio-banner">
          <div class="banner-left">
            <span class="badge archived">Archivado</span>
            <h2>Archivo #<?php echo htmlspecialchars((string) $archivo['id'], ENT_QUOTES, 'UTF-8'); ?></h2>
            <p class="subtitle">Resumen congelado del convenio y todos sus datos relacionados.</p>
            <div class="inline-meta">
              <div>
                <small>Archivado</small>
                <strong><?php echo htmlspecialchars(formatArchivoLabel($archivo['fecha_archivo']), ENT_QUOTES, 'UTF-8'); ?></strong>
              </div>
              <div>
                <small>Motivo</small>
                <strong><?php echo htmlspecialchars($archivo['motivo'] ?? 'Sin motivo registrado', ENT_QUOTES, 'UTF-8'); ?></strong>
              </div>
            </div>
          </div>
          <div class="banner-right">
            <div class="pill">
              <span class="label">Convenio original</span>
              <strong>#<?php echo htmlspecialchars((string) ($archivo['convenio_id_original'] ?? $archivo['convenio_id'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
            <div class="pill">
              <span class="label">Empresa</span>
              <strong><?php echo htmlspecialchars((string) ($archivo['empresa_nombre'] ?? 'No disponible'), ENT_QUOTES, 'UTF-8'); ?></strong>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($errors)): ?>
        <div class="alert danger" role="alert">
          <ul>
            <?php foreach ($errors as $error): ?>
              <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($archivo !== null): ?>
        <section class="card info-card">
          <header>
            <span class="icon">🗂️</span>
            <div>
              <h2>Información general del convenio</h2>
              <p class="subtitle">Resumen congelado al momento del archivado</p>
            </div>
            <span class="status-chip"><?php echo htmlspecialchars((string) $estatusChip, ENT_QUOTES, 'UTF-8'); ?></span>
          </header>
          <div class="content">
            <div class="info-grid">
              <div><strong>Empresa:</strong> <?php echo htmlspecialchars((string) $empresaNombre, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Folio / ID:</strong> <?php echo htmlspecialchars((string) $folio, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Tipo de convenio:</strong> <?php echo htmlspecialchars((string) $tipoConvenio, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Responsable académico:</strong> <?php echo htmlspecialchars((string) $responsable, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Fecha inicio:</strong> <?php echo htmlspecialchars((string) $fechaInicio, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Fecha fin:</strong> <?php echo htmlspecialchars((string) $fechaFin, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Archivado:</strong> <?php echo htmlspecialchars(formatArchivoLabel($archivo['fecha_archivo']), ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Motivo:</strong> <?php echo htmlspecialchars($archivo['motivo'] ?? 'Sin motivo registrado', ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Fecha de registro:</strong> <?php echo htmlspecialchars((string) $fechaRegistro, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Última actualización:</strong> <?php echo htmlspecialchars((string) $fechaActualizacion, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          </div>
        </section>

        <section class="snapshot">
          <h2>Snapshot del Convenio</h2>
          <p class="subtitle">Todos los datos congelados al momento del archivado</p>

          <div class="accordion">
            <div class="accordion-item">
              <button class="accordion-header">📄 Convenio</button>
              <div class="accordion-body">
                <?php echo renderSnapshotTable(is_array($snapshotConvenio) ? $snapshotConvenio : []); ?>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header">📝 Machote y Revisiones</button>
              <div class="accordion-body">
                <h4>Machote</h4>
                <?php echo renderSnapshotTable(is_array($snapshotMachote) ? $snapshotMachote : []); ?>

                <h4>Revisiones</h4>
                <?php echo renderSnapshotTable(is_array($snapshotRevisiones) ? $snapshotRevisiones : []); ?>

                <h4>Mensajes de Revisión</h4>
                <?php echo renderSnapshotTable(is_array($snapshotRevisionMsgs) ? $snapshotRevisionMsgs : []); ?>

                <h4>Archivos de Revisión</h4>
                <?php echo renderSnapshotTable(is_array($snapshotRevisionFiles) ? $snapshotRevisionFiles : []); ?>

                <h4>Comentarios</h4>
                <?php echo renderSnapshotTable(is_array($snapshotComentarios) ? $snapshotComentarios : []); ?>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header">📂 Documentos</button>
              <div class="accordion-body">
                <?php echo renderSnapshotTable(is_array($snapshotDocumentos) ? $snapshotDocumentos : []); ?>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header">👥 Estudiantes y Asignaciones</button>
              <div class="accordion-body">
                <h4>Estudiantes</h4>
                <?php echo renderSnapshotTable(is_array($snapshotEstudiantes) ? $snapshotEstudiantes : []); ?>

                <h4>Asignaciones</h4>
                <?php echo renderSnapshotTable(is_array($snapshotAsignaciones) ? $snapshotAsignaciones : []); ?>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header">🧾 Auditoría</button>
              <div class="accordion-body">
                <?php echo renderSnapshotTable(is_array($snapshotAuditoria) ? $snapshotAuditoria : []); ?>
              </div>
            </div>

            <div class="accordion-item">
              <button class="accordion-header">🕒 Metadata del Archivado</button>
              <div class="accordion-body">
                <?php echo renderSnapshotTable(is_array($snapshotMetadata) ? $snapshotMetadata : []); ?>
              </div>
            </div>
          </div>
        </section>
      <?php endif; ?>
    </main>
  </div>
</body>
<script>
  document.querySelectorAll('.accordion-header').forEach((btn) => {
    btn.addEventListener('click', () => {
      const item = btn.parentElement;
      if (item) {
        item.classList.toggle('open');
      }
    });
  });
</script>

</html>
