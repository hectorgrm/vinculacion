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
$machoteResumen = is_array($snapshotMachote) && $snapshotMachote !== [] ? (array) ($snapshotMachote[0] ?? []) : [];
$machoteNombre = $machoteResumen['titulo'] ?? $machoteResumen['nombre'] ?? 'Machote institucional';
$machoteVersion = $machoteResumen['version'] ?? $machoteResumen['version_actual'] ?? ($machoteResumen['folio'] ?? 'N/D');
$machoteEstatus = $machoteResumen['estatus'] ?? $machoteResumen['estatus_general'] ?? 'Sin estatus';
$machoteActualizado = $machoteResumen['actualizado_en'] ?? $machoteResumen['updated_at'] ?? ($machoteResumen['fecha_actualizacion'] ?? 'N/D');
$machoteCreado = $machoteResumen['creado_en'] ?? $machoteResumen['created_at'] ?? ($machoteResumen['fecha_creacion'] ?? 'N/D');
$machoteResponsable = $machoteResumen['responsable'] ?? $machoteResumen['responsable_academico'] ?? ($machoteResumen['usuario'] ?? 'No registrado');
$machoteTieneDatos = is_array($snapshotMachote) && $snapshotMachote !== [];
$revisionesCount = is_array($snapshotRevisiones) ? count($snapshotRevisiones) : 0;
$revisionMsgsCount = is_array($snapshotRevisionMsgs) ? count($snapshotRevisionMsgs) : 0;
$revisionFilesCount = is_array($snapshotRevisionFiles) ? count($snapshotRevisionFiles) : 0;
$comentariosCount = is_array($snapshotComentarios) ? count($snapshotComentarios) : 0;
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

    .machote-wrap {
      display: grid;
      gap: 1rem;
    }

    .machote-hero {
      border: 1px solid var(--border);
      border-radius: var(--radius-xl);
      background: linear-gradient(120deg, #eef3f9 0%, #f9fbff 60%, #eef5ff 100%);
      padding: 18px 20px;
      box-shadow: var(--shadow-sm);
      display: grid;
      gap: 8px;
    }

    .machote-hero h3 {
      margin: 0;
      color: var(--ink);
      font-size: 1.35rem;
    }

    .machote-hero p {
      margin: 0;
      color: var(--muted);
    }

    .machote-tags {
      display: flex;
      gap: 8px;
      flex-wrap: wrap;
    }

    .machote-tag {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: 999px;
      padding: 6px 12px;
      font-weight: 700;
      color: var(--ink);
      font-size: 13px;
      box-shadow: var(--shadow-sm);
    }

    .machote-tag.neutral {
      background: #f8fafc;
    }

    .machote-stats {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
      gap: 12px;
    }

    .machote-stat {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 12px 14px;
      box-shadow: var(--shadow-sm);
      display: grid;
      gap: 4px;
    }

    .machote-stat small {
      text-transform: uppercase;
      letter-spacing: 0.05em;
      font-size: 12px;
      color: var(--muted);
      font-weight: 700;
    }

    .machote-stat strong {
      font-size: 1.1rem;
      color: var(--ink);
    }

    .machote-stat span {
      color: var(--muted);
      font-size: 13px;
    }

    .machote-panels {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 14px;
    }

    .panel-card {
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      background: #ffffff;
      box-shadow: var(--shadow-sm);
      display: grid;
      overflow: hidden;
    }

    .panel-card header {
      padding: 14px 16px;
      background: var(--panel);
      border-bottom: 1px solid var(--border);
      font-weight: 800;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 8px;
    }

    .panel-card .body {
      padding: 14px 16px;
      display: grid;
      gap: 10px;
    }

    .mini-meta {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 8px 12px;
      color: var(--muted);
      font-size: 14px;
    }

    .mini-meta strong {
      color: var(--ink);
    }

    .mini-timeline {
      list-style: none;
      padding: 0;
      margin: 0;
      display: grid;
      gap: 12px;
    }

    .mini-timeline li {
      display: grid;
      grid-template-columns: 16px 1fr;
      gap: 10px;
      align-items: start;
    }

    .mini-timeline .dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      background: var(--primary);
      margin-top: 4px;
    }

    .mini-timeline .title {
      margin: 0;
      font-weight: 800;
      color: var(--ink);
    }

    .mini-timeline .meta {
      margin: 2px 0 0;
      color: var(--muted);
      font-size: 13px;
    }

    .file-chips {
      display: grid;
      gap: 8px;
    }

    .file-chip {
      border: 1px dashed var(--border);
      background: var(--panel);
      padding: 10px 12px;
      border-radius: var(--radius-md);
      display: flex;
      justify-content: space-between;
      gap: 10px;
      align-items: center;
    }

    .file-chip span {
      color: var(--muted);
      font-size: 13px;
    }

    details.raw-block {
      border: 1px dashed var(--border);
      border-radius: var(--radius-sm);
      padding: 8px 10px;
      background: var(--panel);
    }

    details.raw-block summary {
      cursor: pointer;
      font-weight: 700;
      color: var(--primary);
    }

    .muted {
      color: var(--muted);
    }

    .html-preview {
      max-height: 420px;
      overflow: auto;
      padding: 12px 14px;
      border: 1px solid var(--border);
      border-radius: var(--radius-sm);
      background: #ffffff;
      box-shadow: var(--shadow-sm);
      line-height: 1.6;
      color: var(--ink);
    }

    .html-preview p {
      margin: 0 0 10px;
    }

    .html-preview table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 6px;
    }

    .html-preview th,
    .html-preview td {
      border: 1px solid var(--border);
      padding: 6px;
      vertical-align: top;
    }

    .html-preview strong {
      color: var(--ink);
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
              <button class="accordion-header">📝 Machote y revisiones</button>
              <div class="accordion-body">
                <div class="machote-wrap">
                  <div class="machote-hero">
                    <p class="eyebrow">Machote congelado</p>
                    <h3><?php echo htmlspecialchars((string) $machoteNombre, ENT_QUOTES, 'UTF-8'); ?></h3>
                    <p>Versión <?php echo htmlspecialchars((string) $machoteVersion, ENT_QUOTES, 'UTF-8'); ?> guardada al momento del archivado.</p>
                    <div class="machote-tags">
                      <span class="machote-tag neutral">Estatus: <?php echo htmlspecialchars((string) $machoteEstatus, ENT_QUOTES, 'UTF-8'); ?></span>
                      <span class="machote-tag">Responsable: <?php echo htmlspecialchars((string) $machoteResponsable, ENT_QUOTES, 'UTF-8'); ?></span>
                      <span class="machote-tag">Actualizado: <?php echo htmlspecialchars((string) $machoteActualizado, ENT_QUOTES, 'UTF-8'); ?></span>
                    </div>
                  </div>

                  <div class="machote-stats">
                    <div class="machote-stat">
                      <small>Revisiones</small>
                      <strong><?php echo htmlspecialchars((string) $revisionesCount, ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span>Registros en el historial de ajustes.</span>
                    </div>
                    <div class="machote-stat">
                      <small>Mensajes</small>
                      <strong><?php echo htmlspecialchars((string) $revisionMsgsCount, ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span>Comentarios intercambiados durante la revisión.</span>
                    </div>
                    <div class="machote-stat">
                      <small>Archivos</small>
                      <strong><?php echo htmlspecialchars((string) $revisionFilesCount, ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span>Adjuntos revisados o cargados.</span>
                    </div>
                    <div class="machote-stat">
                      <small>Comentarios</small>
                      <strong><?php echo htmlspecialchars((string) $comentariosCount, ENT_QUOTES, 'UTF-8'); ?></strong>
                      <span>Notas generales sobre el convenio.</span>
                    </div>
                  </div>

                  <div class="machote-panels">
                    <div class="panel-card">
                      <header>Detalle del machote</header>
                      <div class="body">
                        <?php if ($machoteTieneDatos): ?>
                          <div class="mini-meta">
                            <div><strong>Creado:</strong> <?php echo htmlspecialchars((string) $machoteCreado, ENT_QUOTES, 'UTF-8'); ?></div>
                            <div><strong>Actualizado:</strong> <?php echo htmlspecialchars((string) $machoteActualizado, ENT_QUOTES, 'UTF-8'); ?></div>
                            <div><strong>Responsable:</strong> <?php echo htmlspecialchars((string) $machoteResponsable, ENT_QUOTES, 'UTF-8'); ?></div>
                          </div>
                          <details class="raw-block">
                            <summary>Ver tabla completa</summary>
                            <?php echo renderSnapshotTable(is_array($snapshotMachote) ? $snapshotMachote : []); ?>
                          </details>
                        <?php else: ?>
                          <p class="muted">No se encontraron datos del machote en el snapshot.</p>
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="panel-card">
                      <header>Revisiones</header>
                      <div class="body">
                        <?php if ($revisionesCount > 0): ?>
                          <p class="muted">Historial de ajustes registrados en el machote.</p>
                          <?php echo renderSnapshotTable(is_array($snapshotRevisiones) ? $snapshotRevisiones : []); ?>
                        <?php else: ?>
                          <p class="muted">Sin revisiones registradas en el snapshot.</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <div class="machote-panels">
                    <div class="panel-card">
                      <header>Mensajes de revisión</header>
                      <div class="body">
                        <?php if (is_array($snapshotRevisionMsgs) && $snapshotRevisionMsgs !== []): ?>
                          <ul class="mini-timeline">
                            <?php foreach (array_slice($snapshotRevisionMsgs, 0, 4) as $revisionMensaje): ?>
                              <?php
                              $mensajeTexto = $revisionMensaje['mensaje'] ?? $revisionMensaje['comentario'] ?? $revisionMensaje['contenido'] ?? 'Mensaje sin texto';
                              $mensajeAutor = $revisionMensaje['autor'] ?? $revisionMensaje['usuario'] ?? $revisionMensaje['creado_por'] ?? 'Autor no registrado';
                              $mensajeFecha = $revisionMensaje['creado_en'] ?? $revisionMensaje['created_at'] ?? $revisionMensaje['fecha'] ?? '';
                              ?>
                              <li>
                                <span class="dot"></span>
                                <div>
                                  <p class="title"><?php echo htmlspecialchars((string) $mensajeTexto, ENT_QUOTES, 'UTF-8'); ?></p>
                                  <p class="meta">
                                    <?php echo htmlspecialchars((string) $mensajeAutor, ENT_QUOTES, 'UTF-8'); ?>
                                    <?php if ($mensajeFecha !== ''): ?>
                                      · <?php echo htmlspecialchars((string) $mensajeFecha, ENT_QUOTES, 'UTF-8'); ?>
                                    <?php endif; ?>
                                  </p>
                                </div>
                              </li>
                            <?php endforeach; ?>
                          </ul>
                          <details class="raw-block">
                            <summary>Ver todos los mensajes en tabla</summary>
                            <?php echo renderSnapshotTable(is_array($snapshotRevisionMsgs) ? $snapshotRevisionMsgs : []); ?>
                          </details>
                        <?php else: ?>
                          <p class="muted">No hay mensajes asociados a las revisiones.</p>
                        <?php endif; ?>
                      </div>
                    </div>

                    <div class="panel-card">
                      <header>Archivos de revisión</header>
                      <div class="body">
                        <?php if (is_array($snapshotRevisionFiles) && $snapshotRevisionFiles !== []): ?>
                          <div class="file-chips">
                            <?php foreach ($snapshotRevisionFiles as $revisionFile): ?>
                              <?php
                              $archivoLabel = $revisionFile['nombre_original'] ?? $revisionFile['nombre'] ?? $revisionFile['archivo'] ?? 'Archivo adjunto';
                              $archivoFecha = $revisionFile['creado_en'] ?? $revisionFile['created_at'] ?? $revisionFile['fecha'] ?? '';
                              ?>
                              <div class="file-chip">
                                <strong><?php echo htmlspecialchars((string) $archivoLabel, ENT_QUOTES, 'UTF-8'); ?></strong>
                                <span><?php echo htmlspecialchars((string) ($archivoFecha !== '' ? $archivoFecha : 'Fecha no disponible'), ENT_QUOTES, 'UTF-8'); ?></span>
                              </div>
                            <?php endforeach; ?>
                          </div>
                          <details class="raw-block">
                            <summary>Ver tabla completa</summary>
                            <?php echo renderSnapshotTable(is_array($snapshotRevisionFiles) ? $snapshotRevisionFiles : []); ?>
                          </details>
                        <?php else: ?>
                          <p class="muted">No hay archivos cargados para estas revisiones.</p>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>

                  <div class="panel-card">
                    <header>Comentarios</header>
                    <div class="body">
                      <?php if (is_array($snapshotComentarios) && $snapshotComentarios !== []): ?>
                        <?php echo renderSnapshotTable(is_array($snapshotComentarios) ? $snapshotComentarios : []); ?>
                      <?php else: ?>
                        <p class="muted">Sin comentarios adicionales en este archivo.</p>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
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
