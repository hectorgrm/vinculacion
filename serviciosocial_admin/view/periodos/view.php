<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles del Periodo · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/periodos/periodosview.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Detalles del Periodo</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <!-- 🗂 Información general -->
            <section class="card">
                <header>📆 Información del Periodo</header>
                <div class="content info-grid">
                    <div>
                        <h4>ID del Periodo</h4>
                        <p>#P2025-01</p>
                    </div>
                    <div>
                        <h4>Nombre</h4>
                        <p>Servicio Social Enero - Junio 2025</p>
                    </div>
                    <div>
                        <h4>Estado</h4>
                        <span class="badge activo">Activo</span>
                    </div>
                    <div>
                        <h4>Capacidad</h4>
                        <p>150 estudiantes</p>
                    </div>
                    <div>
                        <h4>Fecha de Inicio</h4>
                        <p>2025-01-15</p>
                    </div>
                    <div>
                        <h4>Fecha de Finalización</h4>
                        <p>2025-06-30</p>
                    </div>
                </div>
            </section>

            <!-- 📊 Estadísticas -->
            <section class="card">
                <header>📊 Estadísticas del Periodo</header>
                <div class="content stats-grid">
                    <div>
                        <h4>Estudiantes Asignados</h4>
                        <p>120</p>
                    </div>
                    <div>
                        <h4>Estudiantes Concluidos</h4>
                        <p>45</p>
                    </div>
                    <div>
                        <h4>En Curso</h4>
                        <p>60</p>
                    </div>
                    <div>
                        <h4>Pendientes</h4>
                        <p>15</p>
                    </div>
                </div>
            </section>

            <!-- 📝 Descripción -->
            <section class="card">
                <header>📝 Descripción</header>
                <div class="content">
                    <p class="descripcion">
                        Este periodo corresponde al ciclo Enero - Junio 2025 del programa de Servicio Social.
                        Durante este periodo, los estudiantes participarán en actividades relacionadas con sus áreas
                        profesionales
                        en empresas, instituciones gubernamentales o asociaciones civiles.
                    </p>
                </div>
            </section>

            <!-- ⚙️ Acciones -->
            <div class="actions">
                <a href="edit.php?id=P2025-01" class="btn primary">✏️ Editar Periodo</a>
                <a href="delete.php?id=P2025-01" class="btn danger">🗑️ Eliminar Periodo</a>
            </div>
        </main>
    </div>
</body>

</html>