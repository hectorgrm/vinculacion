<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Plaza · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazaedit.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Editar Plaza</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>✏️ Actualizar Información de la Plaza</header>
                <div class="content">
                    <form>
                        <div>
                            <label for="empresa">Empresa</label>
                            <select id="empresa">
                                <option value="1" selected>Casa del Barrio</option>
                                <option value="2">Tequila ECT</option>
                                <option value="3">Industrias Yakumo</option>
                            </select>
                        </div>

                        <div>
                            <label for="convenio">Convenio Asociado</label>
                            <select id="convenio">
                                <option value="1" selected>Convenio Marco v1.0</option>
                                <option value="2">Convenio Específico v2.0</option>
                            </select>
                        </div>

                        <div>
                            <label for="nombre">Nombre de la Plaza</label>
                            <input type="text" id="nombre" value="Desarrollador Web">
                        </div>

                        <div>
                            <label for="cupos">Cupos Totales</label>
                            <input type="number" id="cupos" value="5">
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
                            <label for="estado">Estado de la Plaza</label>
                            <select id="estado">
                                <option value="disponible" selected>Disponible</option>
                                <option value="completa">Cupo Lleno</option>
                                <option value="cerrada">Cerrada</option>
                            </select>
                        </div>

                        <div class="full">
                            <label for="descripcion">Descripción del Puesto</label>
                            <textarea id="descripcion"
                                rows="5">Participará en el desarrollo de sistemas web internos, soporte técnico y documentación técnica del proyecto.</textarea>
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