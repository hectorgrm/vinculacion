<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/GestEstudianteController.php';

use Serviciosocial\Controller\GestEstudianteController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$controller = new GestEstudianteController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: gestestudiante_list.php?error=invalid');
    exit;
}

$fatalError = '';
$errors = [];
$student = null;

try {
    $student = $controller->find($id);
    if ($student === null) {
        header('Location: gestestudiante_list.php?error=notfound');
        exit;
    }
} catch (\Throwable $throwable) {
    $fatalError = 'No fue posible cargar la información del estudiante: ' . $throwable->getMessage();
}

$plazas = [];

if ($fatalError === '') {
    try {
        $plazas = $controller->getPlazas();
    } catch (\Throwable $throwable) {
        $fatalError = 'No fue posible cargar el catálogo de plazas: ' . $throwable->getMessage();
    }
}

$formData = [
    'nombre'               => '',
    'matricula'            => '',
    'carrera'              => '',
    'semestre'             => '',
    'email'                => '',
    'telefono'             => '',
    'plaza_id'             => '',
    'dependencia_asignada' => '',
    'proyecto'             => '',
    'asesor_interno'       => '',
    'periodo_inicio'       => '',
    'periodo_fin'          => '',
    'horas_acumuladas'     => '',
    'horas_requeridas'     => '',
    'estado_servicio'      => 'pendiente',
    'documentos_entregados'=> '',
    'observaciones'        => '',
];

if ($fatalError === '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach (array_keys($formData) as $key) {
            $value = $_POST[$key] ?? '';
            if (!is_string($value)) {
                $formData[$key] = '';
                continue;
            }

            $trimmed = trim($value);

            if ($key === 'documentos_entregados' || $key === 'observaciones') {
                $formData[$key] = $trimmed;
            } else {
                $formData[$key] = $trimmed;
            }
        }

        try {
            $controller->updateServicioSocial($id, $_POST);
            header('Location: gestestudiante_list.php?updated=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $throwable) {
            $errors[] = 'No fue posible actualizar la información del estudiante: ' . $throwable->getMessage();
        }
    } else {
        $formData['nombre'] = (string)($student['nombre'] ?? '');
        $formData['matricula'] = (string)($student['matricula'] ?? '');
        $formData['carrera'] = (string)($student['carrera'] ?? '');
        $formData['semestre'] = $student['semestre'] !== null ? (string) $student['semestre'] : '';
        $formData['email'] = (string)($student['email'] ?? '');
        $formData['telefono'] = (string)($student['telefono'] ?? '');
        $formData['plaza_id'] = $student['plaza_id'] !== null ? (string) $student['plaza_id'] : '';
        $formData['dependencia_asignada'] = (string)($student['dependencia_asignada'] ?? '');
        $formData['proyecto'] = (string)($student['proyecto'] ?? '');
        $formData['asesor_interno'] = (string)($student['asesor_interno'] ?? '');
        $formData['periodo_inicio'] = substr((string)($student['periodo_inicio'] ?? ''), 0, 10);
        $formData['periodo_fin'] = substr((string)($student['periodo_fin'] ?? ''), 0, 10);
        $formData['horas_acumuladas'] = $student['horas_acumuladas'] !== null ? (string) $student['horas_acumuladas'] : '';
        $formData['horas_requeridas'] = $student['horas_requeridas'] !== null ? (string) $student['horas_requeridas'] : '';
        $formData['estado_servicio'] = strtolower((string)($student['estado_servicio'] ?? 'pendiente'));
        $formData['documentos_entregados'] = (string)($student['documentos_entregados'] ?? '');
        $formData['observaciones'] = (string)($student['observaciones'] ?? '');
    }
}

$estadoLabels = [
    'pendiente' => 'Pendiente',
    'en_curso'  => 'En curso',
    'concluido' => 'Concluido',
    'cancelado' => 'Cancelado',
];

