<?php
declare(strict_types=1);

/** @var array{
 *     formData: array<string, string>,
 *     empresas: array<int, array<string, string>>,
 *     convenios: array<int, array<string, string>>,
 *     errors: array<int, string>,
 *     success: bool,
 *     successMessage: ?string,
 *     controllerError: ?string,
 *     estatusOptions: array<int, string>
 * } $handlerResult
 */
$handlerResult = require __DIR__ . '/../../handler/estudiante/estudiante_add_handler.php';

$formData = $handlerResult['formData'];
$empresas = $handlerResult['empresas'];
$convenios = $handlerResult['convenios'];
$viewErrors = $handlerResult['errors'];
$viewSuccess = $handlerResult['success'];
$successMessage = $handlerResult['successMessage'];
$controllerError = $handlerResult['controllerError'];
$estatusOptions = $handlerResult['estatusOptions'];
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo Estudiante a Residencia Profesional</title>

    <link rel="stylesheet" href="../../assets/css/modules/estudiante/estudianteadd.css" />
</head>

<body>
    <div class="app">
        <?php include __DIR__ . '/../../layout/sidebar.php'; ?>

        <main class="main">
            <header class="topbar">
                <div class="page-titles">
                    <p class="eyebrow">Estudiantes</p>
                    <h2>Nuevo Estudiante</h2>
                    <p class="lead">Registra un estudiante vinculado a una empresa y, opcionalmente, a un convenio.</p>
                </div>
                <div class="actions">
                    <a href="estudiante_list.php" class="btn secondary">&larr; Regresar</a>
                </div>
            </header>

            <?php if ($controllerError !== null || ($viewSuccess && $successMessage !== null) || $viewErrors !== []): ?>
                <div class="message-stack">
                    <?php if ($controllerError !== null): ?>
                        <div class="alert error" role="alert">
                            <strong>Error:</strong> <span><?= htmlspecialchars($controllerError) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($viewSuccess && $successMessage !== null): ?>
                        <div class="alert success" role="status">
                            <strong>Registro completado.</strong>
                            <span><?= htmlspecialchars($successMessage) ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if ($viewErrors !== []): ?>
                        <div class="alert error" role="alert">
                            <p style="margin:0 0 8px 0; font-weight:700;">Corrige los siguientes errores:</p>
                            <ul>
                                <?php foreach ($viewErrors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <section class="card form-card">
                <header>Datos del estudiante</header>
                <div class="content">
                    <form method="post" class="form-grid">

                        <div class="form-group">
                            <label for="nombre" class="required">Nombre(s)</label>
                            <input type="text" id="nombre" name="nombre" required placeholder="Ej. Juan Carlos"
                                value="<?= htmlspecialchars($formData['nombre']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="apellido_paterno">Apellido paterno</label>
                            <input type="text" id="apellido_paterno" name="apellido_paterno" placeholder="Ej. Pérez"
                                value="<?= htmlspecialchars($formData['apellido_paterno']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="apellido_materno">Apellido materno</label>
                            <input type="text" id="apellido_materno" name="apellido_materno" placeholder="Ej. López"
                                value="<?= htmlspecialchars($formData['apellido_materno']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="matricula" class="required">Matrícula</label>
                            <input type="text" id="matricula" name="matricula" required maxlength="20"
                                placeholder="Ej. 20230145" value="<?= htmlspecialchars($formData['matricula']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="carrera">Carrera</label>
                            <input type="text" id="carrera" name="carrera" placeholder="Ej. Ingeniería en Informática"
                                value="<?= htmlspecialchars($formData['carrera']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="correo_institucional">Correo institucional</label>
                            <input type="email" id="correo_institucional" name="correo_institucional"
                                placeholder="Ej. juan.perez@universidad.mx"
                                value="<?= htmlspecialchars($formData['correo_institucional']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" maxlength="20" placeholder="Ej. 3312345678"
                                value="<?= htmlspecialchars($formData['telefono']) ?>">
                        </div>

                        <div class="form-group">
                            <label for="empresa_id" class="required">Empresa</label>
                            <select name="empresa_id" id="empresa_id" required <?= $empresas === [] ? 'disabled' : ''; ?>>
                                <option value="">Selecciona una empresa...</option>
                                <?php foreach ($empresas as $empresa): ?>
                                    <option value="<?= htmlspecialchars($empresa['id']) ?>"
                                        <?= ($formData['empresa_id'] === $empresa['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($empresa['nombre']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="convenio_id">Convenio</label>
                            <select name="convenio_id" id="convenio_id" <?= $convenios === [] ? 'disabled' : ''; ?>>
                                <option value="">Sin asignar</option>
                                <?php foreach ($convenios as $convenio): ?>
                                    <option value="<?= htmlspecialchars($convenio['id']) ?>"
                                        <?= ($formData['convenio_id'] === $convenio['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($convenio['folio'] !== '' ? $convenio['folio'] : 'Sin folio') ?>
                                        <?php if ($convenio['empresa_nombre'] !== ''): ?>
                                            — <?= htmlspecialchars($convenio['empresa_nombre']) ?>
                                        <?php endif; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="estatus">Estatus</label>
                            <select name="estatus" id="estatus">
                                <?php foreach ($estatusOptions as $option): ?>
                                    <option value="<?= htmlspecialchars($option) ?>"
                                        <?= ($formData['estatus'] === $option) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($option) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-actions">
                            <button type="submit" name="guardar" class="btn primary" <?= $empresas === [] ? 'disabled' : ''; ?>>Guardar estudiante</button>
                            <a href="estudiante_list.php" class="btn secondary">Cancelar</a>
                        </div>

                    </form>
                </div>
            </section>

        </main>
    </div>

</body>

</html>
