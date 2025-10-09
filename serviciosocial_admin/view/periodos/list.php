<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Periodos · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/periodos/periodoslist.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Gestión de Periodos</h2>
                <button class="btn primary" onclick="window.location.href='add.php'">+ Nuevo Periodo</button>
            </header>

            <section class="card">
                <header>📆 Lista de Periodos Activos y Finalizados</header>
                <div class="content">
                    <!-- 🔎 Barra de búsqueda -->
                    <form class="search-bar" method="get">
                        <input type="text" name="search" placeholder="Buscar por nombre o estado...">
                        <button type="submit" class="btn primary">Buscar</button>
                        <a href="list.php" class="btn secondary">Restablecer</a>
                    </form>

                    <!-- 📊 Tabla de periodos -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre del Periodo</th>
                                <th>Fecha de Inicio</th>
                                <th>Fecha de Fin</th>
                                <th>Estado</th>
                                <th>Total Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#P2025-01</td>
                                <td>Servicio Social Enero - Junio 2025</td>
                                <td>2025-01-15</td>
                                <td>2025-06-30</td>
                                <td><span class="badge activo">Activo</span></td>
                                <td>120</td>
                                <td class="actions">
                                    <a href="view.php?id=P2025-01" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=P2025-01" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=P2025-01" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#P2024-02</td>
                                <td>Servicio Social Agosto - Diciembre 2024</td>
                                <td>2024-08-01</td>
                                <td>2024-12-15</td>
                                <td><span class="badge finalizado">Finalizado</span></td>
                                <td>95</td>
                                <td class="actions">
                                    <a href="view.php?id=P2024-02" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=P2024-02" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=P2024-02" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#P2025-02</td>
                                <td>Servicio Social Agosto - Diciembre 2025</td>
                                <td>2025-08-01</td>
                                <td>2025-12-15</td>
                                <td><span class="badge pendiente">Pendiente</span></td>
                                <td>--</td>
                                <td class="actions">
                                    <a href="view.php?id=P2025-02" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=P2025-02" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=P2025-02" class="btn small danger">🗑️ Eliminar</a>
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