<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles del Convenio · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/convenio/convenioview.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Detalles del Convenio</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>📑 Información General</header>
                <div class="content">
                    <div class="info-grid">
                        <div>
                            <h4>ID del Convenio</h4>
                            <p>#001</p>
                        </div>
                        <div>
                            <h4>Empresa</h4>
                            <p>Casa del Barrio A.C.</p>
                        </div>
                        <div>
                            <h4>Tipo</h4>
                            <p>Convenio Marco</p>
                        </div>
                        <div>
                            <h4>Versión</h4>
                            <p>v1.0</p>
                        </div>
                        <div>
                            <h4>Fecha de Inicio</h4>
                            <p>15/01/2025</p>
                        </div>
                        <div>
                            <h4>Fecha de Finalización</h4>
                            <p>14/01/2026</p>
                        </div>
                        <div>
                            <h4>Estado</h4>
                            <span class="badge vigente">Vigente</span>
                        </div>
                        <div>
                            <h4>Archivo</h4>
                            <a href="../../uploads/convenios/convenio001.pdf" class="btn small" target="_blank">📄 Ver
                                PDF</a>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <header>📁 Observaciones y Notas</header>
                <div class="content">
                    <p class="observaciones">
                        Este convenio marco tiene como objetivo establecer la colaboración entre el Instituto
                        Tecnológico y la empresa Casa del Barrio A.C.
                        para el desarrollo de proyectos de servicio social en las áreas de tecnología, educación y
                        desarrollo comunitario.
                    </p>
                </div>
            </section>

            <section class="card">
                <header>📜 Historial de Actualizaciones</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Versión</th>
                                <th>Acción</th>
                                <th>Usuario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>15/01/2025</td>
                                <td>v1.0</td>
                                <td>Convenio registrado</td>
                                <td>Admin Vinculación</td>
                            </tr>
                            <tr>
                                <td>20/06/2025</td>
                                <td>v1.1</td>
                                <td>Convenio actualizado con nueva vigencia</td>
                                <td>Lic. José López</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="actions">
                <a href="edit.php?id=1" class="btn primary">✏️ Editar Convenio</a>
                <a href="delete.php?id=1" class="btn danger">🗑️ Eliminar Convenio</a>
            </div>
        </main>
    </div>
</body>

</html>