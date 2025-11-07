<?php
declare(strict_types=1);

// require_once __DIR__ . '/handler/rp_estudiante_add_handler.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo Estudiante · Residencia Profesional</title>

    <link rel="stylesheet" href="../../assets/css/global.css" />
    <link rel="stylesheet" href="../../assets/css/dashboard.css" />
    <link rel="stylesheet" href="../../assets/css/estudiantes/estudiante_add.css">

</head>

<body>

    <div class="app">
        <!-- Sidebar -->
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <!-- Main -->
        <main class="main">
            <header class="topbar">
                <div>
                    <h2>➕ Nuevo Estudiante</h2>
                    <p class="subtitle">Registra un estudiante vinculado a una empresa y convenio.</p>
                </div>
                <div class="actions">
                    <a href="estudiante_list.php" class="btn secondary">← Regresar</a>
                </div>
            </header>

            <!-- Mensajes -->
            <?php if (!empty($viewSuccess)): ?>
                <div class="strip successbox">
                    <strong>✅ Registro completado.</strong>
                    <span>El estudiante fue agregado correctamente.</span>
                </div>
            <?php elseif (!empty($viewErrors)): ?>
                <div class="strip dangerbox">
                    <strong>⚠️ Error:</strong>
                    <span>Hubo un problema al guardar el estudiante. Verifica los datos.</span>
                </div>
            <?php endif; ?>

            <!-- Formulario principal -->
            <section class="card form-card">
                <header>Datos del estudiante</header>
                <div class="content">
                    <form method="post" class="form-grid">

                        <div class="form-group">
                            <label for="nombre">Nombre(s)</label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan Carlos"
                                value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="apellido_paterno">Apellido paterno</label>
                            <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder="Ej. Pérez"
                                value="<?= htmlspecialchars($_POST['apellido_paterno'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="apellido_materno">Apellido materno</label>
                            <input type="text" id="apellido_materno" name="apellido_materno" placeholder="Ej. López"
                                value="<?= htmlspecialchars($_POST['apellido_materno'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="matricula">Matrícula</label>
                            <input type="text" id="matricula" name="matricula" required maxlength="20"
                                placeholder="Ej. 20230145" value="<?= htmlspecialchars($_POST['matricula'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="carrera">Carrera</label>
                            <input type="text" id="carrera" name="carrera" placeholder="Ej. Ingeniería en Informática"
                                value="<?= htmlspecialchars($_POST['carrera'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="correo_institucional">Correo institucional</label>
                            <input type="email" id="correo_institucional" name="correo_institucional"
                                placeholder="Ej. juan.perez@universidad.mx"
                                value="<?= htmlspecialchars($_POST['correo_institucional'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 3312345678"
                                value="<?= htmlspecialchars($_POST['telefono'] ?? '') ?>">
                        </div>

                        <div class="form-group">
                            <label for="empresa_id">Empresa</label>
                            <select name="empresa_id" id="empresa_id" required>
                                <option value="">Selecciona una empresa...</option>
                                <?php foreach ($empresas as $empresa): ?>
                                    <option value="<?= htmlspecialchars((string) $empresa['id']) ?>"
                                        <?= (($_POST['empresa_id'] ?? '') == $empresa['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($empresa['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="convenio_id">Convenio</label>
                            <select name="convenio_id" id="convenio_id">
                                <option value="">Sin asignar</option>
                                <?php foreach ($convenios as $convenio): ?>
                                    <option value="<?= htmlspecialchars((string) $convenio['id']) ?>"
                                        <?= (($_POST['convenio_id'] ?? '') == $convenio['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($convenio['folio'] ?? 'Sin folio') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="estatus">Estatus</label>
                            <select name="estatus" id="estatus">
                                <option value="Activo" <?= (($_POST['estatus'] ?? '') === 'Activo') ? 'selected' : '' ?>>
                                    Activo</option>
                                <option value="Finalizado" <?= (($_POST['estatus'] ?? '') === 'Finalizado') ? 'selected' : '' ?>>Finalizado</option>
                                <option value="Inactivo" <?= (($_POST['estatus'] ?? '') === 'Inactivo') ? 'selected' : '' ?>>Inactivo</option>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="guardar" class="btn primary">💾 Guardar estudiante</button>
                            <a href="rp_estudiante_list.php" class="btn secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </section>

        </main>
    </div>

</body>

</html>