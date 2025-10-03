<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosGlobalController;

require_once __DIR__ . '/../../controller/DocumentosGlobalController.php';

/**
 * @param string|null $value
 */
function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function statusOptions(string $selectedStatus): string
{
    $options = [
        'activo'   => 'Activo',
        'inactivo' => 'Inactivo',
    ];

    $selectedStatus = strtolower(trim($selectedStatus));
    if (!array_key_exists($selectedStatus, $options)) {
        $selectedStatus = 'activo';
    }

    $html = '';
    foreach ($options as $value => $label) {
        $isSelected = $selectedStatus === $value ? ' selected' : '';
        $html .= sprintf('<option value="%s"%s>%s</option>', e($value), $isSelected, e($label));
    }

    return $html;
}

/**
 * @param array<int, array<string, mixed>> $catalog
 */
function documentTypeOptions(array $catalog, int $selectedId): string
{
    $html = '<option value="">-- Seleccionar tipo --</option>';

    foreach ($catalog as $type) {
        $id = (int) ($type['id'] ?? 0);
        $name = (string) ($type['nombre'] ?? 'Tipo sin nombre');
        $isSelected = $id === $selectedId ? ' selected' : '';

        $html .= sprintf('<option value="%d"%s>%s</option>', $id, $isSelected, e($name));
    }

    return $html;
}

function formatDateTime(?string $value): string
{
    $value = $value !== null ? trim($value) : '';

    return $value === '' ? 'No disponible' : $value;
}

function documentDownloadUrl(?string $ruta): string
{
    $ruta = $ruta !== null ? trim($ruta) : '';

    if ($ruta === '') {
        return '';
    }

    if (str_starts_with($ruta, 'http://') || str_starts_with($ruta, 'https://')) {
        return $ruta;
    }

    return '../../' . ltrim($ruta, '/');
}

$idParam = isset($_GET['id']) ? (string) $_GET['id'] : '';
$documentId = filter_var($idParam, FILTER_VALIDATE_INT);

$controller = null;
$document = null;
$documentTypeCatalog = [];
$errorMessage = null;
$successMessage = null;

if ($documentId === false || $documentId === null || $documentId <= 0) {
    $errorMessage = 'El identificador del documento es inválido.';
} else {
    try {
        $controller = new DocumentosGlobalController();
        $documentTypeCatalog = $controller->getDocumentTypeCatalog();

        $document = $controller->find((int) $documentId);
        if ($document === null) {
            throw new RuntimeException('No se encontró el documento solicitado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tipoId = isset($_POST['tipo_id']) ? (int) $_POST['tipo_id'] : 0;
            $nombre = isset($_POST['nombre']) ? trim((string) $_POST['nombre']) : '';
            $descripcion = isset($_POST['descripcion']) ? trim((string) $_POST['descripcion']) : '';
            $estatus = isset($_POST['estatus']) ? strtolower(trim((string) $_POST['estatus'])) : '';

            if ($tipoId <= 0) {
                $errorMessage = 'Debes seleccionar un tipo de documento válido.';
            } elseif ($nombre === '') {
                $errorMessage = 'El nombre del documento es obligatorio.';
            } elseif (!in_array($estatus, ['activo', 'inactivo'], true)) {
                $errorMessage = 'El estatus seleccionado no es válido.';
            }

            $payload = [
                'tipo_id'     => $tipoId,
                'nombre'      => $nombre,
                'descripcion' => $descripcion,
                'estatus'     => $estatus,
            ];

            if ($errorMessage === null) {
                $fileWasUploaded = isset($_FILES['archivo']) && is_array($_FILES['archivo']) && (int) $_FILES['archivo']['error'] !== UPLOAD_ERR_NO_FILE;

                if ($fileWasUploaded) {
                    $fileError = (int) ($_FILES['archivo']['error'] ?? UPLOAD_ERR_NO_FILE);

                    if ($fileError !== UPLOAD_ERR_OK) {
                        $errorMessage = 'Ocurrió un problema al subir el archivo.';
                    } else {
                        $tmpName = (string) $_FILES['archivo']['tmp_name'];
                        $originalName = (string) $_FILES['archivo']['name'];
                        $extension = strtolower((string) pathinfo($originalName, PATHINFO_EXTENSION));

                        if ($extension !== 'pdf') {
                            $errorMessage = 'Solo se permiten archivos en formato PDF.';
                        } elseif (!is_uploaded_file($tmpName)) {
                            $errorMessage = 'El archivo subido no es válido.';
                        } else {
                            $uploadDir = __DIR__ . '/../../uploads/documentos';

                            if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
                                $errorMessage = 'No se pudo preparar el directorio para almacenar el archivo.';
                            } else {
                                $newFileName = 'doc_global_' . $documentId . '_' . time() . '.pdf';
                                $destinationPath = $uploadDir . '/' . $newFileName;

                                if (!move_uploaded_file($tmpName, $destinationPath)) {
                                    $errorMessage = 'No se pudo guardar el archivo subido.';
                                } else {
                                    $payload['ruta'] = 'uploads/documentos/' . $newFileName;
                                }
                            }
                        }
                    }
                }
            }

            if ($errorMessage === null) {
                if ($descripcion === '') {
                    $payload['descripcion'] = null;
                }

                try {
                    $updated = $controller->update((int) $documentId, $payload);

                    if ($updated) {
                        $successMessage = 'El documento se actualizó correctamente.';
                    } else {
                        $successMessage = 'No se realizaron cambios en el documento.';
                    }

                    $document = $controller->find((int) $documentId);
                } catch (Throwable $exception) {
                    $errorMessage = 'Ocurrió un error al guardar los cambios: ' . $exception->getMessage();
                }
            }

            if ($errorMessage !== null && $document !== null) {
                if (!isset($document['tipo']) || !is_array($document['tipo'])) {
                    $document['tipo'] = [];
                }

                $document['tipo']['id'] = $tipoId;
                $document['nombre'] = $nombre;
                $document['descripcion'] = $descripcion === '' ? null : $descripcion;
                $document['estatus'] = $estatus;
            }
        }
    } catch (Throwable $exception) {
        $errorMessage = 'Ocurrió un error al cargar el documento: ' . $exception->getMessage();
        $document = null;
    }
}

