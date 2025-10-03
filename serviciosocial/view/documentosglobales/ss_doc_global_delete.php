<?php

declare(strict_types=1);

use Serviciosocial\Controller\DocumentosGlobalController;

require_once __DIR__ . '/../../controller/DocumentosGlobalController.php';

$documentId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$document = null;
$errorMessage = null;
$successMessage = null;

try {
    $controller = new DocumentosGlobalController();
} catch (Throwable $exception) {
    $controller = null;
    $errorMessage = 'Ocurrió un error al conectar con la base de datos: ' . $exception->getMessage();
}

if ($controller !== null && $errorMessage === null) {
    if ($documentId <= 0) {
        $errorMessage = 'El identificador del documento proporcionado no es válido.';
    } else {
        try {
            $document = $controller->getDocumentForDeletion($documentId);
        } catch (\RuntimeException $exception) {
            $errorMessage = $exception->getMessage();
        } catch (Throwable $exception) {
            $errorMessage = 'Ocurrió un error al cargar el documento: ' . $exception->getMessage();
        }
    }
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && $controller instanceof DocumentosGlobalController
    && $document !== null
    && $errorMessage === null
) {
    $confirmation = isset($_POST['confirmacion']) ? trim((string) $_POST['confirmacion']) : '';

    try {
        $controller->deleteWithConfirmation($documentId, $confirmation);
        $successMessage = 'El documento se eliminó correctamente.';
        $document = null;
    } catch (\InvalidArgumentException $exception) {
        $errorMessage = $exception->getMessage();
    } catch (\RuntimeException $exception) {
        $errorMessage = $exception->getMessage();
        $document = null;
    } catch (Throwable $exception) {
        $errorMessage = 'Ocurrió un error al eliminar el documento: ' . $exception->getMessage();
        $document = null;
    }
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function statusClass(string $estatus): string
{
    return match (strtolower($estatus)) {
        'inactivo' => 'status inactivo',
        default    => 'status activo',
    };
}

function statusLabel(string $estatus): string
{
    return match (strtolower($estatus)) {
        'inactivo' => 'Inactivo',
        default    => 'Activo',
    };
}

$documentName = '';
$documentDescription = '';
$documentStatus = 'activo';
$documentRoute = '';
$documentUpdatedAt = '';
$documentType = 'Tipo desconocido';

if (is_array($document)) {
    $documentName = (string) ($document['nombre'] ?? '');
    $documentDescription = (string) ($document['descripcion'] ?? '');
    $documentStatus = (string) ($document['estatus'] ?? 'activo');
    $documentRoute = (string) ($document['ruta'] ?? '');
    $documentUpdatedAt = (string) ($document['actualizado_en'] ?? '');
    $documentType = (string) ($document['tipo']['nombre'] ?? 'Tipo desconocido');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento Global · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/documentosglobalstyles.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Eliminar Documento Global</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_global_list.php">Documentos Globales</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_global_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

    <section class="dg-card form-card--danger">
      <h2>⚠️ Confirmación de eliminación</h2>
      <p>
        Estás a punto de <strong>eliminar permanentemente</strong> este documento global.
        Esta acción <strong>no se puede deshacer</strong>. Por favor, confirma que realmente deseas eliminarlo.
      </p>

<?php if ($errorMessage !== null): ?>
      <div class="alert alert-danger" role="alert">
        <?= e($errorMessage) ?>
      </div>
<?php elseif ($successMessage !== null): ?>
      <div class="alert alert-success" role="alert">
        <?= e($successMessage) ?>
      </div>
<?php endif; ?>

<?php if ($document !== null): ?>
      <div class="details-list">
        <dt>ID del documento:</dt>
        <dd><?= e((string) ($document['id'] ?? '')) ?></dd>

        <dt>Nombre:</dt>
        <dd><?= e((string) $documentName) ?></dd>

        <dt>Tipo:</dt>
        <dd><span class="doc-type"><?= e((string) $documentType) ?></span></dd>

        <dt>Descripción:</dt>
        <dd>
<?php if ($documentDescription !== ''): ?>
          <?= e((string) $documentDescription) ?>
<?php else: ?>
          <span class="muted">Sin descripción</span>
<?php endif; ?>
        </dd>

        <dt>Estado actual:</dt>
        <dd><span class="<?= e(statusClass((string) $documentStatus)) ?>"><?= e(statusLabel((string) $documentStatus)) ?></span></dd>

        <dt>Archivo actual:</dt>
        <dd>
<?php if ($documentRoute !== ''): ?>
          <a href="<?= e((string) $documentRoute) ?>" class="file-link" target="_blank" rel="noopener noreferrer">📄 Ver archivo</a>
<?php else: ?>
          <span class="muted">No hay archivo disponible</span>
<?php endif; ?>
        </dd>

        <dt>Última actualización:</dt>
        <dd><?= e((string) $documentUpdatedAt) ?></dd>
      </div>

      <form action="" method="post" class="form" style="margin-top: 30px;">
        <div class="field">
          <label for="confirmacion" class="required">Escribe <strong>ELIMINAR</strong> para confirmar</label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR aquí..." required />
          <div class="hint">Esta medida es para evitar eliminaciones accidentales.</div>
        </div>

        <div class="actions">
          <a href="ss_doc_global_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
        </div>
      </form>
<?php elseif ($successMessage !== null): ?>
      <div class="actions" style="margin-top: 30px;">
        <a href="ss_doc_global_list.php" class="btn btn-primary">Volver al listado</a>
      </div>
<?php endif; ?>
    </section>
  </main>

</body>
</html>
