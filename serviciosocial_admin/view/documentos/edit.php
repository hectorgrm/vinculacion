<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Documento · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/documentos/documentosedit.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>✏️ Editar Documento</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>📄 Actualizar Información del Documento</header>
                <div class="content">
                    <form enctype="multipart/form-data">

                        <div class="full">
                            <label for="nombre">Nombre del Documento</label>
                            <input type="text" id="nombre" value="Solicitud de Servicio Social">
                        </div>

                        <div>
                            <label for="tipo">Tipo de Documento</label>
                            <select id="tipo">
                                <option value="global" selected>Global (para todos los estudiantes)</option>
                                <option value="estudiante">Estudiante específico</option>
                            </select>
                        </div>

                        <div>
                            <label for="periodo">Periodo</label>
                            <select id="periodo">
                                <option value="2025-01" selected>Enero - Junio 2025</option>
                                <option value="2025-02">Agosto - Diciembre 2025</option>
                            </select>
                        </div>

                        <div class="full" id="estudiante-field" style="display: none;">
                            <label for="estudiante">Asignado a Estudiante</label>
                            <select id="estudiante">
                                <option value="">Seleccionar estudiante</option>
                                <option value="1">Laura Méndez - 2056764</option>
                                <option value="2">Juan Pérez - 2049821</option>
                            </select>
                        </div>

                        <div>
                            <label for="estado">Estado del Documento</label>
                            <select id="estado">
                                <option value="pendiente">Pendiente</option>
                                <option value="disponible" selected>Disponible</option>
                            </select>
                        </div>

                        <div>
                            <label for="fecha_publicacion">Fecha de Publicación</label>
                            <input type="date" id="fecha_publicacion" value="2025-01-02">
                        </div>

                        <div class="full">
                            <label for="archivo">Actualizar Archivo (opcional)</label>
                            <input type="file" id="archivo" accept=".pdf,.doc,.docx">
                            <p class="file-note">📁 Archivo actual: <a href="../../uploads/solicitud_servicio.pdf"
                                    target="_blank">solicitud_servicio.pdf</a></p>
                        </div>

                        <div class="full">
                            <label for="descripcion">Descripción / Instrucciones</label>
                            <textarea id="descripcion"
                                rows="4">Este documento debe ser llenado antes de iniciar el servicio social y entregado en físico dentro de los primeros 10 días del periodo.</textarea>
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

    <script>
        // Mostrar campo estudiante solo si el tipo es "estudiante"
        document.getElementById('tipo').addEventListener('change', function () {
            const estField = document.getElementById('estudiante-field');
            estField.style.display = this.value === 'estudiante' ? 'block' : 'none';
        });
    </script>
</body>

</html>