<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Servicios · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/servicios/servicioslist.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>📊 Gestión de Servicios Sociales</h2>
                <a href="add.php" class="btn primary">+ Nuevo Servicio</a>
            </header>

            <section class="card">
                <header>Listado de Servicios</header>
                <div class="content">

                    <!-- 🔎 Barra de búsqueda -->
                    <form class="search-bar" method="get">
                        <input type="text" name="search" placeholder="Buscar por estudiante, empresa o estado...">
                        <button type="submit" class="btn primary">Buscar</button>
                        <a href="list.php" class="btn secondary">Restablecer</a>
                    </form>

                    <!-- 📋 Tabla de servicios -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estudiante</th>
                                <th>Empresa</th>
                                <th>Proyecto</th>
                                <th>Periodo</th>
                                <th>Horas</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#S-001</td>
                                <td>Laura Méndez</td>
                                <td>Secretaría de Innovación</td>
                                <td>Sistema de Documentación</td>
                                <td>Enero - Junio 2025</td>
                                <td>480 / 480</td>
                                <td><span class="badge finalizado">Concluido</span></td>
                                <td class="actions">
                                    <a href="view.php?id=1" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=1" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=1" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>

                            <tr>
                                <td>#S-002</td>
                                <td>Juan Pérez</td>
                                <td>Municipio de Guadalajara</td>
                                <td>Plataforma de Gestión</td>
                                <td>Enero - Junio 2025</td>
                                <td>320 / 480</td>
                                <td><span class="badge en-curso">En curso</span></td>
                                <td class="actions">
                                    <a href="view.php?id=2" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=2" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=2" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>

                            <tr>
                                <td>#S-003</td>
                                <td>María López</td>
                                <td>Secretaría de Educación</td>
                                <td>Aplicación de Control Escolar</td>
                                <td>Agosto - Diciembre 2024</td>
                                <td>0 / 480</td>
                                <td><span class="badge pendiente">Pendiente</span></td>
                                <td class="actions">
                                    <a href="view.php?id=3" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=3" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=3" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </section>
        </main>
    </div>
</body>

</html>