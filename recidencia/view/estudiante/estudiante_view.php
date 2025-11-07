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
  <title>Detalle del Estudiante · Residencia Profesional</title>

  <link rel="stylesheet" href="../../assets/css/global.css" />
  <link rel="stylesheet" href="../../assets/css/dashboard.css" />
  <link rel="stylesheet" href="../../assets/css/modules/estudiante.css" />

  <style>
    body {
      font-family: "Inter", sans-serif;
      background: #f6f8fa;
      margin: 0;
      color: #2c3e50;
    }
    .main {
      max-width: 1100px;
      margin: 2rem auto;
      padding: 0 1.5rem;
    }
    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      gap: 1rem;
      flex-wrap: wrap;
    }
    .btn {
      background: #0055aa;
      color: white;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
    }
    .btn.secondary { background: #7f8c8d; }
    .btn.secondary:hover { background: #5f6d70; }
    .card {
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
      margin-bottom: 1.8rem;
      overflow: hidden;
    }
    .card header {
      background: #f1f3f5;
      padding: 0.8rem 1.2rem;
      font-weight: 600;
      border-bottom: 1px solid #e5e7ea;
    }
    .content {
      padding: 1.2rem;
    }
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 1.5rem;
    }
    .data-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .data-list li {
      padding: 0.4rem 0;
      border-bottom: 1px solid #ececec;
    }
    .badge {
      padding: 4px 8px;
      border-radius: 6px;
      color: #fff;
      font-size: 0.85rem;
      display: inline-block;
    }
    .badge.activo { background: #27ae60; }
    .badge.finalizado { background: #2980b9; }
    .badge.inactivo { background: #c0392b; }
    .badge.secondary {
      background: #7f8c8d;
      color: #fff;
    }
    .muted { color: #7f8c8d; font-size: 0.9rem; }
    .foot { text-align: center; color: #888; margin-top: 2rem; font-size: 0.9rem; }
    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 1rem;
      font-size: 0.95rem;
    }
    .alert.error { background: #fce8e6; color: #a50e0e; border: 1px solid #f8b4b4; }
    .alert.warning { background: #fff4e5; color: #8a5d0a; border: 1px solid #ffd6a5; }
  </style>
</head>

<body>
  <div class="app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>
    <main class="main">

      <header class="topbar">
        <div>
          <h2>👨‍🎓 Detalle del Estudiante <?php echo $estudianteId !== null ? '#'.htmlspecialchars((string) $estudianteId, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
          <p class="subtitle">Consulta la información registrada de un estudiante y su vinculación con empresa y convenio.</p>
        </div>
        <div class="actions" style="display:flex; gap:0.5rem; flex-wrap:wrap;">
          <a href="estudiante_list.php" class="btn secondary">← Regresar</a>
          <?php if ($canEdit && $estudianteId !== null): ?>
            <a href="estudiante_edit.php?id=<?php echo urlencode((string) $estudianteId); ?>" class="btn">✏️ Editar</a>
          <?php endif; ?>
        </div>
      </header>

      <?php if (in_array('invalid_id', $viewErrors, true)): ?>
        <section class="card">
          <div class="content">
            <div class="alert error">⚠️ El identificador proporcionado no es válido.</div>
          </div>
        </section>
      <?php endif; ?>

      <?php if (in_array('database_error', $viewErrors, true)): ?>
        <section class="card">
          <div class="content">
            <div class="alert error">⚠️ Ocurrió un problema al consultar la base de datos. Inténtalo de nuevo más tarde.</div>
          </div>
        </section>
      <?php endif; ?>

      <?php if (in_array('not_found', $viewErrors, true) && !in_array('invalid_id', $viewErrors, true)): ?>
        <section class="card">
          <div class="content">
            <div class="alert warning">No se encontró un estudiante con el identificador solicitado.</div>
          </div>
        </section>
      <?php endif; ?>

      <?php if ($estudiante !== null): ?>
        <section class="card profile-card">
          <header>Información del estudiante</header>
          <div class="content grid-2">

            <div class="profile-section">
              <h4>👤 Datos personales</h4>
              <ul class="data-list">
                <li><strong>Nombre completo:</strong> <?php echo htmlspecialchars($metadata['nombreCompleto'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Matrícula:</strong> <?php echo htmlspecialchars($metadata['matricula'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Carrera:</strong> <?php echo htmlspecialchars($metadata['carrera'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Correo institucional:</strong> <?php echo htmlspecialchars($metadata['correoInstitucional'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($metadata['telefono'], ENT_QUOTES, 'UTF-8'); ?></li>
              </ul>
            </div>

            <div class="profile-section">
              <h4>🏢 Empresa asignada</h4>
              <ul class="data-list">
                <li><strong>Empresa:</strong> <?php echo htmlspecialchars($metadata['empresaNombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Contacto:</strong> <?php echo htmlspecialchars($metadata['empresaContactoNombre'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Correo:</strong> <?php echo htmlspecialchars($metadata['empresaContactoEmail'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Teléfono:</strong> <?php echo htmlspecialchars($metadata['empresaTelefono'], ENT_QUOTES, 'UTF-8'); ?></li>
                <li><strong>Dirección:</strong> <?php echo htmlspecialchars($metadata['empresaDireccion'], ENT_QUOTES, 'UTF-8'); ?></li>
              </ul>

              <?php if ($metadata['empresaRepresentante'] !== 'N/D'): ?>
                <p class="muted">Representante: <?php echo htmlspecialchars($metadata['empresaRepresentante'], ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>

              <h4 style="margin-top:1.2rem;">📑 Convenio relacionado</h4>
              <?php if ($hasConvenio): ?>
                <ul class="data-list">
                  <li><strong>Folio:</strong> <?php echo htmlspecialchars($metadata['convenioFolio'], ENT_QUOTES, 'UTF-8'); ?></li>
                  <li><strong>Estatus:</strong> <span class="<?php echo htmlspecialchars($metadata['convenioBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($metadata['convenioBadgeLabel'], ENT_QUOTES, 'UTF-8'); ?></span></li>
                  <li><strong>Tipo:</strong> <?php echo htmlspecialchars($metadata['convenioTipo'], ENT_QUOTES, 'UTF-8'); ?></li>
                  <li><strong>Responsable académico:</strong> <?php echo htmlspecialchars($metadata['convenioResponsableAcademico'], ENT_QUOTES, 'UTF-8'); ?></li>
                  <li><strong>Vigencia:</strong> <?php echo htmlspecialchars($metadata['convenioFechaInicio'], ENT_QUOTES, 'UTF-8'); ?> – <?php echo htmlspecialchars($metadata['convenioFechaFin'], ENT_QUOTES, 'UTF-8'); ?></li>
                </ul>
              <?php else: ?>
                <p class="muted">No hay convenio asignado a este estudiante.</p>
              <?php endif; ?>
            </div>

          </div>
        </section>

        <section class="card">
          <header>📅 Información adicional</header>
          <div class="content">
            <ul class="data-list">
              <li><strong>Estatus del estudiante:</strong> <span class="<?php echo htmlspecialchars($metadata['estatusBadgeClass'], ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($metadata['estatusBadgeLabel'], ENT_QUOTES, 'UTF-8'); ?></span></li>
              <li><strong>Fecha de registro:</strong> <?php echo htmlspecialchars($metadata['creadoEnLabel'], ENT_QUOTES, 'UTF-8'); ?></li>
            </ul>
          </div>
        </section>
      <?php endif; ?>

      <p class="foot">Área de Vinculación · Residencias Profesionales</p>
    </main>
  </div>
</body>
</html>
