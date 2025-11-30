<?php
declare(strict_types=1);

/**
 * @var array{
 *     estudianteId: ?int,
 *     estudiante: ?array<string, mixed>,
 *     empresa: ?array<string, mixed>,
 *     convenio: ?array<string, mixed>,
 *     errors: array<int, string>
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/estudiante/estudiante_view_handler.php';
require_once __DIR__ . '/../../common/helpers/estudiante/estudiante_view_helper.php';

$estudianteId = $handlerResult['estudianteId'];
$estudiante = $handlerResult['estudiante'];
$empresa = $handlerResult['empresa'];
$convenio = $handlerResult['convenio'];
$viewErrors = $handlerResult['errors'];

$metadata = estudiante_view_prepare_metadata($estudiante, $empresa, $convenio);
$hasConvenio = $metadata['convenioExiste'];
$canEdit = $estudiante !== null && !in_array('not_found', $viewErrors, true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Detalle del Estudiante | Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/modules/estudiante/estudianteview.css" />
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    <main class="main estudiante-view">

      <header class="hero">
        <div class="hero__text">
          <p class="eyebrow">Residencias profesionales</p>
          <div class="hero__headline">
            <h1>
              Detalle del estudiante
              <?php echo $estudianteId !== null ? '#' . htmlspecialchars((string) $estudianteId, ENT_QUOTES, 'UTF-8') : ''; ?>
            </h1>
            <?php if ($estudiante !== null): ?>
              <span class="<?php echo htmlspecialchars($metadata['estatusBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($metadata['estatusBadgeLabel'], ENT_QUOTES, 'UTF-8'); ?>
              </span>
            <?php endif; ?>
          </div>
          <p class="subtitle">
            Consulta la información registrada de un estudiante y su vinculación con empresa y convenio.
          </p>

          <?php if ($estudiante !== null): ?>
            <div class="hero__chips">
              <div class="chip">
                <span class="chip-label">Matrícula</span>
                <span class="chip-value"><?php echo htmlspecialchars($metadata['matricula'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <div class="chip">
                <span class="chip-label">Carrera</span>
                <span class="chip-value"><?php echo htmlspecialchars($metadata['carrera'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <div class="chip">
                <span class="chip-label">Empresa</span>
                <span class="chip-value"><?php echo htmlspecialchars($metadata['empresaNombre'], ENT_QUOTES, 'UTF-8'); ?></span>
              </div>
              <div class="chip <?php echo $hasConvenio ? 'chip--ok' : 'chip--muted'; ?>">
                <span class="chip-label">Convenio</span>
                <span class="chip-value">
                  <?php echo $hasConvenio ? htmlspecialchars($metadata['convenioFolio'], ENT_QUOTES, 'UTF-8') : 'Sin asignar'; ?>
                </span>
              </div>
            </div>
          <?php endif; ?>
        </div>
        <div class="hero__actions">
          <a href="estudiante_list.php" class="btn secondary">← Regresar</a>
          <?php if ($canEdit && $estudianteId !== null): ?>
            <a href="estudiante_edit.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn primary">Editar estudiante</a>
          <?php endif; ?>
        </div>
      </header>

      <?php if ($viewErrors !== []): ?>
        <div class="message-stack">
          <?php if (in_array('invalid_id', $viewErrors, true)): ?>
            <div class="alert alert-error">El identificador proporcionado no es válido.</div>
          <?php endif; ?>

          <?php if (in_array('database_error', $viewErrors, true)): ?>
            <div class="alert alert-error">Ocurrió un problema al consultar la base de datos. Intenta de nuevo más tarde.</div>
          <?php endif; ?>

          <?php if (in_array('not_found', $viewErrors, true) && !in_array('invalid_id', $viewErrors, true)): ?>
            <div class="alert alert-warning">No se encontró un estudiante con el identificador solicitado.</div>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if ($estudiante !== null): ?>
        <section class="card profile-card">
          <header>Ficha principal</header>
          <div class="content info-grid">
            <div class="info-block">
              <div class="info-block__title">Datos académicos</div>
              <dl class="info-list">
                <div class="info-row">
                  <dt>Nombre completo</dt>
                  <dd><?php echo htmlspecialchars($metadata['nombreCompleto'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Matrícula</dt>
                  <dd><?php echo htmlspecialchars($metadata['matricula'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Carrera</dt>
                  <dd><?php echo htmlspecialchars($metadata['carrera'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
              </dl>
            </div>

            <div class="info-block">
              <div class="info-block__title">Contacto</div>
              <dl class="info-list">
                <div class="info-row">
                  <dt>Correo institucional</dt>
                  <dd><?php echo htmlspecialchars($metadata['correoInstitucional'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Teléfono</dt>
                  <dd><?php echo htmlspecialchars($metadata['telefono'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
              </dl>
            </div>
          </div>
        </section>

        <section class="card profile-card">
          <header>Empresa y convenio</header>
          <div class="content info-grid">
            <div class="info-block">
              <div class="info-block__title">Empresa asignada</div>
              <dl class="info-list">
                <div class="info-row">
                  <dt>Empresa</dt>
                  <dd><?php echo htmlspecialchars($metadata['empresaNombre'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Contacto</dt>
                  <dd><?php echo htmlspecialchars($metadata['empresaContactoNombre'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Correo</dt>
                  <dd><?php echo htmlspecialchars($metadata['empresaContactoEmail'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Teléfono</dt>
                  <dd><?php echo htmlspecialchars($metadata['empresaTelefono'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
                <div class="info-row">
                  <dt>Dirección</dt>
                  <dd><?php echo htmlspecialchars($metadata['empresaDireccion'], ENT_QUOTES, 'UTF-8'); ?></dd>
                </div>
              </dl>
              <?php if ($metadata['empresaRepresentante'] !== 'N/D'): ?>
                <p class="note">Representante: <?php echo htmlspecialchars($metadata['empresaRepresentante'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>

            <div class="info-block">
              <div class="info-block__title">Convenio relacionado</div>
              <?php if ($hasConvenio): ?>
                <dl class="info-list">
                  <div class="info-row">
                    <dt>Folio</dt>
                    <dd><?php echo htmlspecialchars($metadata['convenioFolio'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  </div>
                  <div class="info-row">
                    <dt>Estatus</dt>
                    <dd>
                      <span class="<?php echo htmlspecialchars($metadata['convenioBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php echo htmlspecialchars($metadata['convenioBadgeLabel'], ENT_QUOTES, 'UTF-8'); ?>
                      </span>
                    </dd>
                  </div>
                  <div class="info-row">
                    <dt>Tipo</dt>
                    <dd><?php echo htmlspecialchars($metadata['convenioTipo'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  </div>
                  <div class="info-row">
                    <dt>Responsable académico</dt>
                    <dd><?php echo htmlspecialchars($metadata['convenioResponsableAcademico'], ENT_QUOTES, 'UTF-8'); ?></dd>
                  </div>
                  <div class="info-row">
                    <dt>Vigencia</dt>
                    <dd>
                      <?php echo htmlspecialchars($metadata['convenioFechaInicio'], ENT_QUOTES, 'UTF-8'); ?>
                      —
                      <?php echo htmlspecialchars($metadata['convenioFechaFin'], ENT_QUOTES, 'UTF-8'); ?>
                    </dd>
                  </div>
                </dl>
              <?php else: ?>
                <div class="empty-box">
                  <p>No hay convenio asignado a este estudiante.</p>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </section>

        <section class="card meta-card">
          <header>Seguimiento</header>
          <div class="content meta-grid">
            <div class="meta-item">
              <p class="meta-label">Estatus del estudiante</p>
              <span class="<?php echo htmlspecialchars($metadata['estatusBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>">
                <?php echo htmlspecialchars($metadata['estatusBadgeLabel'], ENT_QUOTES, 'UTF-8'); ?>
              </span>
            </div>
            <div class="meta-item">
              <p class="meta-label">Fecha de registro</p>
              <p class="meta-value"><?php echo htmlspecialchars($metadata['creadoEnLabel'], ENT_QUOTES, 'UTF-8'); ?></p>
            </div>
          </div>
        </section>
      <?php endif; ?>

      <p class="foot">Área de Vinculación - Residencias Profesionales</p>
    </main>
  </div>
</body>
</html>
