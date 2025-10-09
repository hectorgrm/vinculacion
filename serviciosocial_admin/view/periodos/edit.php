<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Periodo · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/periodos/periodosedit.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Editar Periodo</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>✏️ Actualizar Información del Periodo</header>
                <div class="content">
                    <form>
                        <div class="full">
                            <label for="nombre">Nombre del Periodo</label>
                            <input type="text" id="nombre" value="Servicio Social Enero - Junio 2025">
                        </div>

                        <div>
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" id="fecha_inicio" value="2025-01-15">
                        </div>

                        <div>
                            <label for="fecha_fin">Fecha de Finalización</label>
                            <input type="date" id="fecha_fin" value="2025-06-30">
                        </div>

                        <div>
                            <label for="estado">Estado</label>
                            <select id="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="activo" selected>Activo</option>
                                <option value="finalizado">Finalizado</option>
                            </select>
                        </div>

                        <div>
                            <label for="capacidad">Capacidad Máxima de Estudiantes</label>
                            <input type="number" id="capacidad" value="150">
                        </div>

                        <div class="full">
                            <label for="descripcion">Descripción del Periodo</label>
                            <textarea id="descripcion"
                                rows="4">Periodo correspondiente al ciclo Enero - Junio 2025 del programa de Servicio Social.</textarea>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn primary">💾 Guardar Cambios</button>
                            <a href="list.php" class="btn secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>

</html>