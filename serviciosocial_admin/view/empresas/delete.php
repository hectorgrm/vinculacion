<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Empresa · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/empresa/empresadelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Eliminar Empresa</h2>
                <a href="../empresas/list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger-card">
                <header>⚠️ Confirmar Eliminación</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de <strong>eliminar permanentemente</strong> la siguiente empresa del sistema:
                    </p>

                    <div class="empresa-info">
                        <p><strong>Nombre:</strong> Casa del Barrio</p>
                        <p><strong>RFC:</strong> CDB810101AA1</p>
                        <p><strong>Contacto:</strong> José Manuel Velador</p>
                        <p><strong>Email:</strong> contacto@casadelbarrio.mx</p>
                        <p><strong>Teléfono:</strong> (33) 1234 5678</p>
                        <p><strong>Estado:</strong> <span class="badge activo">Activo</span></p>
                    </div>

                    <p class="note">
                        ⚠️ Esta acción no se puede deshacer. Todos los convenios, plazas o registros asociados a esta
                        empresa
                        podrían eliminarse o quedar sin relación.
                        Confirma que deseas proceder con esta acción.
                    </p>

                    <div class="actions">
                        <button type="submit" class="btn danger">🗑️ Eliminar Definitivamente</button>
                        <a href="../empresas/list.php" class="btn secondary">Cancelar</a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>