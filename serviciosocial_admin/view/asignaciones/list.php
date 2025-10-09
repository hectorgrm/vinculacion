<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Asignaciones · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/asignaciones/asignacioneslist.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Gestión de Asignaciones</h2>
                <button class="btn primary" onclick="window.location.href='add.php'">+ Nueva Asignación</button>
            </header>

            <section class="card">
                <header>📋 Listado de Asignaciones</header>
                <div class="content">
                    <!-- 🔎 Barra de búsqueda -->
                    <form class="search-bar" method="get">
                        <input type="text" name="search" placeholder="Buscar por estudiante, empresa o estado...">
                        <button type="submit" class="btn primary">Buscar</button>
                        <a href="list.php" class="btn secondary">Restablecer</a>
                    </form>

                    <!-- 📊 Tabla de Asignaciones -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estudiante</th>
                                <th>Matrícula</th>
                                <th>Plaza</th>
                                <th>Empresa</th>
                                <th>Convenio</th>
                                <th>Estado</th>
                                <th>Fecha de Asignación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#001</td>
                                <td>Laura Méndez</td>
                                <td>2056764</td>
                                <td>Desarrollador Web</td>
                                <td>Casa del Barrio</td>
                                <td>Convenio Marco v1.0</td>
                                <td><span class="badge activo">En curso</span></td>
                                <td>2025-01-20</td>
                                <td class="actions">
                                    <a href="view.php?id=1" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=1" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=1" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>

                            <tr>
                                <td>#002</td>
                                <td>Juan Pérez</td>
                                <td>2049821</td>
                                <td>Soporte Técnico</td>
                                <td>Tequila ECT</td>
                                <td>Convenio Específico v2.0</td>
                                <td><span class="badge pendiente">Pendiente</span></td>
                                <td>2025-02-01</td>
                                <td class="actions">
                                    <a href="view.php?id=2" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=2" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=2" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>

                            <tr>
                                <td>#003</td>
                                <td>Ana Rodríguez</td>
                                <td>2059982</td>
                                <td>Analista de Datos</td>
                                <td>Industrias Yakumo</td>
                                <td>Convenio Marco v2.0</td>
                                <td><span class="badge finalizado">Concluido</span></td>
                                <td>2025-01-15</td>
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