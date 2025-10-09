<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Convenios · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/convenio/conveniolist.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Gestión de Convenios</h2>
                <button class="btn primary" onclick="window.location.href='add.php'">+ Nuevo Convenio</button>
            </header>

            <section class="card">
                <header>Listado de Convenios</header>
                <div class="content">
                    <!-- 🔎 Barra de búsqueda -->
                    <form class="search-bar" method="get">
                        <input type="text" name="search" placeholder="Buscar por empresa o estado...">
                        <button type="submit" class="btn primary">Buscar</button>
                        <a href="list.php" class="btn secondary">Restablecer</a>
                    </form>

                    <!-- 📊 Tabla -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Empresa</th>
                                <th>Fecha Inicio</th>
                                <th>Fecha Fin</th>
                                <th>Versión</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ejemplo de fila (luego dinámico con PHP) -->
                            <tr>
                                <td>#1</td>
                                <td>Casa del Barrio</td>
                                <td>2025-01-15</td>
                                <td>2026-01-14</td>
                                <td>v1.0</td>
                                <td><span class="badge vigente">Vigente</span></td>
                                <td class="actions">
                                    <a href="view.php?id=1" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=1" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=1" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#2</td>
                                <td>Industrias Yakumo</td>
                                <td>2024-06-01</td>
                                <td>2025-06-01</td>
                                <td>v1.1</td>
                                <td><span class="badge en_revision">En Revisión</span></td>
                                <td class="actions">
                                    <a href="view.php?id=2" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=2" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=2" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#3</td>
                                <td>Tequila ECT</td>
                                <td>2023-01-10</td>
                                <td>2024-01-10</td>
                                <td>v1.3</td>
                                <td><span class="badge vencido">Vencido</span></td>
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