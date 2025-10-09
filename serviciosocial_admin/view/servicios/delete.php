<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Servicio · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/servicios/serviciosdelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>🗑️ Eliminar Servicio Social</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger-card">
                <header>⚠️ Confirmar Eliminación</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de eliminar el siguiente <strong>servicio social</strong> de forma
                        <strong>permanente</strong> del sistema.
                        Esta acción no se puede deshacer. Por favor confirma que deseas continuar.
                    </p>

                    <!-- 📄 Información del servicio a eliminar -->
                    <div class="service-info">
                        <p><strong>ID Servicio:</strong> #S-001</p>
                        <p><strong>Estudiante:</strong> Laura Méndez (2056764)</p>
                        <p><strong>Empresa:</strong> Secretaría de Innovación</p>
                        <p><strong>Proyecto:</strong> Sistema de Gestión Documental</p>
                        <p><strong>Periodo:</strong> Enero - Junio 2025</p>
                        <p><strong>Horas:</strong> 480 / 480</p>
                        <p><strong>Estado:</strong> Concluido</p>
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