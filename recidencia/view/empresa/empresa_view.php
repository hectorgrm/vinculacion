<?php
declare(strict_types=1);

/** @var array{
 *     empresaId: ?int,
 *     empresa: ?array<string, mixed>,
 *     conveniosActivos: array<int, array<string, mixed>>,
 *     controllerError: ?string,
 *     notFoundMessage: ?string,
 *     inputError: ?string,
 *     machoteData: ?array<string, mixed>,
 *     successMessage: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/empresa/empresa_view_handler.php';
$auditoriaHandlerResult = require __DIR__ . '/../../handler/empresa/empresa_auditoria_handler.php';
require_once __DIR__ . '/../../common/helpers/empresa/empresa_view_helper.php';

$preparedData = empresaViewTemplateHelper($handlerResult, $auditoriaHandlerResult);

$empresaId = $preparedData['empresaId'];
$empresa = $preparedData['empresa'];
$controllerError = $preparedData['controllerError'];
$notFoundMessage = $preparedData['notFoundMessage'];
$inputError = $preparedData['inputError'];
$successMessage = $preparedData['successMessage'] ?? null;
$conveniosActivos = $preparedData['conveniosActivos'] ?? [];
$documentos = $preparedData['documentos'] ?? [];
$documentosStats = $preparedData['documentosStats'] ?? [];
$documentosGestionUrl = $preparedData['documentosGestionUrl'] ?? null;
$machoteData = $preparedData['machoteData'] ?? null;
$auditoriaItems = $preparedData['auditoriaItems'] ?? [];
$auditoriaControllerError = $preparedData['auditoriaControllerError'] ?? null;
$auditoriaInputError = $preparedData['auditoriaInputError'] ?? null;
$nombre = $preparedData['nombre'] ?? 'Sin datos';
$rfc = $preparedData['rfc'] ?? 'N/A';
$representante = $preparedData['representante'] ?? 'No especificado';
$telefono = $preparedData['telefono'] ?? 'No registrado';
$correo = $preparedData['correo'] ?? 'No registrado';
$estatusClass = $preparedData['estatusClass'] ?? 'badge secondary';
$estatusLabel = $preparedData['estatusLabel'] ?? 'Sin estatus';
$creadoEn = $preparedData['creadoEn'] ?? 'N/A';
$actualizadoEn = $preparedData['actualizadoEn'] ?? 'Sin actualizar';
$numeroControl = $preparedData['numeroControl'] ?? '';
$logoUrl = $preparedData['logoUrl'] ?? null;
$empresaSubtitulo = $preparedData['empresaSubtitulo'] ?? 'RFC: ' . $rfc;
$logoAltText = $preparedData['logoAltText'] ?? ('Logotipo de ' . $nombre);
$logoUploadAction = $preparedData['logoUploadAction'] ?? '../../handler/empresa/empresa_logo_upload_handler.php';
$logoBaseUrl = $preparedData['logoBaseUrl'] ?? '../../';
$canUploadLogo = $preparedData['canUploadLogo'] ?? false;
$empresaIdQuery = $preparedData['empresaIdQuery'] ?? '';
$nuevoConvenioUrl = $preparedData['nuevoConvenioUrl'] ?? '../convenio/convenio_add.php';
$nuevoEstudianteUrl = $preparedData['nuevoEstudianteUrl'] ?? '../estudiante/estudiante_add.php';
$empresaProgresoUrl = $preparedData['empresaProgresoUrl'] ?? 'empresa_progreso.php';
$empresaEditUrl = $preparedData['empresaEditUrl'] ?? 'empresa_edit.php';
$empresaDeleteUrl = $preparedData['empresaDeleteUrl'] ?? 'empresa_delete.php';
$docsTotal = $preparedData['docsTotal'] ?? 0;
$docsSubidos = $preparedData['docsSubidos'] ?? 0;
$docsAprobados = $preparedData['docsAprobados'] ?? 0;
$progreso = $preparedData['progreso'] ?? 0;
$auditoriaHasOverflow = $preparedData['auditoriaHasOverflow'] ?? false;
$auditoriaVisibleLimit = $preparedData['auditoriaVisibleLimit'] ?? 5;
$empresaIsEnRevision = $preparedData['empresaIsEnRevision'] ?? false;
$estudiantes = $preparedData['estudiantes'] ?? [];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle de Empresa · Residencias Profesionales</title>

  <!-- Si ya tienes estilos específicos para esta vista, mantenlos: -->
  <link rel="stylesheet" href="../../assets/css/empresas/empresaview.css">
  <link rel="stylesheet" href="../../assets/css/empresas/empresalogo.css">


</head>

<body>
  <div class="app">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <!-- Main -->
    <main class="main">
      <?php if ($successMessage !== null && $successMessage !== ''): ?>
        <div class="alert alert-success" style="margin: 0 0 16px;">
          <?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>
        </div>
        <div id="empresa-success-toast"
             data-toast-message="<?php echo htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8'); ?>"
             hidden></div>
      <?php endif; ?>
  <!-- 🏢 Banner con logotipo y lápiz -->
  <section class="empresa-banner">
    <div class="empresa-brand">
      <div class="empresa-logo" data-logo-container>
        <?php if ($logoUrl !== null) : ?>
          <img src="<?php echo htmlspecialchars($logoUrl, ENT_QUOTES, 'UTF-8'); ?>"
               alt="<?php echo htmlspecialchars($logoAltText, ENT_QUOTES, 'UTF-8'); ?>"
               class="empresa-logo__image"
               data-logo-image>
        <?php else : ?>
          <div class="empresa-placeholder" data-logo-placeholder aria-hidden="true">🏢</div>
        <?php endif; ?>

        <?php if ($canUploadLogo) : ?>
          <form id="logo-upload-form"
                class="logo-upload-form"
                action="<?php echo htmlspecialchars($logoUploadAction, ENT_QUOTES, 'UTF-8'); ?>"
                method="post"
                enctype="multipart/form-data"
                data-logo-base-url="<?php echo htmlspecialchars($logoBaseUrl, ENT_QUOTES, 'UTF-8'); ?>"
                data-logo-alt="<?php echo htmlspecialchars($logoAltText, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="empresa_id" value="<?php echo htmlspecialchars($empresaIdQuery, ENT_QUOTES, 'UTF-8'); ?>">
            <label for="logo-upload-input"
                   class="upload-btn"
                   id="logo-upload-button"
                   title="Cambiar logotipo"
                   data-logo-button>
              ✏️
              <input type="file"
                     name="logo"
                     id="logo-upload-input"
                     accept="image/png,image/jpeg"
                     data-logo-input>
            </label>
          </form>
        <?php endif; ?>
      </div>

      <div class="empresa-titles">
        <h1><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></h1>
        <p><?php echo htmlspecialchars($empresaSubtitulo, ENT_QUOTES, 'UTF-8'); ?></p>
      </div>
    </div>

    <div class="empresa-actions">
      <a href="<?php echo htmlspecialchars($empresaProgresoUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn">📊 Ver Progreso</a>
      <a href="empresa_list.php" class="btn secondary">⬅ Volver</a>
    </div>
  </section>

  <?php if ($canUploadLogo) : ?>
    <div id="logo-upload-feedback" class="logo-upload-feedback" aria-live="polite" hidden></div>

    <p class="empresa-logo-hint">
      💡 <strong>Tip:</strong> Haz clic en el lápiz para seleccionar una imagen PNG o JPG.
      El logotipo se actualizará al instante sin recargar la página.
    </p>
  <?php endif; ?>


      <!-- 🏢 Información General -->
      <section class="card">
        <header>🏢 Información General de la Empresa</header>
        <div class="content empresa-info">
          <?php if ($controllerError !== null || $inputError !== null || $notFoundMessage !== null) : ?>
            <div class="alert error" role="alert">
              <?php
              $message = $controllerError ?? $inputError ?? $notFoundMessage ?? 'No se pudo cargar la información de la empresa.';
              echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
              ?>
            </div>
          <?php else : ?>
            <div class="info-grid">
              <div><strong>Nombre:</strong> <?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>RFC:</strong> <?php echo htmlspecialchars($rfc, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Representante Legal:</strong> <?php echo htmlspecialchars($representante, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Teléfono:</strong> <?php echo htmlspecialchars($telefono, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Correo:</strong> <?php echo htmlspecialchars($correo, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Estatus:</strong> <span class="<?php echo htmlspecialchars($estatusClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusLabel, ENT_QUOTES, 'UTF-8'); ?></span></div>
              <div><strong>Fecha de Registro:</strong> <?php echo htmlspecialchars($creadoEn, ENT_QUOTES, 'UTF-8'); ?></div>
              <div><strong>Última actualización:</strong> <?php echo htmlspecialchars($actualizadoEn, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- 📜 Convenios asociados -->
      <section class="card">
        <header>📜 Convenios activos y en revisión</header>
        <div class="content<?php echo $empresaIsEnRevision ? ' content--dimmed' : ''; ?>">
          <?php if ($empresaIsEnRevision): ?>
            <p class="section-note">
              Para agregar un convenio la empresa tiene que estar activa.
            </p>
          <?php endif; ?>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Responsable académico</th>
                <th>Inicio</th>
                <th>Fin</th>
                <th>Estatus</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($controllerError !== null || $inputError !== null || $notFoundMessage !== null): ?>
                <tr>
                  <td colspan="6" style="text-align:center;">No hay datos de convenios disponibles.</td>
                </tr>
              <?php elseif ($conveniosActivos === []): ?>
                <tr>
                  <td colspan="6" style="text-align:center;">No existen convenios activos o en revisión registrados para esta empresa.</td>
                </tr>
              <?php else: ?>
                <?php foreach ($conveniosActivos as $convenio): ?>
                  <?php
                  $convenioIdLabel = (string) ($convenio['id_label'] ?? '#');
                  $convenioResponsable = (string) ($convenio['responsable_label'] ?? 'Sin responsable');
                  $convenioInicio = (string) ($convenio['fecha_inicio_label'] ?? 'N/A');
                  $convenioFin = (string) ($convenio['fecha_fin_label'] ?? 'N/A');
                  $convenioEstatusClass = (string) ($convenio['estatus_badge_class'] ?? 'badge secondary');
                  $convenioEstatusLabel = (string) ($convenio['estatus_badge_label'] ?? 'Sin estatus');
                  $convenioViewUrl = isset($convenio['view_url']) ? (string) $convenio['view_url'] : null;
                  $convenioEditUrl = isset($convenio['edit_url']) ? (string) $convenio['edit_url'] : null;
                  ?>
                  <tr>
                    <td><?php echo htmlspecialchars($convenioIdLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioResponsable, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioInicio, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td><?php echo htmlspecialchars($convenioFin, ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                      <span class="<?php echo htmlspecialchars($convenioEstatusClass, ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($convenioEstatusLabel, ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </td>
                    <td>
                      <?php if ($convenioViewUrl !== null): ?>
                        <a href="<?php echo htmlspecialchars($convenioViewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">👁️ Ver</a>
                      <?php endif; ?>
                      <?php if ($convenioEditUrl !== null): ?>
                        <a href="<?php echo htmlspecialchars($convenioEditUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small">✏️ Editar</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          <?php if (!$empresaIsEnRevision): ?>
            <div class="actions" style="margin-top:16px; justify-content:flex-end;">
              <a href="<?php echo htmlspecialchars($nuevoConvenioUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">➕ Nuevo Convenio</a>
            </div>
          <?php endif; ?>
        </div>
      </section>

      <!-- 💬 Revisión de Machote -->
      <section class="card">
        <header>💬 Revisión de Machote</header>
        <div class="content">
          <?php if ($machoteData === null) : ?>
            <p style="margin:0; color:#475569;">
              Esta empresa aún no tiene un machote registrado o se encuentra en preparación.
            </p>
          <?php else : ?>
            <section class="kpis">
              <div class="kpi">
                <h4>Comentarios abiertos</h4>
                <div class="kpi-num"><?php echo (int) ($machoteData['pendientes'] ?? 0); ?></div>
              </div>

              <div class="kpi">
                <h4>Comentarios resueltos</h4>
                <div class="kpi-num"><?php echo (int) ($machoteData['resueltos'] ?? 0); ?></div>
              </div>

              <div class="kpi wide">
                <h4>Avance de la revisión</h4>
                <div class="progress">
                  <div class="bar" style="width: <?php echo (int) ($machoteData['progreso'] ?? 0); ?>%"></div>
                </div>
                <small><?php echo (int) ($machoteData['progreso'] ?? 0); ?>% completado</small>
              </div>

              <div class="kpi">
                <h4>Estado</h4>
                <div>
                  <span class="badge <?php echo htmlspecialchars((string) ($machoteData['estado_class'] ?? 'pendiente'), ENT_QUOTES, 'UTF-8'); ?>">
                    <?php echo htmlspecialchars((string) ($machoteData['estado'] ?? 'Pendiente'), ENT_QUOTES, 'UTF-8'); ?>
                  </span>
                </div>
              </div>
            </section>

            <div class="actions" style="margin-top:16px; justify-content:flex-start;">
              <a href="../machote/machote_revisar.php?id=<?php echo (int) ($machoteData['id'] ?? 0); ?>" class="btn primary">
                Ir al Machote / Comentarios
              </a>
            </div>
          <?php endif; ?>
        </div>
      </section>
      

      <!-- 📂 Documentación Legal -->
      <section class="card">
        <header>
          📂 Documentación Legal
          <span class="subtitle">Control de documentos requeridos por Vinculación</span>
        </header>

        <div class="content">
          <?php if ($empresaIsEnRevision): ?>
            <p class="section-note">
              Esta empresa aún está en revisión; no es posible subir documentos hasta que esté activa.
            </p>
          <?php endif; ?>
          <div class="docs-summary" style="margin-bottom:15px; display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
            <div style="flex:1;">
              <strong>📄 Documentos requeridos:</strong> <?php echo htmlspecialchars((string) $docsTotal, ENT_QUOTES, 'UTF-8'); ?><br>
              <strong>📤 Subidos:</strong> <?php echo htmlspecialchars((string) $docsSubidos, ENT_QUOTES, 'UTF-8'); ?>
              <strong>✅ Aprobados:</strong> <?php echo htmlspecialchars((string) $docsAprobados, ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <div style="flex:1;">
              <label style="font-weight:600;">Progreso general:</label>
              <div style="background:#eee; border-radius:8px; overflow:hidden; height:10px; margin-top:4px;">
                <div style="width:<?php echo htmlspecialchars((string) $progreso, ENT_QUOTES, 'UTF-8'); ?>%; height:10px; background:#4caf50;"></div>
              </div>
              <small><?php echo htmlspecialchars((string) $progreso, ENT_QUOTES, 'UTF-8'); ?>% completado</small>
            </div>
          </div>

          <!-- 🧾 Tabla de resumen de documentos -->
          <table>
            <thead>
              <tr>
                <th>Documento</th>
                <th>Estado</th>
                <th>Última actualización</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($documentos === []) : ?>
                <tr>
                  <td colspan="4" style="text-align:center;">No hay documentos configurados para esta empresa.</td>
                </tr>
              <?php else : ?>
                <?php foreach ($documentos as $documento) : ?>
                  <?php
                  if (!is_array($documento)) {
                      continue;
                  }

                  $documentoNombre = (string) ($documento['nombre'] ?? 'Documento');
                  $documentoOpcional = isset($documento['obligatorio']) ? !((bool) $documento['obligatorio']) : false;
                  $documentoEstatus = (string) ($documento['estatus'] ?? 'pendiente');
                  $documentoEstadoClass = (string) ($documento['estatus_badge_class'] ?? 'badge pendiente');
                  $documentoEstadoLabel = (string) ($documento['estatus_label'] ?? 'Pendiente');
                  $documentoActualizado = (string) ($documento['ultima_actualizacion_label'] ?? '—');
                  $accionUrl = isset($documento['accion_url']) && is_string($documento['accion_url']) ? trim($documento['accion_url']) : '';
                  $accionLabel = (string) ($documento['accion_label'] ?? 'Ver');
                  $accionVariant = (string) ($documento['accion_variant'] ?? 'view');
                  $accionClass = $accionVariant === 'view' ? 'btn small' : 'btn small primary';
                  $accionPrefix = $accionVariant === 'view' ? '📄 ' : '📁 ';
                  $accionAttrs = $accionVariant === 'view' ? ' target="_blank" rel="noopener noreferrer"' : '';
                  $uploadUrl = isset($documento['upload_url']) && is_string($documento['upload_url'])
                      ? trim($documento['upload_url'])
                      : '';
                  $detailUrl = isset($documento['detail_url']) && is_string($documento['detail_url'])
                      ? trim($documento['detail_url'])
                      : '';
                  $reviewUrl = isset($documento['review_url']) && is_string($documento['review_url'])
                      ? trim($documento['review_url'])
                      : '';
                  ?>
                  <tr>
                    <td>
                      <?php echo htmlspecialchars($documentoNombre, ENT_QUOTES, 'UTF-8'); ?>
                      <?php if ($documentoOpcional) : ?>
                        <span class="badge secondary">Opcional</span>
                      <?php endif; ?>
                    </td>
                    <td><span class="<?php echo htmlspecialchars($documentoEstadoClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($documentoEstadoLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                    <td><?php echo htmlspecialchars($documentoActualizado !== '' ? $documentoActualizado : '—', ENT_QUOTES, 'UTF-8'); ?></td>
                    <td>
                      <?php if (
                          $accionUrl !== '' &&
                          !($empresaIsEnRevision && $accionVariant === 'upload')
                      ) : ?>
                        <a href="<?php echo htmlspecialchars($accionUrl, ENT_QUOTES, 'UTF-8'); ?>" class="<?php echo htmlspecialchars($accionClass, ENT_QUOTES, 'UTF-8'); ?>"<?php echo $accionAttrs; ?>><?php echo htmlspecialchars($accionPrefix . $accionLabel, ENT_QUOTES, 'UTF-8'); ?></a>
                      <?php endif; ?>
                      <?php if (!$empresaIsEnRevision && $accionVariant === 'view' && $uploadUrl !== '' && $documentoEstatus !== 'aprobado' && $documentoEstatus !== 'revision') : ?>
                        <a href="<?php echo htmlspecialchars($uploadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary" style="margin-left:6px;">📁 Subir</a>
                      <?php endif; ?>
                      <?php if ($documentoEstatus === 'aprobado' && $detailUrl !== '') : ?>
                        <a href="<?php echo htmlspecialchars($detailUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary" style="margin-left:6px;">🔍 Detalle</a>
                      <?php endif; ?>
                      <?php if ($documentoEstatus === 'revision' && $reviewUrl !== '') : ?>
                        <a href="<?php echo htmlspecialchars($reviewUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn small primary" style="margin-left:6px;">📝 Revisar</a>
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>

          
          <!-- 🔗 Acción principal -->
          <?php if (!$empresaIsEnRevision && $documentosGestionUrl !== null && $documentosGestionUrl !== '') : ?>
            <div class="actions" style="margin-top:16px; justify-content:flex-end;">
              <a href="<?php echo htmlspecialchars($documentosGestionUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">📁 Gestionar Documentos</a>
            </div>
          <?php endif; ?>
        </div>

        
      </section>


      <!-- 🎓 Estudiantes vinculados -->
      <section class="card">
        <header>🎓 Estudiantes que realizaron Residencia</header>
        <div class="content">
          <div class="table-actions">
            <div>
              <strong><?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?></strong>
              <span class="subtitle">Listado de estudiantes con estatus activo o finalizado.</span>
            </div>
            <a href="<?php echo htmlspecialchars($nuevoEstudianteUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">➕ Agregar estudiante</a>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>Nombre</th>
                  <th>Matrícula</th>
                  <th>Carrera</th>
                  <th>Convenio</th>
                  <th>Resultado</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!is_array($estudiantes) || $estudiantes === []) : ?>
                  <tr>
                    <td colspan="5" style="text-align:center;">Aún no hay estudiantes vinculados a esta empresa.</td>
                  </tr>
                <?php else : ?>
                  <?php foreach ($estudiantes as $estudiante) : ?>
                    <?php
                    if (!is_array($estudiante)) {
                        continue;
                    }

                    $nombreEstudiante = (string) ($estudiante['nombre_completo'] ?? 'Sin nombre');
                    $matricula = (string) ($estudiante['matricula'] ?? '—');
                    $carrera = (string) ($estudiante['carrera'] ?? '—');
                    $convenioLabel = (string) ($estudiante['convenio_label'] ?? 'Sin convenio');
                    $resultadoClass = (string) ($estudiante['estatus_badge_class'] ?? 'badge secondary');
                    $resultadoLabel = (string) ($estudiante['estatus_badge_label'] ?? 'Sin estatus');
                    ?>
                    <tr>
                      <td><?php echo htmlspecialchars($nombreEstudiante, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($matricula, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($carrera, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><?php echo htmlspecialchars($convenioLabel, ENT_QUOTES, 'UTF-8'); ?></td>
                      <td><span class="<?php echo htmlspecialchars($resultadoClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($resultadoLabel, ENT_QUOTES, 'UTF-8'); ?></span></td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <section class="card">
        <header>🕒 Bitácora / Historial</header>
        <div class="content">

          <?php if ($auditoriaControllerError !== null || $auditoriaInputError !== null) : ?>
            <div class="alert error" role="alert">
              <?php
              $message = $auditoriaControllerError
                ?? $auditoriaInputError
                ?? 'No se pudo cargar el historial de la empresa.';
              echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
              ?>
            </div>

          <?php elseif ($auditoriaItems === []) : ?>
            <p style="margin:0;">No se han registrado movimientos de auditoría para esta empresa.</p>

          <?php else : ?>
            <div class="audit-table-wrapper" aria-label="Historial de auditoría">
              <table class="audit-table">
                <thead>
                  <tr>
                    <th style="width:160px;">Fecha</th>
                    <th style="width:140px;">Actor</th>
                    <th>Evento</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($auditoriaItems as $item) : ?>
                  <?php
                  if (!is_array($item)) {
                      continue;
                  }

                  $fecha = htmlspecialchars((string) ($item['fecha'] ?? '—'), ENT_QUOTES, 'UTF-8');
                  $mensaje = htmlspecialchars((string) ($item['mensaje'] ?? 'Acción registrada'), ENT_QUOTES, 'UTF-8');
                  $actorNombre = htmlspecialchars((string) ($item['actor_label'] ?? '—'), ENT_QUOTES, 'UTF-8');
                  $detalleItems = isset($item['detalles']) && is_array($item['detalles']) ? $item['detalles'] : [];
                  ?>
                    <tr>
                      <td><?php echo $fecha; ?></td>
                      <td><?php echo $actorNombre; ?></td>
                      <td>
                        <div class="audit-event">
                          <div class="audit-event__message"><?php echo $mensaje; ?></div>
                          <?php if ($detalleItems !== []) : ?>
                            <ul class="audit-details">
                              <?php foreach ($detalleItems as $detalle) : ?>
                                <li>
                                  <span class="audit-details__field"><?php echo htmlspecialchars($detalle['label'], ENT_QUOTES, 'UTF-8'); ?></span>
                                  <div class="audit-details__values">
                                    <span><strong>Antes:</strong> <?php echo htmlspecialchars($detalle['antes'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <span><strong>Ahora:</strong> <?php echo htmlspecialchars($detalle['despues'], ENT_QUOTES, 'UTF-8'); ?></span>
                                  </div>
                                </li>
                              <?php endforeach; ?>
                            </ul>
                          <?php else : ?>
                            <p class="audit-details__empty">Este evento no registró cambios detallados.</p>
                          <?php endif; ?>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <?php if ($auditoriaHasOverflow) : ?>
              <p style="margin-top:8px; color:#555;">
                Se muestran aproximadamente <?php echo $auditoriaVisibleLimit; ?> registros a la vez.
                Desplázate dentro de la tabla para ver los <?php echo count($auditoriaItems); ?> movimientos registrados.
              </p>
            <?php endif; ?>
          <?php endif; ?>

        </div>
      </section>

      <!-- 🔧 Acciones -->
      <section class="card">
        <div class="content actions" style="justify-content:flex-end;">
          <a href="<?php echo htmlspecialchars($empresaEditUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">✏️ Editar Empresa</a>
          <a href="<?php echo htmlspecialchars($empresaDeleteUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn danger">🗑️ Eliminar Empresa</a>
        </div>
      </section>

      <?php if ($canUploadLogo) : ?>
        <script>
          (() => {
            const form = document.getElementById('logo-upload-form');
            if (!form) {
              return;
            }

            const input = form.querySelector('[data-logo-input]');
            if (!input) {
              return;
            }

            const button = form.querySelector('[data-logo-button]');
            const feedback = document.getElementById('logo-upload-feedback');
            const container = document.querySelector('[data-logo-container]');
            const baseUrl = form.dataset.logoBaseUrl || '';
            const altText = form.dataset.logoAlt || '';

            const normalizeBaseUrl = (base) => {
              if (!base) {
                return '';
              }

              return base.endsWith('/') ? base : `${base}/`;
            };

            const setMessage = (message, type) => {
              if (!feedback) {
                return;
              }

              feedback.textContent = message || '';
              feedback.hidden = !message;
              feedback.classList.remove('logo-upload-feedback--success', 'logo-upload-feedback--error');

              if (!message) {
                return;
              }

              feedback.classList.add(type === 'success' ? 'logo-upload-feedback--success' : 'logo-upload-feedback--error');
            };

            const setLoading = (isLoading) => {
              if (button) {
                button.classList.toggle('is-loading', isLoading);
                button.setAttribute('aria-busy', isLoading ? 'true' : 'false');
              }

              input.disabled = isLoading;
            };

            const updateLogo = (relativePath) => {
              if (!relativePath || !container) {
                return;
              }

              const normalizedBase = normalizeBaseUrl(baseUrl);
              const sanitizedPath = String(relativePath).replace(/^\/+/, '');
              const imageUrl = `${normalizedBase}${sanitizedPath}?v=${Date.now()}`;
              let image = container.querySelector('[data-logo-image]');
              const placeholder = container.querySelector('[data-logo-placeholder]');

              if (!image) {
                image = document.createElement('img');
                image.className = 'empresa-logo__image';
                image.setAttribute('data-logo-image', '');
                image.alt = altText || '';
                const referenceNode = form.parentNode === container ? form : container.querySelector('form');

                if (referenceNode) {
                  container.insertBefore(image, referenceNode);
                } else {
                  container.appendChild(image);
                }
              }

              image.src = imageUrl;

              if (altText) {
                image.alt = altText;
              }

              if (placeholder) {
                placeholder.remove();
              }
            };

            form.addEventListener('submit', (event) => {
              event.preventDefault();
            });

            input.addEventListener('change', async () => {
              if (!input.files || input.files.length === 0) {
                return;
              }

              const formData = new FormData(form);
              const file = input.files[0];

              if (file) {
                formData.set('logo', file);
              }

              setMessage('', '');
              setLoading(true);

              try {
                const response = await fetch(form.action, {
                  method: 'POST',
                  body: formData,
                  credentials: 'same-origin'
                });

                let payload = null;

                try {
                  payload = await response.json();
                } catch (jsonError) {
                  payload = null;
                }

                if (!response.ok) {
                  const errorMessage = payload && typeof payload.message === 'string'
                    ? payload.message
                    : 'No se pudo actualizar el logotipo. Intenta de nuevo.';

                  throw new Error(errorMessage);
                }

                const isSuccessful = payload && (
                  payload.success === true ||
                  payload.success === 'true' ||
                  payload.success === 1 ||
                  payload.success === '1'
                );

                if (!isSuccessful) {
                  const message = payload && typeof payload.message === 'string'
                    ? payload.message
                    : 'No se pudo actualizar el logotipo. Intenta de nuevo.';

                  throw new Error(message);
                }

                updateLogo(payload.logoPath || '');

                const successMessage = typeof payload.message === 'string' && payload.message !== ''
                  ? payload.message
                  : 'Logotipo actualizado correctamente.';

                setMessage(successMessage, 'success');
              } catch (error) {
                const message = error instanceof Error && error.message
                  ? error.message
                  : 'No se pudo actualizar el logotipo. Intenta de nuevo.';

                setMessage(message, 'error');
              } finally {
                setLoading(false);
                input.value = '';
              }
            });
          })();
        </script>
      <?php endif; ?>

    </main>
  </div>
  <?php if ($successMessage !== null && $successMessage !== ''): ?>
    <script src="../../assets/js/empresa-success-toast.js" defer></script>
  <?php endif; ?>
</body>

</html>
