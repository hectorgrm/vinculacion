<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Periodo · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/periodos/periodosdelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Eliminar Periodo</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger">
                <header>⚠️ Confirmar Eliminación del Periodo</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de eliminar el siguiente periodo. Esta acción es <strong>permanente</strong>
                        y podría afectar el historial de estudiantes y asignaciones asociadas a este periodo.
                    </p>

                    <div class="info-grid">
                        <div>
                            <h4>ID del Periodo</h4>
                            <p>#P2025-01</p>
                        </div>
                        <div>
                            <h4>Nombre</h4>
                            <p>Servicio Social Enero - Junio 2025</p>
                        </div>
                        <div>
                            <h4>Fecha de Inicio</h4>
                            <p>2025-01-15</p>
                        </div>
                        <div>
                            <h4>Fecha de Finalización</h4>
                            <p>2025-06-30</p>
                        </div>
                        <div>
                            <h4>Estado</h4>
                            <span class="badge activo">Activo</span>
                        </div>
                        <div>
                            <h4>Capacidad Máxima</h4>
                            <p>150 estudiantes</p>
                        </div>
                        <div>
                            <h4>Estudiantes Asignados</h4>
                            <p>120</p>
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