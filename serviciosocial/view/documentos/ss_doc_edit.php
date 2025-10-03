<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosController;

require_once __DIR__ . '/../../controller/DocumentosController.php';

$idParam = isset($_GET['id']) ? (string) $_GET['id'] : '';
$documentId = filter_var($idParam, FILTER_VALIDATE_INT);

$document = null;
$errorMessage = null;
$successMessage = null;

/**
 * @return string
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

/**
 * @return string
 */
function statusOptions(string $selectedStatus): string
{
    $options = [
        'pendiente' => 'Pendiente',
        'aprobado'  => 'Aprobado',
        'rechazado' => 'Rechazado',
    ];

    $selectedStatus = strtolower($selectedStatus);
    if (!array_key_exists($selectedStatus, $options)) {
        $selectedStatus = 'pendiente';
    }

    $html = '';

    foreach ($options as $value => $label) {
        $isSelected = $selectedStatus === $value ? ' selected' : '';
        $html .= sprintf('<option value="%s"%s>%s</option>', e($value), $isSelected, e($label));
    }

    return $html;
}

if ($documentId === false || $documentId === null || $documentId <= 0) {
    $errorMessage = 'El identificador del documento es inválido.';
} else {
    try {
        $controller = new DocumentosController();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $estatus = isset($_POST['estatus']) ? trim((string) $_POST['estatus']) : '';
            $observaciones = isset($_POST['observaciones']) ? (string) $_POST['observaciones'] : '';

            $payload = [
                'estatus'      => $estatus,
                'observacion'  => $observaciones,
            ];

            if (isset($_FILES['archivo']) && is_array($_FILES['archivo'])) {
                $fileError = (int) $_FILES['archivo']['error'];

                if ($fileError === UPLOAD_ERR_OK) {
                    $tmpName = (string) $_FILES['archivo']['tmp_name'];
                    $originalName = (string) $_FILES['archivo']['name'];
                    $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

                    if ($extension !== 'pdf') {
                        $errorMessage = 'Solo se permiten archivos PDF.';
                    } else {
                        $uploadDir = __DIR__ . '/../../uploads/documentos';

                        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                            $errorMessage = 'No se pudo preparar el directorio para subir archivos.';
                        } else {
                            $newFileName = 'doc_' . $documentId . '_' . time() . '.pdf';
                            $destinationPath = $uploadDir . '/' . $newFileName;

                            if (!is_uploaded_file($tmpName) || !move_uploaded_file($tmpName, $destinationPath)) {
                                $errorMessage = 'No se pudo guardar el archivo subido.';
                            } else {
                                $payload['ruta'] = 'uploads/documentos/' . $newFileName;
                            }
                        }
                    }
                } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
                    $errorMessage = 'Ocurrió un problema al subir el archivo.';
                }
            }

            if ($errorMessage === null) {
                $updated = $controller->update((int) $documentId, $payload);

                if ($updated) {
                    $successMessage = 'El documento se actualizó correctamente.';
                } else {
                    $successMessage = 'No se realizaron cambios en el documento.';
                }
            }
        }

        if ($errorMessage === null) {
            $document = $controller->find((int) $documentId);

            if ($document === null) {
                $errorMessage = 'No se encontró el documento solicitado.';
            }
        }
    } catch (\Throwable $exception) {
        $errorMessage = 'Ocurrió un error al cargar el documento: ' . $exception->getMessage();
    }
}

$estudianteNombre = '';
$matricula = '';
$tipoNombre = '';
$periodoNumero = null;
$periodoLabel = '';
$actualizadoEn = '';
$estatusActual = 'pendiente';
$observacionActual = '';
$ruta = '';
$estudianteDisplay = '';

if ($document !== null) {
    $estudianteNombre = (string) ($document['estudiante']['nombre'] ?? '');
    $matricula = (string) ($document['estudiante']['matricula'] ?? '');
    $tipoNombre = (string) ($document['tipo']['nombre'] ?? '');
    $periodoNumero = $document['periodo']['numero'] ?? null;
    $periodoLabel = $periodoNumero !== null ? 'Periodo ' . $periodoNumero : 'Sin periodo';
    $actualizadoEn = (string) ($document['actualizado_en'] ?? '');
    $estatusActual = strtolower((string) ($document['estatus'] ?? 'pendiente'));
    $observacionActual = (string) ($document['observacion'] ?? '');
    $ruta = (string) ($document['ruta'] ?? '');
}

$estudianteDisplay = $estudianteNombre;
if ($matricula !== '') {
    $estudianteDisplay .= ' (' . $matricula . ')';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $errorMessage !== null) {
    $estatusActual = strtolower(isset($_POST['estatus']) ? (string) $_POST['estatus'] : $estatusActual);
    $observacionActual = isset($_POST['observaciones']) ? (string) $_POST['observaciones'] : $observacionActual;
}

$rutaEnlace = '';
if ($ruta !== '') {
    if (strpos($ruta, 'http://') === 0 || strpos($ruta, 'https://') === 0) {
        $rutaEnlace = $ruta;
    } else {
        $rutaEnlace = '../../' . ltrim($ruta, '/');
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header>
    <h1>Editar Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="card" style="margin-top:20px;">
      <p style="color:#b00020; font-weight:bold;"><?= e($errorMessage) ?></p>
    </section>
<?php elseif ($document !== null): ?>
    <section class="card">
      <h2>Formulario de Edición</h2>
      <p>Modifica la información necesaria del documento. Puedes actualizar su estado, agregar observaciones o subir una nueva versión.</p>

<?php if ($successMessage !== null): ?>
      <div style="background-color:#e6f4ea; color:#1e4620; padding:12px 16px; border-radius:8px; margin-bottom:16px;">
        <?= e($successMessage) ?>
      </div>
<?php endif; ?>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <div class="field">
            <label for="estudiante">Estudiante</label>
            <input type="text" id="estudiante" name="estudiante" value="<?= e($estudianteDisplay) ?>" readonly />
          </div>

          <div class="field">
            <label for="tipo">Tipo de Documento</label>
            <input type="text" id="tipo" name="tipo" value="<?= e($tipoNombre) ?>" readonly />
          </div>

          <div class="field">
            <label for="periodo">Periodo</label>
            <input type="text" id="periodo" name="periodo" value="<?= e($periodoLabel) ?>" readonly />
          </div>

          <div class="field">
            <label for="fecha_subida">Última actualización</label>
            <input type="text" id="fecha_subida" name="fecha_subida" value="<?= e($actualizadoEn) ?>" readonly />
          </div>

          <div class="field">
            <label for="estatus" class="required">Estado</label>
            <select id="estatus" name="estatus" required>
              <?= statusOptions($estatusActual) ?>
            </select>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label>Archivo actual</label>
<?php if ($rutaEnlace !== ''): ?>
            <a href="<?= e($rutaEnlace) ?>" target="_blank" class="btn btn-primary">📄 Ver documento</a>
<?php else: ?>
            <span class="muted">Sin archivo adjunto</span>
<?php endif; ?>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo">Actualizar archivo PDF (opcional)</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" />
            <div class="hint">Si subes un nuevo archivo, reemplazará al actual.</div>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label for="observaciones">Observaciones</label>
            <textarea id="observaciones" name="observaciones" placeholder="Escribe tus comentarios o notas sobre el documento..."><?= e($observacionActual) ?></textarea>
          </div>
        </div>

        <div class="actions">
          <a href="ss_doc_view.php?id=<?= e((string) ($document['id'] ?? '')) ?>" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-success">💾 Guardar Cambios</button>
        </div>
      </form>
    </section>
<?php endif; ?>
  </main>

</body>
</html>
