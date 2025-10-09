<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detalles de la Asignación · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/asignaciones/asignacionesview.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Detalles de la Asignación</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <!-- 📁 Datos generales de la asignación -->
            <section class="card">
                <header>📋 Información General</header>
                <div class="content info-grid">
                    <div>
                        <h4>ID Asignación</h4>
                        <p>#001</p>
                    </div>
                    <div>
                        <h4>Estado</h4>
                        <span class="badge activo">En curso</span>
                    </div>
                    <div>
                        <h4>Fecha de Asignación</h4>
                        <p>2025-01-20</p>
                    </div>
                    <div>
                        <h4>Horas Acumuladas</h4>
                        <p>120 / 480 hrs</p>
                    </div>
                </div>
            </section>

            <!-- 🧑‍🎓 Información del estudiante -->
            <section class="card">
                <header>🧑‍🎓 Estudiante Asignado</header>
                <div class="content info-grid">
                    <div>
                        <h4>Nombre</h4>
                        <p>Laura Méndez</p>
                    </div>
                    <div>
                        <h4>Matrícula</h4>
                        <p>2056764</p>
                    </div>
                    <div>
                        <h4>Carrera</h4>
                        <p>Ingeniería en Informática</p>
                    </div>
                    <div>
                        <h4>Semestre</h4>
                        <p>8°</p>
                    </div>
                    <div class="full">
                        <h4>Correo</h4>
                        <p>laura.mendez@alumno.edu.mx</p>
                    </div>
                </div>
            </section>

            <!-- 🏢 Empresa y Convenio -->
            <section class="card">
                <header>🏢 Empresa y Convenio</header>
                <div class="content info-grid">
                    <div>
                        <h4>Empresa</h4>
                        <p>Casa del Barrio</p>
                    </div>
                    <div>
                        <h4>Convenio</h4>
                        <p>Convenio Marco v1.0</p>
                    </div>
                    <div>
                        <h4>Plaza</h4>
                        <p>Desarrollador Web</p>
                    </div>
                    <div>
                        <h4>Cupos Totales</h4>
                        <p>5</p>
                    </div>
                    <div>
                        <h4>Disponibles</h4>
                        <p>2</p>
                    </div>
                </div>
            </section>

            <!-- 📝 Observaciones -->
            <section class="card">
                <header>📝 Observaciones</header>
                <div class="content">
                    <p class="observaciones">
                        El estudiante ha mostrado un excelente desempeño en tareas de desarrollo web.
                        Se recomienda asignarle responsabilidades adicionales en el próximo mes.
                    </p>
                </div>
            </section>

            <!-- ⚙️ Acciones -->
            <div class="actions">
                <a href="edit.php?id=1" class="btn primary">✏️ Editar Asignación</a>
                <a href="delete.php?id=1" class="btn danger">🗑️ Eliminar Asignación</a>
            </div>
        </main>
    </div>
</body>

</html>