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
                    <h2>📑 Detalle del Convenio</h2>
                    <nav class="breadcrumb">
                        <a href="../../index.php">Inicio</a>
                        <span>›</span>
                        <a href="convenio_list.php">Convenios</a>
                        <span>›</span>
                        <span>Ver</span>
                    </nav>
                </div>
                <div class="top-actions" style="display:flex; gap:10px; flex-wrap:wrap;">
                    <a href="convenio_edit.php?id=12" class="btn">✏️ Editar</a>
                    <a href="../../uploads/convenios/convenio_12.pdf" class="btn" target="_blank">📄 Ver PDF</a>
                    <a href="convenio_list.php" class="btn secondary">⬅ Volver</a>
                </div>
            </header>

            <!-- Información principal -->
            <section class="card">
                <header>🧾 Información del Convenio</header>
                <div class="content">
                    <div class="grid">
                        <div class="field">
                            <label>Empresa</label>
                            <div>
                                <a href="../empresa/empresa_view.php?id=45" class="btn">🏢 Casa del Barrio</a>
                            </div>
                        </div>

                        <div class="field">
                            <label>Estatus</label>
                            <div><span class="badge ok">Vigente</span></div>
                        </div>

                        <div class="field">
                            <label>Versión</label>
                            <div>v1.2</div>
                        </div>

                        <div class="field">
                            <label>Última actualización</label>
                            <div>02/10/2025</div>
                        </div>

                        <div class="field">
                            <label>Fecha de inicio</label>
                            <div>01/07/2025</div>
                        </div>

                        <div class="field">
                            <label>Fecha de término</label>
                            <div>30/06/2026</div>
                        </div>

                        <div class="field">
                            <label>Días restantes</label>
                            <div>263</div>
                        </div>

                        <div class="field">
                            <label>Archivo adjunto (PDF)</label>
                            <div>
                                <a class="btn" href="../../uploads/convenios/convenio_12.pdf" target="_blank">📥
                                    Descargar</a>
                            </div>
                        </div>

                        <div class="field col-span-2">
                            <label>Notas / Observaciones</label>
                            <div class="text-muted">Convenio marco para prácticas y residencias; incluye anexo técnico
                                para proyectos TI.</div>
                        </div>
                    </div>

                    <div class="actions" style="justify-content:flex-start; margin-top:14px;">
                        <a href="convenio_add.php?empresa=45&copy=12" class="btn">🔁 Renovar (nueva versión)</a>
                        <a href="../empresa/empresa_view.php?id=45" class="btn">🏢 Ver empresa</a>
                    </div>
                </div>
            </section>

            <!-- Observaciones de machote -->
            <section class="card">
                <header>📝 Observaciones de Machote (cláusula por cláusula)</header>
                <div class="content">
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
                            <tr>
                                <td>1</td>
                                <td>Objeto</td>
                                <td>Solicitan explicitar alcance del proyecto y KPIs.</td>
                                <td><span class="badge secondary">En revisión</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Confidencialidad</td>
                                <td>Se acepta texto del machote sin cambios.</td>
                                <td><span class="badge ok">Aprobado</span></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>Vigencia</td>
                                <td>Proponen alinear al ciclo escolar Ago–Dic / Feb–Jul.</td>
                                <td><span class="badge warn">Pendiente</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="actions" style="margin-top:12px;">
                        <a href="../machote/revisar.php?id_empresa=45&convenio=12" class="btn primary">✏️ Revisar
                            Machote</a>
                    </div>
                </div>
            </section>

            <!-- Documentos vinculados al convenio (si aplican) -->
            <section class="card">
                <header>📂 Documentos Asociados</header>
                <div class="content">
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
                            <tr>
                                <td>Anexo Técnico</td>
                                <td><span class="badge ok">Aprobado</span></td>
                                <td>12/09/2025</td>
                                <td><a href="../../uploads/anexo_tecnico_12.pdf" class="btn" target="_blank">📄 Ver</a>
                                </td>
                            </tr>
                            <tr>
                                <td>Oficio de intención</td>
                                <td><span class="badge warn">Pendiente</span></td>
                                <td>—</td>
                                <td>
                                    <a href="../documentos/documento_upload.php?empresa=45&convenio=12"
                                        class="btn primary">⬆️ Subir</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Bitácora / Historial -->
            <section class="card">
                <header>🕒 Historial</header>
                <div class="content">
                    <ul style="margin:0; padding-left:18px; color:#334155">
                        <li><strong>02/10/2025</strong> — Actualizado estatus a <em>Vigente</em>.</li>
                        <li><strong>20/09/2025</strong> — Subido anexo técnico (PDF).</li>
                        <li><strong>15/09/2025</strong> — Observaciones de machote registradas.</li>
                        <li><strong>10/09/2025</strong> — Convenio creado (v1.2) para empresa “Casa del Barrio”.</li>
                    </ul>
                </div>
            </section>

            <!-- Acciones finales -->
            <div class="actions">
                <a href="convenio_edit.php?id=12" class="btn primary">✏️ Editar Convenio</a>
                <a href="convenio_delete.php?id=12" class="btn danger">🗑️ Eliminar Convenio</a>
            </div>
        </main>
    </div>
</body>

</html>