<?php
declare(strict_types=1);

/**
 * @var array{
 *     convenioId: ?int,
 *     convenio: ?array<string, mixed>,
 *     machoteObservaciones: array<int, array<string, mixed>>,
 *     documentosAsociados: array<int, array<string, mixed>>,
 *     historial: array<int, array<string, mixed>>,
 *     controllerError: ?string,
 *     notFoundMessage: ?string,
 *     inputError: ?string
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/convenio/convenio_view_handler.php';
require_once __DIR__ . '/../../common/helpers/convenio/convenio_view_helpers.php';

$convenioId = $handlerResult['convenioId'];
$convenio = $handlerResult['convenio'];
$machoteObservaciones = $handlerResult['machoteObservaciones'];
$documentosAsociados = $handlerResult['documentosAsociados'];
$historial = $handlerResult['historial'];
$controllerError = $handlerResult['controllerError'];
$notFoundMessage = $handlerResult['notFoundMessage'];
$inputError = $handlerResult['inputError'];

$metadata = convenio_prepare_view_metadata($convenio);
$empresaNombre = $metadata['empresaNombre'];
$empresaUrl = $metadata['empresaUrl'];
$downloadUrl = $metadata['downloadUrl'];
$downloadLabel = $metadata['downloadLabel'];
$diasRestantesLabel = $metadata['diasRestantesLabel'];
$observacionesLabel = $metadata['observacionesLabel'];
$versionLabel = $metadata['versionLabel'];
$estatusBadgeClass = $metadata['estatusBadgeClass'];
$estatusBadgeLabel = $metadata['estatusBadgeLabel'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalle de Convenio · Residencias Profesionales</title>

    <!-- Estilos globales del módulo -->
    <link rel="stylesheet" href="../../assets/stylesrecidencia.css" />
    <!-- (Opcional) Estilos específicos para esta vista -->
    <link rel="stylesheet" href="../../assets/css/convenios/convenio_view.css" />

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <!-- Topbar -->
            <header class="topbar">
                <div>
                    <h2>📑 Detalle del Convenio<?php echo $convenioId !== null ? ' #' . htmlspecialchars((string) $convenioId, ENT_QUOTES, 'UTF-8') : ''; ?></h2>
                    <nav class="breadcrumb">
                        <a href="../../index.php">Inicio</a>
                        <span>›</span>
                        <a href="convenio_list.php">Convenios</a>
                        <span>›</span>
                        <span>Ver</span>
                    </nav>
                </div>
                <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
                    <?php if ($convenioId !== null): ?>
                        <a href="convenio_edit.php?id=<?php echo urlencode((string) $convenioId); ?>" class="btn">✏️ Editar</a>
                    <?php endif; ?>
                    <?php if ($downloadUrl !== null): ?>
                        <a href="<?php echo htmlspecialchars($downloadUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($downloadLabel, ENT_QUOTES, 'UTF-8'); ?></a>
                    <?php endif; ?>
                    <a href="convenio_list.php" class="btn secondary">⬅ Volver</a>
                </div>
            </header>

            <?php if ($controllerError !== null): ?>
                <section class="card">
                    <div class="content">
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($controllerError, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($inputError !== null): ?>
                <section class="card">
                    <div class="content">
                        <div class="alert alert-warning">
                            <?php echo htmlspecialchars($inputError, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($notFoundMessage !== null): ?>
                <section class="card">
                    <div class="content">
                        <div class="alert alert-warning">
                            <?php echo htmlspecialchars($notFoundMessage, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <?php if ($convenio !== null): ?>
                <!-- Información principal -->
                <section class="card">
                    <header>🧾 Información del Convenio</header>
                    <div class="content">
                        <div class="grid">
                            <div class="field">
                                <label>Empresa</label>
                                <div>
                                    <?php if ($empresaUrl !== null): ?>
                                        <a href="<?php echo htmlspecialchars($empresaUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn">🏢 <?php echo htmlspecialchars((string) $empresaNombre, ENT_QUOTES, 'UTF-8'); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars((string) ($empresaNombre ?? 'Sin empresa'), ENT_QUOTES, 'UTF-8'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="field">
                                <label>Estatus</label>
                                <div><span class="<?php echo htmlspecialchars($estatusBadgeClass, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($estatusBadgeLabel, ENT_QUOTES, 'UTF-8'); ?></span></div>
                            </div>

                            <div class="field">
                                <label>Folio</label>
                                <div><?php echo htmlspecialchars((string) ($convenio['folio'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Versión</label>
                                <div><?php echo htmlspecialchars($versionLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Última actualización</label>
                                <div><?php echo htmlspecialchars((string) ($convenio['actualizado_en_label'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Fecha de inicio</label>
                                <div><?php echo htmlspecialchars((string) ($convenio['fecha_inicio_label'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Fecha de término</label>
                                <div><?php echo htmlspecialchars((string) ($convenio['fecha_fin_label'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Días restantes</label>
                                <div><?php echo htmlspecialchars($diasRestantesLabel, ENT_QUOTES, 'UTF-8'); ?></div>
                            </div>

                            <div class="field">
                                <label>Archivo adjunto (PDF)</label>
                                <div>
                                    <?php if ($downloadUrl !== null): ?>
                                        <a class="btn" href="<?php echo htmlspecialchars($downloadUrl, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener noreferrer">📥 Descargar</a>
                                    <?php else: ?>
                                        <span class="text-muted">No disponible</span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="field col-span-2">
                                <label>Notas / Observaciones</label>
                                <div class="text-muted"><?php echo $observacionesLabel; ?></div>
                            </div>
                        </div>

                        <div class="actions" style="justify-content:flex-start; margin-top:14px;">
                            <?php if ($convenioId !== null && isset($convenio['empresa_id'])): ?>
                                <a href="convenio_add.php?empresa=<?php echo urlencode((string) $convenio['empresa_id']); ?>&copy=<?php echo urlencode((string) $convenioId); ?>" class="btn">🔁 Renovar (nueva versión)</a>
                            <?php endif; ?>
                            <?php if ($empresaUrl !== null): ?>
                                <a href="<?php echo htmlspecialchars($empresaUrl, ENT_QUOTES, 'UTF-8'); ?>" class="btn">🏢 Ver empresa</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>

                <!-- Observaciones de machote -->
                <section class="card">
                    <header>📝 Observaciones de Machote (cláusula por cláusula)</header>
                    <div class="content">
                        <?php if (count($machoteObservaciones) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cláusula</th>
                                        <th>Comentario</th>
                                        <th>Estatus</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($machoteObservaciones as $index => $observacion): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars((string) ($observacion['numero'] ?? ($index + 1)), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo htmlspecialchars((string) ($observacion['clausula'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td><?php echo nl2br(htmlspecialchars((string) ($observacion['comentario'] ?? ''), ENT_QUOTES, 'UTF-8')); ?></td>
                                            <td>
                                                <span class="badge <?php echo htmlspecialchars((string) ($observacion['badge_class'] ?? 'secondary'), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars((string) ($observacion['badge_label'] ?? ($observacion['estatus'] ?? 'N/D')), ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No hay observaciones registradas para el machote.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Documentos vinculados al convenio (si aplican) -->
                <section class="card">
                    <header>📂 Documentos Asociados</header>
                    <div class="content">
                        <?php if (count($documentosAsociados) > 0): ?>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Documento</th>
                                        <th>Estatus</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($documentosAsociados as $documento): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars((string) ($documento['titulo'] ?? 'Documento'), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <span class="badge <?php echo htmlspecialchars((string) ($documento['badge_class'] ?? 'secondary'), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?php echo htmlspecialchars((string) ($documento['badge_label'] ?? ($documento['estatus'] ?? 'N/D')), ENT_QUOTES, 'UTF-8'); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars((string) ($documento['fecha'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></td>
                                            <td>
                                                <?php if (!empty($documento['url'])): ?>
                                                    <a href="<?php echo htmlspecialchars((string) $documento['url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn" target="_blank" rel="noopener noreferrer">📄 Ver</a>
                                                <?php elseif (!empty($documento['upload_url'])): ?>
                                                    <a href="<?php echo htmlspecialchars((string) $documento['upload_url'], ENT_QUOTES, 'UTF-8'); ?>" class="btn primary">⬆️ Subir</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Sin acciones</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <p class="text-muted">No hay documentos asociados registrados.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <!-- Bitácora / Historial -->
                <section class="card">
                    <header>🕒 Historial</header>
                    <div class="content">
                        <?php if (count($historial) > 0): ?>
                            <ul style="margin:0; padding-left:18px; color:#334155">
                                <?php foreach ($historial as $evento): ?>
                                    <li>
                                        <strong><?php echo htmlspecialchars((string) ($evento['fecha'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></strong>
                                        —
                                        <?php echo htmlspecialchars((string) ($evento['descripcion'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Sin movimientos registrados en el historial.</p>
                        <?php endif; ?>
                    </div>
                </section>

                <div class="field">
                    <label>Fecha de registro</label>
                    <div><?php echo htmlspecialchars((string) ($convenio['creado_en_label'] ?? 'N/D'), ENT_QUOTES, 'UTF-8'); ?></div>
                </div>

                <!-- Acciones finales -->
                <section class="card">
                    <div class="content actions">
                        <?php if ($convenioId !== null): ?>
                            <a href="convenio_edit.php?id=<?php echo urlencode((string) $convenioId); ?>" class="btn primary">✏️ Editar Convenio</a>
                            <a href="convenio_delete.php?id=<?php echo urlencode((string) $convenioId); ?>" class="btn danger">🗑️ Eliminar Convenio</a>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>

        </main>
    </div>
</body>

</html>
