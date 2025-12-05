<?php
declare(strict_types=1);
require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../handler/dashboard/dashboard_empresa_handler.php';
require_once __DIR__ . '/../../handler/dashboard/dashboard_convenio_handler.php';
require_once __DIR__ . '/../../handler/dashboard/dashboard_documento_handler.php';
require_once __DIR__ . '/../../handler/dashboard/dashboard_comentario_handler.php';

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['res_admin'];

if (!is_array($user) || !in_array(strtolower((string) ($user['role'] ?? '')), $allowedRoles, true)) {
  header('Location: ../../common/login.php?module=residencias&error=unauthorized');
  exit;
}

// ===============================
//   DATOS DEL DASHBOARD (dummy)
//   Listos para reemplazar con BD
// ===============================

// 4) COMENTARIOS / MACHOTE PENDIENTES
$machotePendientes = [
    [
        'empresa'      => 'ACME S.A.',
        'version'      => 'v1.3',
        'comentarios'  => 2,
        'machote_id'   => 34,
    ],
    [
        'empresa'      => 'Tech Corp',
        'version'      => 'v2.0',
        'comentarios'  => 1,
        'machote_id'   => 41,
    ],
];

// 5) Alertas críticas (construidas a partir de lo anterior)
$alertasCriticas = [];

if ($docsStats['pendientes'] > 0) {
    $alertasCriticas[] = [
        'titulo' => 'Documentos pendientes por revisar',
        'detalle' => (string) $docsStats['pendientes'],
        'icono' => '!',
    ];
}

if (!empty($machotePendientes)) {
    $totalComentarios = array_sum(array_map(
        static fn(array $m): int => (int) $m['comentarios'],
        $machotePendientes
    ));

    $alertasCriticas[] = [
        'titulo' => 'Comentarios de machote sin revisar',
        'detalle' => (string) $totalComentarios,
        'icono' => '!',
    ];
}

if ($conveniosStats['activos'] === 0) {
    $alertasCriticas[] = [
        'titulo' => 'Sin convenios activos',
        'detalle' => '',
        'icono' => '!',
    ];
}

