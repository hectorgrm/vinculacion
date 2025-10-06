<?php
declare(strict_types=1);

require_once __DIR__ . '/../../../config/session.php';
require_once __DIR__ . '/../../controller/PlazaController.php';

use Serviciosocial\Controller\PlazaController;

$user = $_SESSION['user'] ?? null;
$allowedRoles = ['ss_admin'];

if (!is_array($user) || !in_array(strtolower((string)($user['role'] ?? '')), $allowedRoles, true)) {
    header('Location: ../../../common/login.php?module=serviciosocial&error=unauthorized');
    exit;
}

$controller = new PlazaController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
}

if ($id <= 0) {
    header('Location: plaza_list.php?error=invalid');
    exit;
}

$errors = [];
$fatalError = '';
$plaza = null;

try {
    $plaza = $controller->findById($id);
    if ($plaza === null) {
        header('Location: plaza_list.php?error=notfound');
        exit;
    }
} catch (\Throwable $throwable) {
    $fatalError = 'No fue posible cargar la información de la plaza: ' . $throwable->getMessage();
}

$empresas = [];
$convenios = [];

if ($fatalError === '') {
    try {
        $empresas = $controller->getEmpresas();
        $convenios = $controller->getConvenios();
    } catch (\Throwable $catalogException) {
        $fatalError = 'No fue posible cargar los catálogos de apoyo: ' . $catalogException->getMessage();
    }
}

$formData = [
    'plaza_nombre'       => '',
    'dependencia_id'     => '',
    'programa_id'        => '',
    'modalidad'          => '',
    'horario_texto'      => '',
    'cupo'               => '',
    'periodo_inicio'     => '',
    'periodo_fin'        => '',
    'actividades'        => '',
    'requisitos'         => '',
    'responsable_nombre' => '',
    'responsable_puesto' => '',
    'responsable_email'  => '',
    'responsable_tel'    => '',
    'direccion'          => '',
    'ubicacion'          => '',
    'estado_plaza'       => '',
    'observaciones'      => '',
];

if ($fatalError === '') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($formData as $key => $defaultValue) {
            $formData[$key] = trim((string)($_POST[$key] ?? ''));
        }

        try {
            $controller->update($id, $_POST);
            header('Location: plaza_list.php?updated=1');
            exit;
        } catch (\InvalidArgumentException $invalidArgumentException) {
            $errors[] = $invalidArgumentException->getMessage();
        } catch (\Throwable $throwable) {
            $errors[] = 'No fue posible actualizar la plaza: ' . $throwable->getMessage();
        }
    } else {
        $formData['plaza_nombre'] = (string)($plaza['nombre'] ?? '');
        $formData['dependencia_id'] = (string)($plaza['ss_empresa_id'] ?? '');
        $formData['programa_id'] = (string)($plaza['ss_convenio_id'] ?? '');
        $formData['modalidad'] = strtolower((string)($plaza['modalidad'] ?? ''));
        $formData['cupo'] = $plaza['cupo'] !== null ? (string) $plaza['cupo'] : '';
        $formData['periodo_inicio'] = substr((string)($plaza['periodo_inicio'] ?? ''), 0, 10);
        $formData['periodo_fin'] = substr((string)($plaza['periodo_fin'] ?? ''), 0, 10);
        $formData['actividades'] = (string)($plaza['actividades'] ?? '');
        $formData['requisitos'] = (string)($plaza['requisitos'] ?? '');
        $formData['responsable_nombre'] = (string)($plaza['responsable_nombre'] ?? '');
        $formData['responsable_puesto'] = (string)($plaza['responsable_puesto'] ?? '');
        $formData['responsable_email'] = (string)($plaza['responsable_email'] ?? '');
        $formData['responsable_tel'] = (string)($plaza['responsable_tel'] ?? '');
        $formData['direccion'] = (string)($plaza['direccion'] ?? '');
        $formData['ubicacion'] = (string)($plaza['ubicacion'] ?? '');
        $formData['estado_plaza'] = strtolower((string)($plaza['estado'] ?? ''));

        $observacionesOriginal = trim((string)($plaza['observaciones'] ?? ''));
        $horarioLabel = 'Horario: ';
        $horarioTexto = '';
        $observacionesTexto = $observacionesOriginal;

        if ($observacionesOriginal !== '') {
            $lines = preg_split("/\r\n|\n|\r/", $observacionesOriginal) ?: [];
            if (!empty($lines)) {
                $firstLine = trim((string) $lines[0]);
                if (strpos($firstLine, $horarioLabel) === 0) {
                    $horarioTexto = trim(substr($firstLine, strlen($horarioLabel)));
                    array_shift($lines);
                    $observacionesTexto = trim(implode("\n", $lines));
                }
            }
        }

        $formData['horario_texto'] = $horarioTexto;
        $formData['observaciones'] = $observacionesTexto;
    }
}

