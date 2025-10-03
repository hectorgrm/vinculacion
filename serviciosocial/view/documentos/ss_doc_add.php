<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosController;

require_once __DIR__ . '/../../controller/DocumentosController.php';

/**
 * @return string
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$periodos = [];
$tipos = [];
$studentCatalog = [];
$errorMessage = null;
$successMessage = null;

$selectedEstudiante = isset($_POST['estudiante']) ? (string) $_POST['estudiante'] : '';
$selectedPeriodo = isset($_POST['periodo']) ? (string) $_POST['periodo'] : '';
$selectedTipo = isset($_POST['tipo']) ? (string) $_POST['tipo'] : '';
$observacionesInput = isset($_POST['observaciones']) ? (string) $_POST['observaciones'] : '';

$controller = null;

try {
    $controller = new DocumentosController();
    $periodos = $controller->getPeriodCatalog();
    $tipos = $controller->getDocumentTypeCatalog();

    foreach ($periodos as $periodo) {
        $estudiante = $periodo['estudiante'] ?? [];
        $estudianteId = (int) ($estudiante['id'] ?? 0);

        if ($estudianteId > 0 && !array_key_exists($estudianteId, $studentCatalog)) {
            $studentCatalog[$estudianteId] = [
                'nombre'    => $estudiante['nombre'] ?? '',
                'matricula' => $estudiante['matricula'] ?? '',
            ];
        }
    }
} catch (\Throwable $exception) {
    $errorMessage = 'Ocurrió un error al preparar el formulario: ' . $exception->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorMessage === null && $controller instanceof DocumentosController) {
    $estudianteId = filter_input(INPUT_POST, 'estudiante', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($estudianteId === false || $estudianteId === null) {
        $errorMessage = 'Selecciona un estudiante válido.';
    } elseif (!array_key_exists((int) $estudianteId, $studentCatalog)) {
        $errorMessage = 'El estudiante seleccionado no existe.';
    }

    $periodoId = filter_input(INPUT_POST, 'periodo', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($errorMessage === null && ($periodoId === false || $periodoId === null)) {
        $errorMessage = 'Selecciona un periodo válido.';
    }

    $tipoId = filter_input(INPUT_POST, 'tipo', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($errorMessage === null && ($tipoId === false || $tipoId === null)) {
        $errorMessage = 'Selecciona un tipo de documento válido.';
    }

    $observacionesLimpias = trim($observacionesInput);

    $periodoSeleccionado = null;
    if ($errorMessage === null) {
        foreach ($periodos as $periodo) {
            if ((int) ($periodo['id'] ?? 0) === (int) $periodoId) {
                $periodoSeleccionado = $periodo;
                break;
            }
        }

        if ($periodoSeleccionado === null) {
            $errorMessage = 'El periodo seleccionado no existe.';
        }
    }

    if ($errorMessage === null) {
        $periodoEstudianteId = (int) ($periodoSeleccionado['estudiante']['id'] ?? 0);

        if ($periodoEstudianteId !== (int) $estudianteId) {
            $errorMessage = 'El periodo seleccionado no corresponde al estudiante elegido.';
        }
    }

    $rutaRelativa = null;
    $rutaAbsoluta = null;

    if ($errorMessage === null) {
        if (!isset($_FILES['archivo']) || !is_array($_FILES['archivo'])) {
            $errorMessage = 'Debes seleccionar un archivo PDF.';
        } else {
            $fileError = (int) $_FILES['archivo']['error'];

            if ($fileError !== UPLOAD_ERR_OK) {
                if ($fileError === UPLOAD_ERR_NO_FILE) {
                    $errorMessage = 'Debes seleccionar un archivo PDF.';
                } else {
                    $errorMessage = 'Ocurrió un problema al subir el archivo.';
                }
            } else {
                $tmpName = (string) $_FILES['archivo']['tmp_name'];
                $originalName = (string) $_FILES['archivo']['name'];
                $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

                if ($extension !== 'pdf') {
                    $errorMessage = 'Solo se permiten archivos en formato PDF.';
                } else {
                    $uploadDir = __DIR__ . '/../../uploads/documentos';

                    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                        $errorMessage = 'No se pudo preparar el directorio para subir archivos.';
                    } else {
                        $newFileName = sprintf('doc_%d_%d_%d.pdf', (int) $periodoId, (int) $tipoId, time());
                        $destinationPath = $uploadDir . '/' . $newFileName;

                        if (!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $destinationPath)) {
                            $errorMessage = 'No se pudo guardar el archivo subido.';
                        } else {
                            $rutaRelativa = 'uploads/documentos/' . $newFileName;
                            $rutaAbsoluta = $destinationPath;
                        }
                    }
                }
            }
        }
    }

    if ($errorMessage === null) {
        try {
            $controller->create([
                'estudiante_id' => (int) $estudianteId,
                'periodo_id'    => (int) $periodoId,
                'tipo_id'       => (int) $tipoId,
                'ruta'          => $rutaRelativa,
                'recibido'      => true,
                'observacion'   => $observacionesLimpias,
            ]);

            $successMessage = 'El documento se registró correctamente.';
            $selectedEstudiante = '';
            $selectedPeriodo = '';
            $selectedTipo = '';
            $observacionesInput = '';
        } catch (\Throwable $exception) {
            $errorMessage = 'No se pudo registrar el documento: ' . $exception->getMessage();

            if ($rutaAbsoluta !== null && is_file($rutaAbsoluta)) {
                unlink($rutaAbsoluta);
            }
        }
    } elseif ($rutaAbsoluta !== null && is_file($rutaAbsoluta)) {
        unlink($rutaAbsoluta);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Subir Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Subir Documento de Servicio Social</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Nuevo</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="card" style="margin-top:20px;">
      <p style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></p>
    </section>
<?php endif; ?>

<?php if ($successMessage !== null): ?>
    <section class="card" style="margin-top:20px; background-color:#e6f4ea; color:#1e4620;">
      <p style="font-weight:bold;"><?= e($successMessage) ?></p>
    </section>
<?php endif; ?>

    <section class="card">
      <h2>Registrar Nuevo Documento</h2>
      <p>Completa los siguientes campos para subir un documento relacionado con un estudiante y su servicio social.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🧑‍🎓 Estudiante -->
          <div class="field">
            <label for="estudiante" class="required">Estudiante</label>
            <select id="estudiante" name="estudiante" required>
              <option value="">-- Seleccionar estudiante --</option>
<?php foreach ($studentCatalog as $studentId => $studentData): ?>
<?php
    $value = (string) $studentId;
    $isSelected = $selectedEstudiante === $value ? ' selected' : '';
    $label = $studentData['nombre'] ?? 'Estudiante sin nombre';
    $matricula = $studentData['matricula'] ?? '';

    if ($matricula !== '') {
        $label .= ' (' . $matricula . ')';
    }
?>
              <option value="<?= e($value) ?>"<?= $isSelected ?>><?= e($label) ?></option>
<?php endforeach; ?>
            </select>
<?php if ($studentCatalog === []): ?>
            <div class="hint" style="color:#b00020;">No hay estudiantes con periodos registrados disponibles.</div>
<?php endif; ?>
          </div>

          <!-- 📆 Periodo -->
          <div class="field">
            <label for="periodo" class="required">Periodo</label>
            <select id="periodo" name="periodo" required>
              <option value="">-- Seleccionar periodo --</option>
<?php foreach ($periodos as $periodo): ?>
<?php
    $value = (string) ($periodo['id'] ?? '');
    $isSelected = $selectedPeriodo === $value ? ' selected' : '';
    $estudianteNombre = $periodo['estudiante']['nombre'] ?? 'Estudiante sin nombre';
    $matricula = $periodo['estudiante']['matricula'] ?? '';
    $numero = $periodo['numero'] ?? null;
    $estatus = $periodo['estatus'] ?? '';

    $labelParts = [$estudianteNombre];

    if ($matricula !== '') {
        $labelParts[] = '(' . $matricula . ')';
    }

    if ($numero !== null) {
        $labelParts[] = 'Periodo ' . $numero;
    }

    if ($estatus !== '') {
        $labelParts[] = ucfirst((string) $estatus);
    }

    $label = implode(' · ', array_filter($labelParts, static function ($part) {
        return $part !== '' && $part !== null;
    }));
?>
              <option value="<?= e($value) ?>"<?= $isSelected ?>><?= e($label) ?></option>
<?php endforeach; ?>
            </select>
<?php if ($periodos === []): ?>
            <div class="hint" style="color:#b00020;">No hay periodos registrados. Registra periodos antes de subir documentos.</div>
<?php endif; ?>
          </div>

          <!-- 📑 Tipo de Documento -->
          <div class="field">
            <label for="tipo" class="required">Tipo de Documento</label>
            <select id="tipo" name="tipo" required>
              <option value="">-- Seleccionar tipo --</option>
<?php foreach ($tipos as $tipo): ?>
<?php
    $value = (string) ($tipo['id'] ?? '');
    $isSelected = $selectedTipo === $value ? ' selected' : '';
    $label = $tipo['nombre'] ?? 'Tipo sin nombre';
?>
              <option value="<?= e($value) ?>"<?= $isSelected ?>><?= e($label) ?></option>
<?php endforeach; ?>
            </select>
<?php if ($tipos === []): ?>
            <div class="hint" style="color:#b00020;">No hay tipos de documento disponibles.</div>
<?php endif; ?>
          </div>

          <!-- 📎 Archivo PDF -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo" class="required">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <div class="hint">Solo se permiten archivos PDF (máx. 5 MB).</div>
          </div>

          <!-- 📝 Observaciones -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Notas o comentarios sobre este documento (opcional)..."><?= e($observacionesInput) ?></textarea>
          </div>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">📤 Subir Documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
