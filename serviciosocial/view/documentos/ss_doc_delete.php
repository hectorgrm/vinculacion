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

/**
 * @return string
 */
function statusClass(string $status): string
{
    return match (strtolower($status)) {
        'aprobado'  => 'status aprobado',
        'rechazado' => 'status rechazado',
        default     => 'status pendiente',
    };
}

/**
 * @return string
 */
function statusLabel(string $status): string
{
    return match (strtolower($status)) {
        'aprobado'  => 'Aprobado',
        'rechazado' => 'Rechazado',
        default     => 'Pendiente',
    };
}

$idParam = isset($_GET['id']) ? (string) $_GET['id'] : '';
$documentId = filter_var($idParam, FILTER_VALIDATE_INT);

$document = null;
$errorMessage = null;

if ($documentId === false || $documentId === null || $documentId <= 0) {
    $errorMessage = 'El identificador del documento es inválido.';
} else {
    try {
        $controller = new DocumentosController();
        $document = $controller->find((int) $documentId);

        if ($document === null) {
            $errorMessage = 'No se encontró el documento solicitado.';
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $confirmation = isset($_POST['confirmacion']) ? strtoupper(trim((string) $_POST['confirmacion'])) : '';

            if ($confirmation !== 'ELIMINAR') {
                $errorMessage = 'Para eliminar el documento debes escribir la palabra "ELIMINAR".';
            } else {
                $deleted = $controller->delete((int) $documentId);

                if ($deleted) {
                    header('Location: ss_doc_list.php?deleted=1');
                    exit;
                }

                $errorMessage = 'No se pudo eliminar el documento. Es posible que ya haya sido eliminado.';
                $document = $controller->find((int) $documentId);
            }
        }
    } catch (\Throwable $exception) {
        $errorMessage = 'Ocurrió un error al procesar la solicitud: ' . $exception->getMessage();
        $document = null;
    }
}

$documentIdLabel = $document !== null ? (string) ($document['id'] ?? '') : '';
$estudianteNombre = $document !== null ? (string) ($document['estudiante']['nombre'] ?? 'Estudiante sin nombre') : '';
$matricula = $document !== null ? (string) ($document['estudiante']['matricula'] ?? '') : '';
$tipoNombre = $document !== null ? (string) ($document['tipo']['nombre'] ?? 'Tipo desconocido') : '';
$periodoNumero = $document !== null ? ($document['periodo']['numero'] ?? null) : null;
$periodoLabel = $periodoNumero !== null ? 'Periodo ' . $periodoNumero : 'Sin periodo';
$estatusActual = $document !== null ? (string) ($document['estatus'] ?? 'pendiente') : 'pendiente';
$fechaActualizacion = $document !== null ? (string) ($document['actualizado_en'] ?? '') : '';

$estudianteDisplay = $estudianteNombre;
if ($matricula !== '') {
    $estudianteDisplay .= ' (' . $matricula . ')';
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eliminar Documento · Servicio Social</title>
  <link rel="stylesheet" href="../../assets/css/documentos/globaldocumentos.css" />
</head>
<body>

  <header class="danger-header">
    <h1>Eliminar Documento</h1>
    <nav class="breadcrumb">
      <a href="../../index.php">Inicio</a>
      <span>›</span>
      <a href="ss_doc_list.php">Gestión de Documentos</a>
      <span>›</span>
      <span>Eliminar</span>
    </nav>
  </header>

  <main>
    <a href="ss_doc_list.php" class="btn btn-secondary">⬅ Volver a la lista</a>

<?php if ($errorMessage !== null): ?>
    <section class="card form-card--danger" style="margin-top:20px;">
      <h2>⚠️ No se puede eliminar el documento</h2>
      <p style="color:#b00020; font-weight:bold;">
        <?= e($errorMessage) ?>
      </p>
    </section>
<?php elseif ($document !== null): ?>
    <section class="card form-card--danger">
      <h2>⚠️ Confirmar eliminación del documento</h2>
      <p>
        Estás a punto de <strong>eliminar definitivamente</strong> el siguiente documento.
        Esta acción es <strong>irreversible</strong> y no podrás recuperarlo después de confirmar.
      </p>

      <!-- 📄 Información del documento a eliminar -->
      <div class="details-list">
        <dt>ID del Documento:</dt>
        <dd><?= e($documentIdLabel) ?></dd>

        <dt>Estudiante:</dt>
        <dd><?= e($estudianteDisplay) ?></dd>

        <dt>Periodo:</dt>
        <dd><?= e($periodoLabel) ?></dd>

        <dt>Tipo de Documento:</dt>
        <dd><?= e($tipoNombre) ?></dd>

        <dt>Estado actual:</dt>
        <dd><span class="<?= e(statusClass($estatusActual)) ?>"><?= e(statusLabel($estatusActual)) ?></span></dd>

        <dt>Fecha de actualización:</dt>
        <dd><?= e($fechaActualizacion) ?></dd>
      </div>

      <!-- ⚠️ Formulario de confirmación -->
      <form action="" method="post" class="form">
        <div class="field">
          <label for="confirmacion" class="required">Confirma escribiendo <strong>ELIMINAR</strong></label>
          <input type="text" id="confirmacion" name="confirmacion" placeholder="Escribe ELIMINAR para confirmar" required />
          <div class="hint">Por seguridad, debes escribir la palabra <strong>ELIMINAR</strong> para continuar.</div>
        </div>

        <div class="field">
          <label for="motivo">Motivo de eliminación (opcional)</label>
          <textarea id="motivo" name="motivo" placeholder="Describe brevemente el motivo de la eliminación..."></textarea>
        </div>

        <!-- ✅ Acciones -->
        <div class="actions">
          <a href="ss_doc_list.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-danger">🗑️ Eliminar Documento</button>
        </div>
      </form>
    </section>
<?php endif; ?>
  </main>

</body>
</html>
