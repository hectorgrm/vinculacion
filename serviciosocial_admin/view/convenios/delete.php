<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Convenio · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/convenio/conveniodel.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Eliminar Convenio</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger-card">
                <header>⚠️ Confirmar Eliminación de Convenio</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de <strong>eliminar permanentemente</strong> el siguiente convenio del sistema:
                    </p>

                    <div class="convenio-info">
                        <p><strong>ID:</strong> #001</p>
                        <p><strong>Empresa:</strong> Casa del Barrio A.C.</p>
                        <p><strong>Tipo:</strong> Convenio Marco</p>
                        <p><strong>Versión:</strong> v1.0</p>
                        <p><strong>Vigencia:</strong> 15/01/2025 - 14/01/2026</p>
                        <p><strong>Estado:</strong> <span class="badge vigente">Vigente</span></p>
                    </div>

                    <p class="note">
                        ⚠️ Esta acción no se puede deshacer. Al eliminar este convenio:
                    </p>
                    <ul class="note-list">
                        <li>Se perderá el historial de versiones y actualizaciones.</li>
                        <li>Se eliminarán todas las plazas asociadas a este convenio.</li>
                        <li>Los estudiantes vinculados podrían quedar sin asignación.</li>
                    </ul>

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