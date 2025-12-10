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
$machoteContenido = '';
if ($machoteResumen !== []) {
    $machoteContenido = (string) ($machoteResumen['contenido_html'] ?? $machoteResumen['contenido'] ?? '');
    if ($machoteContenido === '' && isset($machoteResumen['html'])) {
        $machoteContenido = (string) $machoteResumen['html'];
    }
}
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
                      <header>Contenido HTML</header>
                      <div class="body">
                        <?php if ($machoteContenido !== ''): ?>
                          <?php
                          $machoteDecoded = html_entity_decode($machoteContenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                          $machoteSafe = strip_tags(
                              $machoteDecoded,
                              '<p><br><strong><b><em><i><u><ul><ol><li><table><thead><tbody><tr><td><th><figure><span><div>'
                          );
                          ?>
                          <div class="html-preview"><?php echo $machoteSafe; ?></div>
                        <?php else: ?>
                          <p class="muted">Sin contenido HTML guardado en el snapshot.</p>
                        <?php endif; ?>
                      </div>
                    </div>

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
                  </div>

                  <div class="machote-panels">
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
                  </div>

                  <div class="machote-panels">
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
