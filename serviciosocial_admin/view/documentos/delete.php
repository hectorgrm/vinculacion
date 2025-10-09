<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Documento · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/documentos/documentosdelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>🗑️ Eliminar Documento</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger-card">
                <header>⚠️ Confirmar Eliminación</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de eliminar el siguiente documento de forma <strong>permanente</strong> del
                        sistema.
                        Esta acción no se puede deshacer. Por favor, confirma que deseas continuar.
                    </p>

                    <!-- 📁 Información del documento a eliminar -->
                    <div class="document-info">
                        <p><strong>ID:</strong> #D-001</p>
                        <p><strong>Nombre:</strong> Solicitud de Servicio Social</p>
                        <p><strong>Tipo:</strong> Global</p>
                        <p><strong>Periodo:</strong> Enero - Junio 2025</p>
                        <p><strong>Estado:</strong> Disponible</p>
                        <p><strong>Fecha de publicación:</strong> 2025-01-02</p>
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