$currentPlazaName = $formData['plaza_nombre'] !== ''
    ? $formData['plaza_nombre']
    : (string)($plaza['nombre'] ?? '');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Servicio Social - Editar Plaza</title>
    <link rel="stylesheet" href="../../assets/css/plaza/plazaeditstyles.css">
</head>
<body>

  <header>
    <div class="title">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        <path d="M3 7h18M5 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7" stroke="#fff" stroke-width="1.7" stroke-linecap="round"/>
        <rect x="7" y="3" width="10" height="4" rx="1.3" stroke="#fff" stroke-width="1.7"/>
      </svg>
      <h1>Servicio Social · Editar Plaza</h1>
    </div>
    <nav class="breadcrumb">
      <a href="../../dashboard.php">Inicio</a>
      <span class="sep">›</span>
      <a href="../gestionplaza/plaza_list.php">Gestión de Plazas</a>
      <span class="sep">›</span>
      <span><?php echo htmlspecialchars($currentPlazaName, ENT_QUOTES, 'UTF-8'); ?></span>
    </nav>
  </header>

  <main>
    <?php if ($fatalError !== ''): ?>
      <div class="alert error" role="alert"><?php echo htmlspecialchars($fatalError, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php else: ?>
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
          <h2>Actualizar Plaza</h2>
          <p>Modifica los datos necesarios y guarda los cambios para mantener la plaza al día.</p>
        </header>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8'); ?>" autocomplete="off">
          <input type="hidden" name="id" value="<?php echo (int) $id; ?>">

          <div class="grid cols-2">
            <div class="field">
              <label for="plaza_nombre" class="required">Nombre de la plaza</label>
              <input id="plaza_nombre" name="plaza_nombre" type="text" required placeholder="Ej. Auxiliar de Soporte TI" value="<?php echo htmlspecialchars($formData['plaza_nombre'], ENT_QUOTES, 'UTF-8'); ?>">
              <div class="hint">Nombre corto y claro de la vacante/plaza.</div>
            </div>

            <div class="field">
              <label for="dependencia_id" class="required">Dependencia / Empresa</label>
              <select id="dependencia_id" name="dependencia_id" required>
                <option value="">Selecciona…</option>
                <?php foreach ($empresas as $empresa): ?>
                  <option value="<?php echo (int) $empresa['id']; ?>" <?php echo $formData['dependencia_id'] === (string) $empresa['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars((string) $empresa['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
              <div class="hint">Entidad donde se realizará el servicio.</div>
            </div>

            <div class="field">
              <label for="programa_id">Programa</label>
              <select id="programa_id" name="programa_id">
                <option value="">Selecciona…</option>
                <?php foreach ($convenios as $convenio): ?>
                  <option value="<?php echo (int) $convenio['id']; ?>" <?php echo $formData['programa_id'] === (string) $convenio['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars((string) $convenio['nombre'], ENT_QUOTES, 'UTF-8'); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>

            <div class="field">
              <label for="modalidad" class="required">Modalidad</label>
              <select id="modalidad" name="modalidad" required>
                <option value="">Selecciona…</option>
                <option value="presencial" <?php echo $formData['modalidad'] === 'presencial' ? 'selected' : ''; ?>>Presencial</option>
                <option value="hibrida" <?php echo $formData['modalidad'] === 'hibrida' ? 'selected' : ''; ?>>Híbrida</option>
                <option value="remota" <?php echo $formData['modalidad'] === 'remota' ? 'selected' : ''; ?>>Remota</option>
              </select>
            </div>

            <div class="field">
              <label for="horario_texto">Horario</label>
              <input id="horario_texto" name="horario_texto" type="text" placeholder="Ej. L–V 9:00–14:00" value="<?php echo htmlspecialchars($formData['horario_texto'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="field">
              <label for="cupo" class="required">Cupo</label>
              <input id="cupo" name="cupo" type="number" min="1" step="1" required placeholder="Ej. 3" value="<?php echo htmlspecialchars($formData['cupo'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="field">
              <label for="periodo_inicio" class="required">Periodo — Inicio</label>
              <input id="periodo_inicio" name="periodo_inicio" type="date" required value="<?php echo htmlspecialchars($formData['periodo_inicio'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>

            <div class="field">
              <label for="periodo_fin" class="required">Periodo — Fin</label>
              <input id="periodo_fin" name="periodo_fin" type="date" required value="<?php echo htmlspecialchars($formData['periodo_fin'], ENT_QUOTES, 'UTF-8'); ?>">
            </div>
          </div>

          <div class="section">
            <h3>Descripción y requisitos</h3>
            <div class="grid cols-2">
              <div class="field">
                <label for="actividades" class="required">Actividades a realizar</label>
                <textarea id="actividades" name="actividades" required placeholder="Describe las tareas principales…"><?php echo htmlspecialchars($formData['actividades'], ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
              <div class="field">
                <label for="requisitos" class="required">Requisitos</label>
                <textarea id="requisitos" name="requisitos" required placeholder="Conocimientos, habilidades, documentos…"><?php echo htmlspecialchars($formData['requisitos'], ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>
          </div>

          <div class="section">
            <h3>Contacto</h3>
            <div class="grid cols-2">
              <div class="field">
                <label for="responsable_nombre" class="required">Responsable</label>
                <input id="responsable_nombre" name="responsable_nombre" type="text" required placeholder="Nombre completo" value="<?php echo htmlspecialchars($formData['responsable_nombre'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="field">
                <label for="responsable_puesto">Puesto</label>
                <input id="responsable_puesto" name="responsable_puesto" type="text" placeholder="Ej. Jefe de Sistemas" value="<?php echo htmlspecialchars($formData['responsable_puesto'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="field">
                <label for="responsable_email" class="required">Correo</label>
                <input id="responsable_email" name="responsable_email" type="email" required placeholder="correo@empresa.com" value="<?php echo htmlspecialchars($formData['responsable_email'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="field">
                <label for="responsable_tel">Teléfono</label>
                <input id="responsable_tel" name="responsable_tel" type="tel" placeholder="Ej. 55 1234 5678" value="<?php echo htmlspecialchars($formData['responsable_tel'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
            </div>
          </div>

          <div class="section">
            <h3>Ubicación y estado</h3>
            <div class="grid cols-2">
              <div class="field">
                <label for="direccion">Dirección</label>
                <input id="direccion" name="direccion" type="text" placeholder="Calle y número" value="<?php echo htmlspecialchars($formData['direccion'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="field">
                <label for="ubicacion">Ubicación</label>
                <input id="ubicacion" name="ubicacion" type="text" placeholder="Dirección o ciudad" value="<?php echo htmlspecialchars($formData['ubicacion'], ENT_QUOTES, 'UTF-8'); ?>">
              </div>
              <div class="field">
                <label for="estado_plaza" class="required">Estado</label>
                <select id="estado_plaza" name="estado_plaza" required>
                  <option value="">Selecciona…</option>
                  <option value="activa" <?php echo $formData['estado_plaza'] === 'activa' ? 'selected' : ''; ?>>Activa</option>
                  <option value="inactiva" <?php echo $formData['estado_plaza'] === 'inactiva' ? 'selected' : ''; ?>>Inactiva</option>
                </select>
              </div>
              <div class="field" style="grid-column:1/-1">
                <label for="observaciones">Observaciones</label>
                <textarea id="observaciones" name="observaciones" placeholder="Notas internas, consideraciones, etc."><?php echo htmlspecialchars($formData['observaciones'], ENT_QUOTES, 'UTF-8'); ?></textarea>
              </div>
            </div>
          </div>

          <div class="actions">
            <span class="note">Revisa los campos marcados con <strong style="color:var(--danger)">*</strong> antes de guardar.</span>
            <a class="btn ghost" href="./plaza_list.php">Cancelar</a>
            <button type="submit" class="btn primary">Guardar cambios</button>
          </div>
        </form>
      </div>
    <?php endif; ?>
  </main>

</body>
</html>
