<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Plazas · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazalist.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Gestión de Plazas</h2>
                <button class="btn primary" onclick="window.location.href='add.php'">+ Nueva Plaza</button>
            </header>

            <section class="card">
                <header>Listado de Plazas</header>
                <div class="content">
                    <!-- 🔎 Barra de búsqueda -->
                    <form class="search-bar" method="get">
                        <input type="text" name="search" placeholder="Buscar por empresa, convenio o estado...">
                        <button type="submit" class="btn primary">Buscar</button>
                        <a href="list.php" class="btn secondary">Restablecer</a>
                    </form>

                    <!-- 📊 Tabla de plazas -->
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Empresa</th>
                                <th>Convenio</th>
                                <th>Nombre de Plaza</th>
                                <th>Cupos</th>
                                <th>Disponibles</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>#101</td>
                                <td>Casa del Barrio</td>
                                <td>Convenio Marco v1.0</td>
                                <td>Desarrollo Web</td>
                                <td>5</td>
                                <td>2</td>
                                <td><span class="badge disponible">Disponible</span></td>
                                <td class="actions">
                                    <a href="view.php?id=101" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=101" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=101" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#102</td>
                                <td>Tequila ECT</td>
                                <td>Convenio Específico v1.2</td>
                                <td>Soporte Técnico</td>
                                <td>3</td>
                                <td>0</td>
                                <td><span class="badge completa">Cupo Lleno</span></td>
                                <td class="actions">
                                    <a href="view.php?id=102" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=102" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=102" class="btn small danger">🗑️ Eliminar</a>
                                </td>
                            </tr>
                            <tr>
                                <td>#103</td>
                                <td>Industrias Yakumo</td>
                                <td>Convenio Marco v2.0</td>
                                <td>IoT & Redes</td>
                                <td>4</td>
                                <td>4</td>
                                <td><span class="badge nueva">Reciente</span></td>
                                <td class="actions">
                                    <a href="view.php?id=103" class="btn small">👁️ Ver</a>
                                    <a href="edit.php?id=103" class="btn small">✏️ Editar</a>
                                    <a href="delete.php?id=103" class="btn small danger">🗑️ Eliminar</a>
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