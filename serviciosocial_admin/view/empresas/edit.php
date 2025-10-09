<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Empresa · Servicio Social</title>
    <link rel="stylesheet" href="../../assets/css/empresa/empresaedit.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">

</head>

<body>
    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <h2>Editar Empresa</h2>
                <a href="../empresas/list.php" class="btn secondary">⬅ Volver</a>
            </header>

            <section class="card">
                <header>🏢 Información de la Empresa</header>
                <div class="content">
                    <form>
                        <div>
                            <label for="nombre">Nombre de la Empresa</label>
                            <input type="text" id="nombre" placeholder="Ej: Casa del Barrio" />
                        </div>

                        <div>
                            <label for="rfc">RFC</label>
                            <input type="text" id="rfc" placeholder="Ej: CDB810101AA1" />
                        </div>

                        <div>
                            <label for="estado">Estado</label>
                            <select id="estado">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                        </div>

                        <div>
                            <label for="fecha_registro">Fecha de Registro</label>
                            <input type="date" id="fecha_registro" />
                        </div>

                        <div class="full">
                            <label for="direccion">Dirección</label>
                            <input type="text" id="direccion"
                                placeholder="Ej: Calle Reforma #123, Guadalajara, Jalisco" />
                        </div>

                        <div class="full">
                            <label for="descripcion">Descripción / Giro</label>
                            <input type="text" id="descripcion"
                                placeholder="Ej: Asociación civil enfocada al desarrollo comunitario" />
                        </div>
                    </form>
                </div>
            </section>

            <section class="card">
                <header>👤 Información de Contacto</header>
                <div class="content">
                    <form>
                        <div>
                            <label for="contacto">Nombre del Contacto</label>
                            <input type="text" id="contacto" placeholder="Ej: José Manuel Velador" />
                        </div>

                        <div>
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" placeholder="Ej: contacto@casadelbarrio.mx" />
                        </div>

                        <div>
                            <label for="telefono">Teléfono</label>
                            <input type="tel" id="telefono" placeholder="Ej: (33) 1234 5678" />
                        </div>

                        <div>
                            <label for="sitio_web">Sitio Web</label>
                            <input type="url" id="sitio_web" placeholder="Ej: https://casadelbarrio.mx" />
                        </div>
                    </form>
                </div>
            </section>

            <div class="actions">
                <button type="submit" class="btn primary">💾 Guardar Cambios</button>
                <a href="../empresas/list.php" class="btn secondary">Cancelar</a>
            </div>
        </main>
    </div>
</body>

</html>