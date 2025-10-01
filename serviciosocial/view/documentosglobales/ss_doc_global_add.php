<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosGlobalController;

require_once __DIR__ . '/../../controller/DocumentosGlobalController.php';

/**
 * @return string
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$controller = null;
$tipos = [];
$allowedStatuses = ['activo', 'inactivo'];
$errorMessage = null;
$successMessage = null;

$selectedTipo = isset($_POST['tipo_id']) ? (string) $_POST['tipo_id'] : '';
$nombreInput = isset($_POST['nombre']) ? (string) $_POST['nombre'] : '';
$descripcionInput = isset($_POST['descripcion']) ? (string) $_POST['descripcion'] : '';
$estatusInput = isset($_POST['estatus']) ? (string) $_POST['estatus'] : 'activo';

try {
    $controller = new DocumentosGlobalController();
    $tipos = $controller->getDocumentTypeCatalog();
    $allowedStatuses = $controller->getAllowedStatuses();
} catch (Throwable $exception) {
    $errorMessage = 'Ocurrió un error al preparar el formulario: ' . $exception->getMessage();
}

if (!in_array(strtolower($estatusInput), array_map('strtolower', $allowedStatuses), true)) {
    $estatusInput = 'activo';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorMessage === null && $controller instanceof DocumentosGlobalController) {
    $tipoId = filter_input(INPUT_POST, 'tipo_id', FILTER_VALIDATE_INT, [
        'options' => ['min_range' => 1],
    ]);

    if ($tipoId === false || $tipoId === null) {
        $errorMessage = 'Selecciona un tipo de documento válido.';
    } elseif ($controller->findDocumentType((int) $tipoId) === null) {
        $errorMessage = 'El tipo de documento seleccionado no existe.';
    }

    $nombreLimpio = trim($nombreInput);
    if ($errorMessage === null && $nombreLimpio === '') {
        $errorMessage = 'El nombre del documento es obligatorio.';
    }

    $descripcionLimpia = trim($descripcionInput);
    if ($descripcionLimpia === '') {
        $descripcionLimpia = null;
    }

    $estatusLimpio = strtolower(trim($estatusInput));
    if (!in_array($estatusLimpio, array_map('strtolower', $allowedStatuses), true)) {
        $estatusLimpio = 'activo';
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
                    $uploadDir = __DIR__ . '/../../uploads/documentosglobales';

                    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                        $errorMessage = 'No se pudo preparar el directorio para subir archivos.';
                    } else {
                        $uniqueId = str_replace('.', '_', uniqid('doc_global_' . (int) $tipoId . '_', true));
                        $newFileName = $uniqueId . '.pdf';
                        $destinationPath = $uploadDir . '/' . $newFileName;

                        if (!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $destinationPath)) {
                            $errorMessage = 'No se pudo guardar el archivo subido.';
                        } else {
                            $rutaRelativa = 'uploads/documentosglobales/' . $newFileName;
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
                'tipo_id'     => (int) $tipoId,
                'nombre'      => $nombreLimpio,
                'descripcion' => $descripcionLimpia,
                'ruta'        => (string) $rutaRelativa,
                'estatus'     => $estatusLimpio,
            ]);

            $successMessage = 'El documento global se registró correctamente.';
            $selectedTipo = '';
            $nombreInput = '';
            $descripcionInput = '';
            $estatusInput = 'activo';
        } catch (Throwable $exception) {
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
  <title>Nuevo Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Nuevo Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Nuevo</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="dg-card" style="margin-top:20px;">
      <p class="error-message" style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></p>
    </section>
<?php endif; ?>

<?php if ($successMessage !== null): ?>
    <section class="dg-card" style="margin-top:20px; background-color:#e6f4ea; color:#1e4620;">
      <p style="font-weight:bold;"><?= e($successMessage) ?></p>
    </section>
<?php endif; ?>

    <section class="dg-card">
      <h2>Registrar Documento Global</h2>
      <p>Completa los campos para subir un documento disponible para todos los estudiantes.</p>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <!-- 🧩 Tipo de documento (FK: ss_doc_tipo.id) -->
          <div class="field">
            <label for="tipo_id" class="required">Tipo de documento</label>
            <select id="tipo_id" name="tipo_id" required>
              <option value="">-- Selecciona un tipo --</option>
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
<?php else: ?>
            <div class="hint">Este catálogo viene de <strong>ss_doc_tipo</strong>.</div>
<?php endif; ?>
          </div>

          <!-- 🏷️ Nombre visible -->
          <div class="field">
            <label for="nombre" class="required">Nombre del documento</label>
            <input type="text" id="nombre" name="nombre" value="<?= e($nombreInput) ?>" placeholder="Ej. Guía Oficial de Reporte Bimestral" required />
            <div class="hint">Título claro que verá el estudiante en su panel.</div>
          </div>

          <!-- 📝 Descripción -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Breve descripción o instrucciones para el uso del documento (opcional)…"><?= e($descripcionInput) ?></textarea>
          </div>

          <!-- 📎 Archivo PDF -->
          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo" class="required">Archivo PDF</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" required />
            <div class="hint">Formato permitido: PDF — Tamaño sugerido ≤ 5 MB.</div>
          </div>

          <!-- 🔘 Estatus -->
          <div class="field">
            <label for="estatus" class="required">Estatus</label>
            <select id="estatus" name="estatus" required>
<?php foreach ($allowedStatuses as $status): ?>
<?php
    $statusValue = strtolower((string) $status);
    $isSelected = strtolower($estatusInput) === $statusValue ? ' selected' : '';
    $label = ucfirst($statusValue);
?>
              <option value="<?= e($statusValue) ?>"<?= $isSelected ?>><?= e($label) ?></option>
<?php endforeach; ?>
            </select>
            <div class="hint">Si está <em>inactivo</em>, no se mostrará a los estudiantes.</div>
          </div>

        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <span class="note">Revisa que el archivo sea el correcto antes de subirlo.</span>
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">📤 Subir documento</button>
        </div>
      </form>
    </section>
  </main>

</body>
</html>
