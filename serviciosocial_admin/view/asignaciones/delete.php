<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Asignación · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/asignaciones/asignacionesdelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Eliminar Asignación</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger">
                <header>⚠️ Confirmar Eliminación</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de eliminar la siguiente asignación. Esta acción es <strong>permanente</strong> y
                        no se puede deshacer.
                    </p>

                    <div class="info-grid">
                        <div>
                            <h4>ID Asignación</h4>
                            <p>#001</p>
                        </div>
                        <div>
                            <h4>Estudiante</h4>
                            <p>Laura Méndez – 2056764</p>
                        </div>
                        <div>
                            <h4>Carrera</h4>
                            <p>Ingeniería en Informática</p>
                        </div>
                        <div>
                            <h4>Empresa</h4>
                            <p>Casa del Barrio</p>
                        </div>
                        <div>
                            <h4>Plaza</h4>
                            <p>Desarrollador Web</p>
                        </div>
                        <div>
                            <h4>Convenio</h4>
                            <p>Convenio Marco v1.0</p>
                        </div>
                        <div>
                            <h4>Fecha de Asignación</h4>
                            <p>2025-01-20</p>
                        </div>
                        <div>
                            <h4>Estado</h4>
                            <span class="badge activo">En curso</span>
                        </div>
                    </div>

                    <div class="actions">
                        <button type="submit" class="btn danger">🗑️ Eliminar Definitivamente</button>
                        <a href="list.php" class="btn secondary">Cancelar</a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>