<?php
declare(strict_types=1);

// ===============================
//   DATOS DEL DASHBOARD (dummy)
//   Listos para reemplazar con BD
// ===============================

// Convenio actual (si existe)
$convenioActual = [
    'estado' => 'Activa',
    'folio' => 'RP/2025/0142',
    'tipo' => 'Convenio Marco',
];

// KPI Machote
$kpiMachote = [
    'version' => 'v1.3',
    'comentariosPendientes' => 2,
    'ultimaRevision' => '01/12/2025',
    'estado' => 'En revisión',
    'avance' => 68,
];

// KPI Documentos legales
$kpiDocs = [
    'aprobados' => 5,
    'total' => 7,
    'pendientes' => 2,
];
$docsPercent = $kpiDocs['total'] > 0 ? round(($kpiDocs['aprobados'] / $kpiDocs['total']) * 100) : 0;

// KPI Estudiantes
$kpiEstudiantes = [
    'asignados' => 142,
    'activos' => 96,
    'finalizados' => 28,
    'sinAsignar' => 12,
];

// Timeline general del convenio
$timelineConvenio = [
    ['etapa' => 'Machote aprobado', 'estado' => 'completo', 'detalle' => 'Versión vigente publicada'],
    ['etapa' => 'Documentación completa', 'estado' => 'incompleto', 'detalle' => 'Faltan 2 documentos firmados'],
    ['etapa' => 'Estudiantes asignados', 'estado' => 'completo', 'detalle' => 'Asignaciones confirmadas'],
    ['etapa' => 'Convenio Activo', 'estado' => 'pendiente', 'detalle' => 'Activar al completar documentación'],
];

// Alertas críticas
$alertasCriticas = [
    ['titulo' => 'Documentos pendientes requeridos', 'detalle' => '2', 'icono' => '!'],
    ['titulo' => 'Comentarios sin resolver en machote', 'detalle' => '1', 'icono' => '!'],
    ['titulo' => 'Revisiones abiertas', 'detalle' => '3', 'icono' => '!'],
];

// Actividades recientes
$actividadesRecientes = [
    ['tiempo' => 'Hace 10 min', 'detalle' => 'Documento "Identificación" aprobado'],
    ['tiempo' => 'Hace 25 min', 'detalle' => 'Comentario resuelto en machote'],
    ['tiempo' => 'Hace 2h', 'detalle' => 'Estudiante asignado'],
];

