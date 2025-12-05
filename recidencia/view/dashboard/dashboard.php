<?php

declare(strict_types=1);

$convenioActual = [
  'estado' => 'Activa',
  'folio' => 'RP/2025/0142',
  'tipo' => 'Convenio Marco',
  'vigencia' => '01/01/2025 - 31/12/2025',
  'estatus' => 'Activa',
  'completado' => 86,
  'ultimaActualizacion' => '05/12/2025',
];

$kpiMachote = [
  'version' => 'v1.3',
  'comentariosPendientes' => 2,
  'ultimaRevision' => '01/12/2025',
  'estado' => 'En revisión',
  'avance' => 68,
];

$kpiDocs = [
  'aprobados' => 5,
  'total' => 7,
  'pendientes' => 2,
];
$docsPercent = $kpiDocs['total'] > 0 ? round(($kpiDocs['aprobados'] / $kpiDocs['total']) * 100) : 0;

$kpiEstudiantes = [
  'asignados' => 142,
  'activos' => 96,
  'finalizados' => 28,
  'sinAsignar' => 12,
];

$timelineConvenio = [
  [
    'etapa' => 'Machote aprobado',
    'estado' => 'completo',
    'detalle' => 'Versión vigente publicada',
  ],
  [
    'etapa' => 'Documentación completa',
    'estado' => 'incompleto',
    'detalle' => 'Faltan 2 documentos firmados',
  ],
  [
    'etapa' => 'Estudiantes asignados',
    'estado' => 'completo',
    'detalle' => 'Asignaciones confirmadas',
  ],
  [
    'etapa' => 'Convenio Activo',
    'estado' => 'pendiente',
    'detalle' => 'Activar al completar documentación',
  ],
];

$alertasCriticas = [
  ['titulo' => 'Documentos pendientes requeridos', 'detalle' => '2', 'icono' => '!'],
  ['titulo' => 'Comentarios sin resolver en machote', 'detalle' => '1', 'icono' => '!'],
  ['titulo' => 'Revisiones abiertas', 'detalle' => '3', 'icono' => '!'],
  ['titulo' => 'Estudiantes sin asignar', 'detalle' => '1', 'icono' => '!'],
  ['titulo' => 'Empresa suspendida', 'detalle' => '', 'icono' => '!'],
  ['titulo' => 'Convenio próximo a expirar', 'detalle' => '22/12', 'icono' => '!'],
];

$actividadesRecientes = [
  ['tiempo' => 'Hace 10 min', 'detalle' => 'Documento "Identificación" aprobado'],
  ['tiempo' => 'Hace 25 min', 'detalle' => 'Comentario resuelto en machote'],
  ['tiempo' => 'Hace 50 min', 'detalle' => 'Convenio archivado'],
  ['tiempo' => 'Hace 2h', 'detalle' => 'Estudiante Juan Pérez asignado'],
  ['tiempo' => 'Hace 3h', 'detalle' => 'Revisión jurídica marcada como completada'],
];

$shortcuts = [
  ['label' => 'Subir documento', 'icono' => '📄', 'href' => '../documentos/documento_upload.php'],
  ['label' => 'Revisar machote', 'icono' => '📝', 'href' => '../machote/machote_list.php'],
  ['label' => 'Ver convenio', 'icono' => '🧾', 'href' => '../convenio/convenio_list.php'],
  ['label' => 'Ver estudiantes', 'icono' => '👨‍🎓', 'href' => '../estudiante/estudiante_list.php'],
  ['label' => 'Ver convenios archivados', 'icono' => '🗂', 'href' => '../convenio/convenio_archivado.php'],
  ['label' => 'Crear asignación', 'icono' => '➕', 'href' => '../estudiante/estudiante_add.php'],
  ['label' => 'Crear nuevo convenio', 'icono' => '➕', 'href' => '../convenio/convenio_add.php'],
];
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Sistema de Vinculación</title>
  <link rel="stylesheet" href="../../assets/DashboardCSS/dashboard.css" />
</head>

