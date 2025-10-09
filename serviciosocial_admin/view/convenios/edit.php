<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Convenio · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/convenio/convenioedit.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Editar Convenio</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>📑 Información del Convenio</header>
                <div class="content">
                    <form>
                        <div>
                            <label for="empresa">Empresa</label>
                            <select id="empresa">
                                <option value="">Seleccione la empresa</option>
                                <option value="1" selected>Casa del Barrio</option>
                                <option value="2">Industrias Yakumo</option>
                                <option value="3">Tequila ECT</option>
                            </select>
                        </div>

                        <div>
                            <label for="tipo">Tipo de Convenio</label>
                            <select id="tipo">
                                <option value="marco" selected>Convenio Marco</option>
                                <option value="especifico">Convenio Específico</option>
                            </select>
                        </div>

                        <div>
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" id="fecha_inicio" value="2025-01-15" />
                        </div>

                        <div>
                            <label for="fecha_fin">Fecha de Finalización</label>
                            <input type="date" id="fecha_fin" value="2026-01-14" />
                        </div>

                        <div>
                            <label for="version">Versión</label>
                            <input type="text" id="version" value="v1.0" />
                        </div>

                        <div>
                            <label for="estado">Estado</label>
                            <select id="estado">
                                <option value="vigente" selected>Vigente</option>
                                <option value="en_revision">En Revisión</option>
                                <option value="vencido">Vencido</option>
                            </select>
                        </div>

                        <div class="full">
                            <label for="archivo">Actualizar Archivo del Convenio (PDF)</label>
                            <input type="file" id="archivo" accept=".pdf" />
                            <p class="file-note">Archivo actual: <a href="../../uploads/convenios/convenio001.pdf"
                                    target="_blank">📄 convenio001.pdf</a></p>
                        </div>

                        <div class="full">
                            <label for="observaciones">Observaciones</label>
                            <textarea id="observaciones"
                                rows="4">Convenio marco para colaboración en proyectos tecnológicos y comunitarios.</textarea>
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