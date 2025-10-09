<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles de la Plaza · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazaview.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Detalles de la Plaza</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>🏢 Información de la Plaza</header>
                <div class="content">
                    <div class="info-grid">
                        <div>
                            <h4>ID Plaza</h4>
                            <p>#101</p>
                        </div>
                        <div>
                            <h4>Empresa</h4>
                            <p>Casa del Barrio A.C.</p>
                        </div>
                        <div>
                            <h4>Convenio</h4>
                            <p>Convenio Marco v1.0</p>
                        </div>
                        <div>
                            <h4>Nombre de la Plaza</h4>
                            <p>Desarrollador Web</p>
                        </div>
                        <div>
                            <h4>Cupos Totales</h4>
                            <p>5</p>
                        </div>
                        <div>
                            <h4>Cupos Disponibles</h4>
                            <p>2</p>
                        </div>
                        <div>
                            <h4>Estado</h4>
                            <span class="badge disponible">Disponible</span>
                        </div>
                        <div>
                            <h4>Fecha de Creación</h4>
                            <p>15/01/2025</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <header>📝 Descripción</header>
                <div class="content">
                    <p class="descripcion">
                        Esta plaza está destinada al desarrollo de aplicaciones web para proyectos sociales y
                        administrativos.
                        Los estudiantes seleccionados colaborarán con el equipo de TI en tareas de diseño, programación,
                        pruebas y documentación.
                    </p>
                </div>
            </section>

            <section class="card">
                <header>👥 Estudiantes Asignados</header>
                <div class="content">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Matrícula</th>
                                <th>Carrera</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Laura Méndez</td>
                                <td>2056764</td>
                                <td>Ing. Informática</td>
                                <td><span class="badge activo">En curso</span></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Juan Pérez</td>
                                <td>2049821</td>
                                <td>Ing. Sistemas</td>
                                <td><span class="badge finalizado">Concluido</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <div class="actions">
                <a href="edit.php?id=101" class="btn primary">✏️ Editar Plaza</a>
                <a href="delete.php?id=101" class="btn danger">🗑️ Eliminar Plaza</a>
            </div>
        </main>
    </div>
</body>

</html>