$nombreActual = (string) ($document['nombre'] ?? '');
$descripcionActual = (string) ($document['descripcion'] ?? '');
$estatusActual = strtolower((string) ($document['estatus'] ?? 'activo'));
$tipoActual = (int) ($document['tipo']['id'] ?? 0);
$rutaActual = (string) ($document['ruta'] ?? '');
$actualizadoEn = formatDateTime($document['actualizado_en'] ?? null);

$rutaDescarga = documentDownloadUrl($rutaActual);
$nombreArchivoActual = $rutaActual !== '' ? basename($rutaActual) : '';

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Editar Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header>
    <h1>Editar Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Editar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($document === null): ?>
    <section class="dg-card">
      <h2>⚠️ No se pudo cargar el documento</h2>
      <p class="error-message"><?= e($errorMessage) ?></p>
    </section>
<?php else: ?>
    <section class="dg-card">
      <h2>✏️ Editar Información</h2>
      <p>Modifica los datos del documento global y guarda los cambios.</p>

<?php if ($errorMessage !== null && $successMessage === null): ?>
      <div class="alert error"><?= e($errorMessage) ?></div>
<?php endif; ?>

<?php if ($successMessage !== null): ?>
      <div class="alert success"><?= e($successMessage) ?></div>
<?php endif; ?>

      <form action="" method="post" enctype="multipart/form-data" class="form">
        <div class="grid cols-2">

          <div class="field">
            <label for="nombre" class="required">Nombre del Documento</label>
            <input type="text" id="nombre" name="nombre" value="<?= e($nombreActual) ?>" required />
          </div>

          <div class="field">
            <label for="tipo_id" class="required">Tipo de Documento</label>
            <select id="tipo_id" name="tipo_id" required>
              <?= documentTypeOptions($documentTypeCatalog, $tipoActual) ?>
            </select>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" placeholder="Describe brevemente el propósito del documento..."><?= e($descripcionActual) ?></textarea>
          </div>

          <div class="field" style="grid-column: 1 / -1;">
            <label for="archivo">Actualizar Archivo PDF (opcional)</label>
            <input type="file" id="archivo" name="archivo" accept=".pdf" />
            <div class="hint">Si no deseas cambiar el archivo, deja este campo vacío.</div>
<?php if ($rutaDescarga !== ''): ?>
            <p>📁 Archivo actual: <a href="<?= e($rutaDescarga) ?>" class="file-link" target="_blank" rel="noopener noreferrer">Ver documento (<?= e($nombreArchivoActual) ?>)</a></p>
<?php else: ?>
            <p class="muted">No hay archivo disponible para este documento.</p>
<?php endif; ?>
          </div>

          <div class="field">
            <label for="estatus" class="required">Estado</label>
            <select id="estatus" name="estatus" required>
              <?= statusOptions($estatusActual) ?>
            </select>
          </div>

          <div class="field">
            <label>Última actualización</label>
            <input type="text" value="<?= e($actualizadoEn) ?>" disabled />
          </div>

        </div>

        <div class="actions">
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">💾 Guardar Cambios</button>
        </div>
      </form>
    </section>
<?php endif; ?>
  </main>

</body>
</html>