<body>
  <div class="dashboard-app">
    <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

    <main class="dashboard-main">
      <section class="card alert-card">
        <div class="card-header">
          <h3 class="card-title">Pendientes críticos</h3>
          <span class="pill warning">Atención prioritaria</span>
        </div>
        <ul class="alert-list">
          <?php foreach ($alertasCriticas as $alerta) : ?>
            <li class="alert-item">
              <div class="alert-icon"><?php echo htmlspecialchars($alerta['icono']); ?></div>
              <div>
                <div class="alert-title"><?php echo htmlspecialchars($alerta['titulo']); ?></div>
                <?php if (!empty($alerta['detalle'])) : ?>
                  <div class="alert-meta"><?php echo htmlspecialchars($alerta['detalle']); ?></div>
                <?php endif; ?>
              </div>
              <div class="pill warning">Pendiente</div>
            </li>
          <?php endforeach; ?>
        </ul>
      </section>

      <section class="kpi-row">
        <article class="kpi-card primary">
          <div class="spaced">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #264bf7, #5c7dff)">C</div>
            <span class="kpi-pill">Convenio actual</span>
          </div>
          <h3 class="kpi-title">Estado del convenio</h3>
          <?php if ($convenioActual) : ?>
            <div class="kpi-grid">
              <div class="kpi-value"><?php echo htmlspecialchars($convenioActual['estado']); ?></div>
              <div class="kpi-sub">Folio: <?php echo htmlspecialchars($convenioActual['folio']); ?></div>
              <div class="kpi-sub">Tipo: <?php echo htmlspecialchars($convenioActual['tipo']); ?></div>
            </div>
          <?php else : ?>
            <div class="kpi-empty">Esta empresa no tiene convenio activo.</div>
          <?php endif; ?>
        </article>

        <article class="kpi-card teal">
          <div class="spaced">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #0fd6c9, #2af0e4)">M</div>
            <span class="kpi-pill">Revisión de machote</span>
          </div>
          <h3 class="kpi-title">Machote <?php echo htmlspecialchars($kpiMachote['version']); ?></h3>
          <div class="kpi-sub">Estado general: <?php echo htmlspecialchars($kpiMachote['estado']); ?></div>
          <div class="kpi-sub">Comentarios pendientes: <?php echo htmlspecialchars((string)$kpiMachote['comentariosPendientes']); ?></div>
          <div class="kpi-sub">Última revisión: <?php echo htmlspecialchars($kpiMachote['ultimaRevision']); ?></div>
          <div class="progress" aria-hidden="true">
            <div class="bar" style="width: <?php echo (int)$kpiMachote['avance']; ?>%"></div>
          </div>
          <div class="kpi-sub">Avance: <?php echo htmlspecialchars((string)$kpiMachote['avance']); ?>%</div>
        </article>

        <article class="kpi-card amber">
          <div class="spaced">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #ffb347, #ffd070)">D</div>
            <span class="kpi-pill">Documentación legal</span>
          </div>
          <h3 class="kpi-title">Documentación</h3>
          <div class="kpi-value"><?php echo htmlspecialchars((string)$kpiDocs['aprobados']); ?>/<?php echo htmlspecialchars((string)$kpiDocs['total']); ?> aprobados</div>
          <div class="progress" aria-hidden="true">
            <div class="bar" style="width: <?php echo $docsPercent; ?>%"></div>
          </div>
          <div class="kpi-sub">Pendientes: <?php echo htmlspecialchars((string)$kpiDocs['pendientes']); ?> · Avance: <?php echo htmlspecialchars((string)$docsPercent); ?>%</div>
        </article>

        <article class="kpi-card pink">
          <div class="spaced">
            <div class="kpi-icon" style="background: linear-gradient(135deg, #ef476f, #ff7a9a)">E</div>
            <span class="kpi-pill">Estudiantes / Asignaciones</span>
          </div>
          <h3 class="kpi-title">Estudiantes asignados</h3>
          <div class="kpi-value"><?php echo htmlspecialchars((string)$kpiEstudiantes['asignados']); ?></div>
          <div class="kpi-sub">Activos en periodo: <?php echo htmlspecialchars((string)$kpiEstudiantes['activos']); ?></div>
          <div class="kpi-sub">Finalizados: <?php echo htmlspecialchars((string)$kpiEstudiantes['finalizados']); ?> · Sin asignar: <?php echo htmlspecialchars((string)$kpiEstudiantes['sinAsignar']); ?></div>
        </article>
      </section>

      <section class="dashboard-hero">
        <span class="eyebrow">Sistema de Vinculación</span>
        <h1>Dashboard principal</h1>
        <p>Visión integral de Convenios, Machotes/Revisión, Documentación legal y Estudiantes/Asignaciones.</p>

        <div class="hero-actions">
          <a class="btn primary" href="../convenio/convenio_add.php">Nuevo convenio</a>
          <a class="btn ghost" href="../machote/machote_list.php">Revisión de machotes</a>
          <a class="btn ghost" href="../documentos/documento_upload.php">Subir documento legal</a>
          <a class="btn ghost" href="../estudiante/estudiante_list.php">Asignar estudiantes</a>
        </div>

        <div class="hero-metrics">
          <div class="metric-tile">
            <div class="label">Convenios activos</div>
            <div class="value">24</div>
            <div class="progress" aria-hidden="true">
              <div class="bar" style="width: 78%"></div>
            </div>
          </div>
          <div class="metric-tile">
            <div class="label">Revisiones abiertas</div>
            <div class="value">12</div>
            <div class="pill accent">+3 semana</div>
          </div>
          <div class="metric-tile">
            <div class="label">Documentos validados</div>
            <div class="value">86%</div>
            <div class="pill">Checklist legal</div>
          </div>
          <div class="metric-tile">
            <div class="label">Estudiantes asignados</div>
            <div class="value">142</div>
            <div class="pill warning">8 pendientes</div>
          </div>
        </div>
      </section>

      <section class="stats-grid">
        <article class="card axis-card emphasis">
          <div class="accent-blob"></div>
          <div class="card-header">
            <div>
              <p class="card-subtitle">Seguimiento integral</p>
              <h3 class="card-title">Convenios</h3>
            </div>
            <span class="pill accent">+2 firmados hoy</span>
          </div>
          <div class="stat">
            <span class="number">24</span>
            <span class="label">activos</span>
          </div>
          <div class="progress" aria-hidden="true">
            <div class="bar" style="width: 72%"></div>
          </div>
          <ul>
            <li><span class="muted">En renovación</span><strong>5</strong></li>
            <li><span class="muted">En revisión jurídica</span><strong>3</strong></li>
            <li><span class="muted">Vencen este mes</span><strong>4</strong></li>
          </ul>
          <div class="axis-links">
            <a href="../convenio/convenio_list.php">Ver listado</a>
            <a href="../convenio/convenio_renovar.php">Renovar</a>
            <a href="../convenio/convenio_add.php">Alta rápida</a>
          </div>
        </article>

        <article class="card axis-card">
          <div class="accent-blob"></div>
          <div class="card-header">
            <div>
              <p class="card-subtitle">Machotes y revisión</p>
              <h3 class="card-title">Machote / Revisión</h3>
            </div>
            <span class="pill">Checklist</span>
          </div>
          <div class="stat">
            <span class="number">12</span>
            <span class="label">en revisión</span>
          </div>
          <ul>
            <li><span class="muted">Comentarios pendientes</span><strong>7</strong></li>
            <li><span class="muted">Versión aprobada</span><strong>5</strong></li>
            <li><span class="muted">Tiempo promedio</span><strong>2.3 d</strong></li>
          </ul>
          <div class="axis-links">
            <a href="../machote/machote_list.php">Revisar comentarios</a>
            <a href="../machoteglobal/machote_global_list.php">Machote global</a>
          </div>
        </article>

        <article class="card axis-card">
          <div class="accent-blob"></div>
          <div class="card-header">
            <div>
              <p class="card-subtitle">Control jurídico</p>
              <h3 class="card-title">Documentación legal</h3>
            </div>
            <span class="pill warning">Vencen pronto</span>
          </div>
          <div class="stat">
            <span class="number">86%</span>
            <span class="label">cumplimiento</span>
          </div>
          <ul>
            <li><span class="muted">Documentos vigentes</span><strong>148</strong></li>
            <li><span class="muted">Caducan en 30 días</span><strong>9</strong></li>
            <li><span class="muted">Pendientes de firma</span><strong>4</strong></li>
          </ul>
          <div class="axis-links">
            <a href="../documentos/documento_list.php">Ver documentos</a>
            <a href="../documentos/documento_upload.php">Subir evidencia</a>
            <a href="../documentotipo/documentotipo_list.php">Tipos de documento</a>
          </div>
        </article>

        <article class="card axis-card">
          <div class="accent-blob"></div>
          <div class="card-header">
            <div>
              <p class="card-subtitle">Asignación y seguimiento</p>
              <h3 class="card-title">Estudiantes / Asignaciones</h3>
            </div>
            <span class="pill accent">+18 en matching</span>
          </div>
          <div class="stat">
            <span class="number">142</span>
            <span class="label">asignados</span>
          </div>
          <div class="progress" aria-hidden="true">
            <div class="bar" style="width: 64%"></div>
          </div>
          <ul>
            <li><span class="muted">Con proyecto activo</span><strong>96</strong></li>
            <li><span class="muted">En espera de empresa</span><strong>18</strong></li>
            <li><span class="muted">Reportes atrasados</span><strong>6</strong></li>
          </ul>
          <div class="axis-links">
            <a href="../estudiante/estudiante_list.php">Listado</a>
            <a href="../empresa/empresa_list.php">Empresas</a>
            <a href="../portalacceso/portal_list.php">Portales</a>
          </div>
        </article>
      </section>

      <section class="grid grid-2">
        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Próximos vencimientos</h3>
            <span class="pill warning">7 dentro de 30 días</span>
          </div>
          <ul class="timeline">
            <li>
              <div class="spaced">
                <div class="title">Convenio ACME - Auditoría</div>
                <a class="btn small ghost" href="../convenio/convenio_view.php?id=1">Abrir</a>
              </div>
              <div class="meta">
                <span>Vence: 22/12</span>
                <span class="pill warning">Renovar</span>
              </div>
            </li>
            <li>
              <div class="spaced">
                <div class="title">Revisión machote Global</div>
                <a class="btn small ghost" href="../machote/machote_list.php">Ver</a>
              </div>
              <div class="meta">
                <span>Comentarios pendientes: 3</span>
                <span class="pill">Legal</span>
              </div>
            </li>
            <li>
              <div class="spaced">
                <div class="title">Carta de confidencialidad</div>
                <a class="btn small ghost" href="../documentos/documento_list.php">Ir</a>
              </div>
              <div class="meta">
                <span>Subir versión firmada</span>
                <span class="pill accent">Prioridad</span>
              </div>
            </li>
          </ul>
        </article>

        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Checklist legal express</h3>
            <span class="pill accent">86% completo</span>
          </div>
          <ul class="checklist">
            <li>
              <span class="status"></span>
              <div>
                <strong>Convenios con vigencia registrada</strong>
                <p class="muted">Actualizar fecha y responsable</p>
              </div>
            </li>
            <li>
              <span class="status warning"></span>
              <div>
                <strong>Documentos con firmas faltantes</strong>
                <p class="muted">3 con firma de empresa, 1 con firma institucional</p>
              </div>
            </li>
            <li>
              <span class="status danger"></span>
              <div>
                <strong>Machotes pendientes de aprobación</strong>
                <p class="muted">Revisar comentarios críticos antes de publicar</p>
              </div>
            </li>
            <li>
              <span class="status"></span>
              <div>
                <strong>Asignaciones con reportes atrasados</strong>
                <p class="muted">Enviar recordatorio a estudiantes y empresa</p>
              </div>
            </li>
          </ul>
        </article>

        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Resumen del convenio actual</h3>
            <span class="pill">Detalle breve</span>
          </div>
          <?php if ($convenioActual) : ?>
            <table class="summary-table">
              <tr>
                <td class="label">Folio</td>
                <td class="value"><?php echo htmlspecialchars($convenioActual['folio']); ?></td>
              </tr>
              <tr>
                <td class="label">Vigencia</td>
                <td class="value"><?php echo htmlspecialchars($convenioActual['vigencia']); ?></td>
              </tr>
              <tr>
                <td class="label">Tipo</td>
                <td class="value"><?php echo htmlspecialchars($convenioActual['tipo']); ?></td>
              </tr>
              <tr>
                <td class="label">Estatus</td>
                <td class="value"><?php echo htmlspecialchars($convenioActual['estatus']); ?></td>
              </tr>
              <tr>
                <td class="label">% completado</td>
                <td class="value"><?php echo htmlspecialchars((string)$convenioActual['completado']); ?>%</td>
              </tr>
              <tr>
                <td class="label">Última actualización</td>
                <td class="value"><?php echo htmlspecialchars($convenioActual['ultimaActualizacion']); ?></td>
              </tr>
            </table>
          <?php else : ?>
            <p class="muted">No hay convenio activo.</p>
          <?php endif; ?>
        </article>

        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Línea de tiempo del convenio</h3>
            <span class="pill">Ciclo completo</span>
          </div>
          <div class="flow">
            <?php foreach ($timelineConvenio as $step) : ?>
              <?php
              $statusClass = $step['estado'] === 'completo' ? 'ok' : ($step['estado'] === 'incompleto' ? 'warn' : 'pending');
              $statusLabel = $step['estado'] === 'completo' ? '✔ Completo' : ($step['estado'] === 'incompleto' ? '⚠ Incompleto' : 'Pendiente');
              ?>
              <div class="flow-step">
                <span class="status <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                <div class="label"><?php echo htmlspecialchars($step['etapa']); ?></div>
                <div class="desc"><?php echo htmlspecialchars($step['detalle']); ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </article>

        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Últimas actividades</h3>
            <span class="pill accent">Historial vivo</span>
          </div>
          <ul class="activity-list">
            <?php foreach ($actividadesRecientes as $act) : ?>
              <li class="activity-item">
                <span class="activity-time"><?php echo htmlspecialchars($act['tiempo']); ?></span>
                <span class="activity-detail"><?php echo htmlspecialchars($act['detalle']); ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </article>

        <article class="card">
          <div class="card-header">
            <h3 class="card-title">Acceso rápido</h3>
tajos</span>
          </div>
          <div class="shortcuts">
            <?php foreach ($shortcuts as $shortcut) : ?>
              <a class="shortcut-btn" href="<?php echo htmlspecialchars($shortcut['href']); ?>">
                <span class="shortcut-icon"><?php echo htmlspecialchars($shortcut['icono']); ?></span>
                <span><?php echo htmlspecialchars($shortcut['label']); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </article>
    </main>
  </div>
</body>

</html>