// Accesos rápidos
$shortcuts = [
    ['label' => 'Subir documento', 'icono' => '📄', 'href' => '../documentos/documento_upload.php'],
    ['label' => 'Revisar machote', 'icono' => '📝', 'href' => '../machote/machote_list.php'],
    ['label' => 'Ver convenio', 'icono' => '🧾', 'href' => '../convenio/convenio_list.php'],
    ['label' => 'Ver estudiantes', 'icono' => '👨‍🎓', 'href' => '../estudiante/estudiante_list.php'],
    ['label' => 'Convenios archivados', 'icono' => '🗂', 'href' => '../convenio/convenio_archivado.php'],
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Sistema de Vinculación</title>
    <link rel="stylesheet" href="../../assets/css/modules/empresa/empresalist.css" />

    <link rel="stylesheet" href="../../assets/DashboardCSS/dashboard.css" />

</head>

<body>
    <div class="app">

        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <main class="dashboard-main">

            <!-- ===================================== -->
            <!--   ALERTAS CRÍTICAS                    -->
            <!-- ===================================== -->
            <section class="card alert-card">
                <div class="card-header">
                    <h3 class="card-title">Pendientes críticos</h3>
                    <span class="pill warning">Atención prioritaria</span>
                </div>

                <?php if (empty($alertasCriticas)): ?>
                    <p class="muted">No hay alertas registradas.</p>
                <?php else: ?>
                    <ul class="alert-list">
                        <?php foreach ($alertasCriticas as $alerta): ?>
                            <li class="alert-item">
                                <div class="alert-icon"><?php echo htmlspecialchars($alerta['icono']); ?></div>
                                <div>
                                    <div class="alert-title"><?php echo htmlspecialchars($alerta['titulo']); ?></div>
                                    <?php if (!empty($alerta['detalle'])): ?>
                                        <div class="alert-meta"><?php echo htmlspecialchars($alerta['detalle']); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="pill warning">Pendiente</div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <!-- ===================================== -->
            <!--   KPIs PRINCIPALES                    -->
            <!-- ===================================== -->
            <section class="kpi-row">

                <!-- CONVENIO -->
                <article class="kpi-card primary">
                    <div class="spaced">
                        <div class="kpi-icon">C</div>
                        <span class="kpi-pill">Convenio actual</span>
                    </div>

                    <h3 class="kpi-title">Estado del convenio</h3>

                    <?php if ($convenioActual): ?>
                        <div class="kpi-grid">
                            <div class="kpi-value"><?php echo htmlspecialchars($convenioActual['estado']); ?></div>
                            <div class="kpi-sub">Folio: <?php echo htmlspecialchars($convenioActual['folio']); ?></div>
                            <div class="kpi-sub">Tipo: <?php echo htmlspecialchars($convenioActual['tipo']); ?></div>
                        </div>
                    <?php else: ?>
                        <div class="kpi-empty">No hay convenio activo.</div>
                    <?php endif; ?>
                </article>

                <!-- MACHOTE -->
                <article class="kpi-card teal">
                    <div class="spaced">
                        <div class="kpi-icon">M</div>
                        <span class="kpi-pill">Revisión de machote</span>
                    </div>

                    <h3 class="kpi-title">Machote <?php echo htmlspecialchars($kpiMachote['version']); ?></h3>
                    <div class="kpi-sub">Estado: <?php echo htmlspecialchars($kpiMachote['estado']); ?></div>
                    <div class="kpi-sub">Comentarios:
                        <?php echo htmlspecialchars((string) $kpiMachote['comentariosPendientes']); ?></div>
                    <div class="kpi-sub">Última revisión: <?php echo htmlspecialchars($kpiMachote['ultimaRevision']); ?>
                    </div>

                    <div class="progress">
                        <div class="bar" style="width: <?php echo (int) $kpiMachote['avance']; ?>%"></div>
                    </div>
                    <div class="kpi-sub">Avance: <?php echo htmlspecialchars((string) $kpiMachote['avance']); ?>%</div>
                </article>

                <!-- DOCUMENTOS -->
                <article class="kpi-card amber">
                    <div class="spaced">
                        <div class="kpi-icon">D</div>
                        <span class="kpi-pill">Documentación legal</span>
                    </div>

                    <h3 class="kpi-title">Documentación</h3>
                    <div class="kpi-value">
                        <?php echo htmlspecialchars((string) $kpiDocs['aprobados']); ?>/<?php echo htmlspecialchars((string) $kpiDocs['total']); ?>
                        aprobados
                    </div>

                    <div class="progress">
                        <div class="bar" style="width: <?php echo $docsPercent; ?>%"></div>
                    </div>

                    <div class="kpi-sub">
                        Pendientes: <?php echo htmlspecialchars((string) $kpiDocs['pendientes']); ?> · Avance:
                        <?php echo $docsPercent; ?>%
                    </div>
                </article>

                <!-- ESTUDIANTES -->
                <article class="kpi-card pink">
                    <div class="spaced">
                        <div class="kpi-icon">E</div>
                        <span class="kpi-pill">Estudiantes</span>
                    </div>

                    <h3 class="kpi-title">Asignaciones</h3>
                    <div class="kpi-value"><?php echo htmlspecialchars((string) $kpiEstudiantes['asignados']); ?></div>
                    <div class="kpi-sub">Activos: <?php echo htmlspecialchars((string) $kpiEstudiantes['activos']); ?>
                    </div>
                    <div class="kpi-sub">Sin asignar:
                        <?php echo htmlspecialchars((string) $kpiEstudiantes['sinAsignar']); ?></div>
                </article>

            </section>

            <!-- ===================================== -->
            <!--   TIMELINE DEL CONVENIO               -->
            <!-- ===================================== -->
            <section class="card">
                <div class="card-header">
                    <h3 class="card-title">Línea de tiempo del convenio</h3>
                    <span class="pill">Ciclo completo</span>
                </div>

                <div class="flow">
                    <?php foreach ($timelineConvenio as $step): ?>
                        <?php
                        $statusClass =
                            $step['estado'] === 'completo' ? 'ok' :
                            ($step['estado'] === 'incompleto' ? 'warn' : 'pending');

                        $statusLabel =
                            $step['estado'] === 'completo' ? '✔ Completo' :
                            ($step['estado'] === 'incompleto' ? '⚠ Incompleto' : 'Pendiente');
                        ?>

                        <div class="flow-step">
                            <span class="status <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span>
                            <div class="label"><?php echo htmlspecialchars($step['etapa']); ?></div>
                            <div class="desc"><?php echo htmlspecialchars($step['detalle']); ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- ===================================== -->
            <!--   ÚLTIMAS ACTIVIDADES                 -->
            <!-- ===================================== -->
            <section class="card">
                <div class="card-header">
                    <h3 class="card-title">Últimas actividades</h3>
                    <span class="pill accent">Historial</span>
                </div>

                <?php if (empty($actividadesRecientes)): ?>
                    <p class="muted">No hay actividades recientes.</p>
                <?php else: ?>
                    <ul class="activity-list">
                        <?php foreach ($actividadesRecientes as $act): ?>
                            <li class="activity-item">
                                <span class="activity-time"><?php echo htmlspecialchars($act['tiempo']); ?></span>
                                <span class="activity-detail"><?php echo htmlspecialchars($act['detalle']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <!-- ===================================== -->
            <!--   ACCESOS RÁPIDOS                     -->
            <!-- ===================================== -->
            <section class="card">
                <div class="card-header">
                    <h3 class="card-title">Accesos rápidos</h3>
                    <span class="pill accent">Atajos</span>
                </div>

                <div class="shortcuts">
                    <?php foreach ($shortcuts as $shortcut): ?>
                        <a class="shortcut-btn" href="<?php echo htmlspecialchars($shortcut['href']); ?>">
                            <span class="shortcut-icon"><?php echo htmlspecialchars($shortcut['icono']); ?></span>
                            <span><?php echo htmlspecialchars($shortcut['label']); ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>

        </main>
    </div>
</body>

</html>