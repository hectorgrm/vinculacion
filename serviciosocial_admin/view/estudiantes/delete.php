<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eliminar Estudiante · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/estudiante/estudiantedelete.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Eliminar Estudiante</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card danger-card">
                <header>⚠️ Confirmar Eliminación</header>
                <div class="content">
                    <p class="warning-text">
                        Estás a punto de <strong>eliminar permanentemente</strong> el siguiente estudiante del sistema:
                    </p>

                    <div class="student-info">
                        <p><strong>Nombre:</strong> Ana Rodríguez</p>
                        <p><strong>Matrícula:</strong> A012345</p>
                        <p><strong>Carrera:</strong> Ingeniería en Informática</p>
                        <p><strong>Correo:</strong> ana.rodriguez@alumno.edu.mx</p>
                    </div>

                    <p class="note">
                        ⚠️ Esta acción no se puede deshacer. Todos los registros asociados al estudiante (documentos,
                        periodos, etc.) podrían eliminarse o quedar sin relación.
                    </p>

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