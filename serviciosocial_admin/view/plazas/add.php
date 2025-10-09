<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Registrar Nueva Plaza · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazaadd.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Registrar Nueva Plaza</h2>
                <a href="list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>📋 Información de la Plaza</header>
                <div class="content">
                    <form>
                        <div>
                            <label for="empresa">Empresa</label>
                            <select id="empresa">
                                <option value="" disabled selected>Seleccione la empresa</option>
                                <option value="1">Casa del Barrio</option>
                                <option value="2">Tequila ECT</option>
                                <option value="3">Industrias Yakumo</option>
                            </select>
                        </div>

                        <div>
                            <label for="convenio">Convenio Asociado</label>
                            <select id="convenio">
                                <option value="" disabled selected>Seleccione un convenio</option>
                                <option value="1">Convenio Marco v1.0</option>
                                <option value="2">Convenio Específico v2.0</option>
                            </select>
                        </div>

                        <div>
                            <label for="nombre">Nombre de la Plaza</label>
                            <input type="text" id="nombre" placeholder="Ej: Desarrollador Web">
                        </div>

                        <div>
                            <label for="cupos">Cupos Totales</label>
                            <input type="number" id="cupos" placeholder="Ej: 5">
                        </div>

                        <div>
                            <label for="fecha_inicio">Fecha de Inicio</label>
                            <input type="date" id="fecha_inicio">
                        </div>

                        <div>
                            <label for="fecha_fin">Fecha de Finalización</label>
                            <input type="date" id="fecha_fin">
                        </div>

                        <div>
                            <label for="estado">Estado de la Plaza</label>
                            <select id="estado">
                                <option value="disponible">Disponible</option>
                                <option value="completa">Cupo Lleno</option>
                                <option value="cerrada">Cerrada</option>
                            </select>
                        </div>

                        <div class="full">
                            <label for="descripcion">Descripción del Puesto</label>
                            <textarea id="descripcion" rows="5"
                                placeholder="Ej: Participará en el desarrollo de aplicaciones web, soporte técnico y documentación."></textarea>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn primary">💾 Registrar Plaza</button>
                            <a href="list.php" class="btn secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>
</body>

</html>