// 6) Accesos rápidos
$shortcuts = [
    ['label' => 'Empresas',              'icono' => '🏢', 'href' => '../empresa/empresa_list.php'],
    ['label' => 'Convenios',             'icono' => '📑', 'href' => '../convenio/convenio_list.php'],
    ['label' => 'Documentos legales',    'icono' => '📄', 'href' => '../documentos/documento_list.php'],
    ['label' => 'Revisión de machotes',  'icono' => '📝', 'href' => '../machote/machote_list.php'],
    ['label' => 'Convenios archivados',  'icono' => '🗂', 'href' => '../convenio/convenio_archivado.php'],
];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Sistema de Vinculación</title>

    <!-- Tus estilos existentes -->
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
            <!-- <section class="card alert-card">
                <div class="card-header">
                    <h3 class="card-title">Pendientes críticos</h3>
                    <span class="pill warning">
                        <?php echo empty($alertasCriticas) ? 'Sin alertas' : 'Atención prioritaria'; ?>
                    </span>
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
            </section> -->

            <!-- ===================================== -->
            <!--   KPIs SIMPLES (EMPRESAS / CONVENIOS / DOCS) -->
            <!-- ===================================== -->
            <section class="kpi-row">

                <!-- EMPRESAS -->
                <article class="kpi-card primary">
                    <div class="spaced">
                        <div class="kpi-icon">E</div>
                        <span class="kpi-pill">Empresas</span>
                    </div>

                    <h3 class="kpi-title">Resumen de empresas</h3>
                    <ul class="simple-list">
                        <li>Total: <strong><?php echo (int) $empresasStats['total']; ?></strong></li>
                        <li>Activas: <strong><?php echo (int) $empresasStats['activas']; ?></strong></li>
                        <li>En revisión: <strong><?php echo (int) $empresasStats['revision']; ?></strong></li>
                        <li>Completadas: <strong><?php echo (int) $empresasStats['completadas']; ?></strong></li>
                    </ul>
                    <?php if (!empty($empresasError)): ?>
                        <p class="muted"><?php echo htmlspecialchars((string) $empresasError, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <div class="actions">
                        <a class="btn primary" href="../empresa/empresa_list.php">Ver empresas</a>
                    </div>
                </article>

                <!-- CONVENIOS -->
                <article class="kpi-card teal">
                    <div class="spaced">
                        <div class="kpi-icon">C</div>
                        <span class="kpi-pill">Convenios</span>
                    </div>

                    <h3 class="kpi-title">Estado de convenios</h3>
                    <ul class="simple-list">
                        <li>Activos: <strong><?php echo (int) $conveniosStats['activos']; ?></strong></li>
                        <li>Archivados: <strong><?php echo (int) $conveniosStats['archivados']; ?></strong></li>
                    </ul>
                    <?php if (!empty($conveniosError)): ?>
                        <p class="muted"><?php echo htmlspecialchars((string) $conveniosError, ENT_QUOTES, 'UTF-8'); ?></p>
                    <?php endif; ?>

                    <div class="actions">
                        <a class="btn ghost" href="../convenio/convenio_list.php">Ver convenios</a>
                        <a class="btn primary" href="../convenio/convenio_add.php">Nuevo convenio</a>
                    </div>
                </article>

                <!-- DOCUMENTOS -->
                <article class="kpi-card amber">
                    <div class="spaced">
                        <div class="kpi-icon">D</div>
                        <span class="kpi-pill">Documentación legal</span>
                    </div>

                    <h3 class="kpi-title">Documentos por aprobar</h3>
                    <div class="kpi-value">
                        <?php echo (int) $docsStats['revision']; ?>
                    </div>

                    <div class="kpi-sub">
                        En revisión
                    </div>

                  <div class="actions">
                      <a class="btn ghost" href="../documentos/documento_list.php">Ver documentos</a>
                  </div>
              </article>

              <!-- MACHOTES / COMENTARIOS -->
              <article class="kpi-card pink">
                  <div class="spaced">
                      <div class="kpi-icon">M</div>
                      <div class="inline">
                          <span class="kpi-pill">Machotes</span>
                          <span class="pill warning">
                              <?php echo (int) $comentariosStats['revisiones']; ?> en revision
                          </span>
                      </div>
                  </div>

                  <h3 class="kpi-title">Comentarios en revision</h3>

                  <div class="stat">
                      <div class="number"><?php echo (int) $comentariosStats['abiertos']; ?></div>
                      <div class="label">Comentarios abiertos</div>
                  </div>

                  <ul class="simple-list">
                      <li>Resueltos: <strong><?php echo (int) $comentariosStats['resueltos']; ?></strong></li>
                      <li>Archivados: <strong><?php echo (int) $comentariosStats['archivados']; ?></strong></li>
                      <li>Total mensajes: <strong><?php echo (int) $comentariosStats['total']; ?></strong></li>
                  </ul>

                  <div class="actions">
                      <a class="btn ghost" href="../machote/machote_list.php">Ver revisiones</a>
                      <a class="btn primary" href="../machote/machote_list.php?estado=en_revision">Pendientes</a>
                  </div>
              </article>

          </section>

            <!-- ===================================== -->
            <!--   DOCUMENTOS EN REVISIÓN              -->
            <!-- ===================================== -->
            <section class="card">
                <div class="card-header">
                    <h3 class="card-title">Documentos en revisión</h3>
                    <span class="pill accent">
                        <?php echo empty($docsEnRevision) ? 'Sin pendientes' : count($docsEnRevision) . ' por revisar'; ?>
                    </span>
                </div>

                <?php if (!empty($docsError)): ?>
                    <p class="muted"><?php echo htmlspecialchars((string) $docsError, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php elseif (empty($docsEnRevision)): ?>
                    <p class="muted">No hay documentos en revisión.</p>
                <?php else: ?>
                    <ul class="task-list">
                        <?php foreach ($docsEnRevision as $doc): ?>
                            <li class="task-item">
                                <div>
                                    <strong><?php echo htmlspecialchars((string) ($doc['empresa_nombre'] ?? 'Empresa')); ?></strong><br>
                                    <?php echo htmlspecialchars((string) ($doc['tipo_nombre'] ?? 'Documento')); ?>
                                </div>
                                <a class="btn small primary"
                                   href="../documentos/documento_review.php?id=<?php echo (int) ($doc['id'] ?? 0); ?>">
                                    Revisar
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>

            <!-- ===================================== -->
            <!--   COMENTARIOS DE MACHOTE EN REVISION  -->
            <!-- ===================================== -->
            <section class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">Comentarios de machote en revision</h3>
                        <p class="card-subtitle">
                            Revisiones activas: <?php echo (int) $comentariosStats['revisiones']; ?>
                        </p>
                    </div>
                    <div class="inline">
                        <span class="pill warning">
                            Abiertos <?php echo (int) $comentariosStats['abiertos']; ?>
                        </span>
                        <span class="pill accent">
                            Resueltos <?php echo (int) $comentariosStats['resueltos']; ?>
                        </span>
                        <span class="pill">
                            Archivados <?php echo (int) $comentariosStats['archivados']; ?>
                        </span>
                    </div>
                </div>

                <?php if (!empty($comentariosError)): ?>
                    <p class="muted"><?php echo htmlspecialchars((string) $comentariosError, ENT_QUOTES, 'UTF-8'); ?></p>
                <?php elseif (empty($comentariosRevision)): ?>
                    <p class="muted">No hay comentarios en revision.</p>
                <?php else: ?>
                    <ul class="task-list">
                        <?php foreach ($comentariosRevision as $comentario): ?>
                            <?php
                            $estatus = strtolower((string) ($comentario['estado'] ?? ''));
                            $pillClass = $estatus === 'en_revision' ? 'warning' : ($estatus === 'acordado' ? 'accent' : '');
                            $estadoLabel = $estatus !== '' ? str_replace('_', ' ', ucfirst($estatus)) : 'Revision';
                            $abiertos = (int) ($comentario['abiertos'] ?? 0);
                            $resueltos = (int) ($comentario['resueltos'] ?? 0);
                            $total = (int) ($comentario['total'] ?? 0);
                            $progreso = max(0, min(100, (int) ($comentario['progreso'] ?? 0)));
                            $empresaNombre = (string) ($comentario['empresa_nombre'] ?? 'Empresa');
                            $versionMachote = trim((string) ($comentario['machote_version'] ?? ''));
                            ?>
                            <li class="task-item">
                                <div class="task-meta">
                                    <div class="inline">
                                        <strong><?php echo htmlspecialchars($empresaNombre); ?></strong>
                                        <span class="pill <?php echo $pillClass; ?>">
                                            <?php echo htmlspecialchars($estadoLabel); ?>
                                        </span>
                                    </div>
                                    <div class="muted">
                                        Machote <?php echo htmlspecialchars($versionMachote); ?> &middot; <?php echo $abiertos; ?> abiertos de <?php echo $total; ?> comentarios
                                    </div>
                                    <div class="progress">
                                        <div class="bar" style="width: <?php echo $progreso; ?>%"></div>
                                    </div>
                                    <small class="muted"><?php echo $progreso; ?>% completado | <?php echo $resueltos; ?> resueltos</small>
                                </div>

                                <a class="btn small primary"
                                   href="../machote/machote_revisar.php?id=<?php echo (int) ($comentario['revision_id'] ?? 0); ?>">
                                    Abrir
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </section>
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