$studentName = (string)($student['nombre'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar expediente · <?php echo htmlspecialchars($studentName, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="../../assets/css/gestestudiante/estudianteviewstyles.css">
    <style>
        body {
            background-color: #f4f6fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #1f2937;
            margin: 0;
        }

        a {
            text-decoration: none;
        }

        header {
            background: linear-gradient(90deg, #234ce3, #162c6d);
            color: #fff;
            padding: 32px 48px;
        }

        header h1 {
            margin: 0 0 12px;
            font-size: 28px;
        }

        header p {
            margin: 0;
            font-size: 15px;
            opacity: 0.9;
        }

        .breadcrumb {
            margin-top: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: rgba(255, 255, 255, 0.85);
        }

        main {
            max-width: 1080px;
            margin: -36px auto 64px;
            padding: 0 24px;
        }

        .card {
            background: #fff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 16px 30px rgba(19, 45, 94, 0.1);
        }

        .card + .card {
            margin-top: 24px;
        }

        .card header {
            background: none;
            color: inherit;
            padding: 0;
        }

        .card header h2 {
            margin: 0 0 8px;
            font-size: 22px;
        }

        .card header p {
            margin: 0;
            color: #6b7280;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-top: 24px;
        }

        .profile-grid .field label {
            display: block;
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: .05em;
        }

        .profile-grid .field p {
            margin: 0;
            font-weight: 600;
            color: #111827;
        }

        form {
            margin-top: 24px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        .form-group label .hint {
            display: block;
            font-weight: normal;
            color: #9ca3af;
            font-size: 12px;
        }

        .form-group select,
        .form-group input,
        .form-group textarea {
            border-radius: 10px;
            border: 1px solid #cbd5f5;
            padding: 12px;
            font-size: 15px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-group select:focus,
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #234ce3;
            box-shadow: 0 0 0 3px rgba(35, 76, 227, 0.15);
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            margin-top: 32px;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-primary,
        .btn-secondary {
            padding: 12px 24px;
            border-radius: 999px;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(90deg, #234ce3, #162c6d);
            color: #fff;
            box-shadow: 0 8px 18px rgba(35, 76, 227, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #111827;
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
        }

        .alert {
            padding: 16px 18px;
            border-radius: 12px;
            margin-bottom: 18px;
            font-weight: 500;
        }

        .alert.error {
            background: #fee2e2;
            color: #b91c1c;
        }

        .top-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .top-actions .btn-back {
            color: #1f2937;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .top-actions .btn-back svg {
            width: 18px;
            height: 18px;
        }
    </style>
</head>
<body>
<header>
    <h1>Editar expediente del estudiante</h1>
    <p>Actualiza los datos personales, el seguimiento del servicio social y la documentación entregada.</p>
    <nav class="breadcrumb">
        <a href="../../index.php">Inicio</a>
        <span>›</span>
        <a href="gestestudiante_list.php">Gestión de estudiantes</a>
        <span>›</span>
        <span>Editar</span>
    </nav>
</header>

<main>
    <?php if ($fatalError !== ''): ?>
        <div class="alert error" role="alert"><?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php else: ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

            <div class="top-actions">
                <a href="gestestudiante_list.php" class="btn-back">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 5L7.5 10L12.5 15" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Regresar
                </a>
                <span>Última actualización registrada: <?php echo htmlspecialchars($student['periodo_inicio'] !== null ? substr((string)$student['periodo_inicio'], 0, 10) : 'Sin periodo asignado', ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert error" role="alert">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="card">
                <header>
                    <h2>Datos personales</h2>
                    <p>Corrige el nombre, contacto y datos escolares del estudiante.</p>
                </header>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nombre">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($formData['nombre'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="matricula">Matrícula</label>
                        <input type="text" id="matricula" name="matricula" required value="<?php echo htmlspecialchars($formData['matricula'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="carrera">Carrera</label>
                        <input type="text" id="carrera" name="carrera" value="<?php echo htmlspecialchars($formData['carrera'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="semestre">Semestre</label>
                        <input type="number" id="semestre" name="semestre" min="1" max="15" step="1" value="<?php echo htmlspecialchars($formData['semestre'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($formData['email'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="form-group">
                        <label for="telefono">Teléfono</label>
                        <input type="text" id="telefono" name="telefono" value="<?php echo htmlspecialchars($formData['telefono'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="Ej. 6641234567">
                    </div>
                </div>
            </div>

            <div class="card">
                <header>
                    <h2>Servicio social</h2>
                    <p>Actualiza la asignación de plaza, periodo, horas y estado del servicio social.</p>
                </header>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="plaza_id">Plaza asignada
                            <span class="hint">Selecciona la plaza si el estudiante participa en una vacante oficial.</span>
                        </label>
                        <select id="plaza_id" name="plaza_id">
                            <option value="">Sin plaza asignada</option>
                            <?php foreach ($plazas as $plaza): ?>
                                <?php $selected = $formData['plaza_id'] !== '' && (int)$formData['plaza_id'] === (int) $plaza['id']; ?>
                                <option value="<?php echo (int) $plaza['id']; ?>" <?php echo $selected ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars((string) $plaza['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="dependencia_asignada">Dependencia asignada</label>
                        <input type="text" id="dependencia_asignada" name="dependencia_asignada" value="<?php echo htmlspecialchars($formData['dependencia_asignada'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="proyecto">Proyecto</label>
                        <input type="text" id="proyecto" name="proyecto" value="<?php echo htmlspecialchars($formData['proyecto'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="asesor_interno">Asesor interno</label>
                        <input type="text" id="asesor_interno" name="asesor_interno" value="<?php echo htmlspecialchars($formData['asesor_interno'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="periodo_inicio">Inicio del periodo</label>
                        <input type="date" id="periodo_inicio" name="periodo_inicio" value="<?php echo htmlspecialchars($formData['periodo_inicio'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="periodo_fin">Fin del periodo</label>
                        <input type="date" id="periodo_fin" name="periodo_fin" value="<?php echo htmlspecialchars($formData['periodo_fin'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>

                    <div class="form-group">
                        <label for="horas_acumuladas">Horas acumuladas</label>
                        <input type="number" id="horas_acumuladas" name="horas_acumuladas" min="0" step="1" value="<?php echo htmlspecialchars($formData['horas_acumuladas'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="0">
                    </div>

                    <div class="form-group">
                        <label for="horas_requeridas">Horas requeridas</label>
                        <input type="number" id="horas_requeridas" name="horas_requeridas" min="0" step="1" value="<?php echo htmlspecialchars($formData['horas_requeridas'], ENT_QUOTES, 'UTF-8'); ?>" placeholder="480">
                    </div>

                    <div class="form-group">
                        <label for="estado_servicio">Estado del servicio social</label>
                        <select id="estado_servicio" name="estado_servicio" required>
                            <?php foreach ($estadoLabels as $key => $label): ?>
                                <option value="<?php echo htmlspecialchars($key, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $formData['estado_servicio'] === $key ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card">
                <header>
                    <h2>Documentación y observaciones</h2>
                    <p>Registra los documentos recibidos y cualquier seguimiento pendiente.</p>
                </header>

                <div class="form-grid">
                    <div class="form-group full">
                        <label for="documentos_entregados">Documentos entregados</label>
                        <textarea id="documentos_entregados" name="documentos_entregados" placeholder="Listado de documentos entregados"><?php echo htmlspecialchars($formData['documentos_entregados'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                    <div class="form-group full">
                        <label for="observaciones">Observaciones</label>
                        <textarea id="observaciones" name="observaciones" placeholder="Notas adicionales, compromisos o incidencias"><?php echo htmlspecialchars($formData['observaciones'], ENT_QUOTES, 'UTF-8'); ?></textarea>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="gestestudiante_list.php" class="btn-secondary">Cancelar</a>
                <button type="submit" class="btn-primary">Guardar cambios</button>
            </div>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
