<?php
declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_auditoria.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioEditController.php';

use Residencia\Controller\Convenio\ConvenioEditController;

/**
 * Redirige de vuelta a la vista del convenio con el resultado de la carga.
 */
function convenioUploadRedirect(int $convenioId, ?string $status = null, ?string $error = null, ?string $detail = null): void
{
    $params = ['id' => $convenioId];

    if ($status !== null) {
        $params['upload_status'] = $status;
    }

    if ($error !== null) {
        $params['upload_error'] = $error;
    }

    if ($detail !== null) {
        $params['upload_error_detail'] = $detail;
    }

    $target = '../../view/convenio/convenio_view.php?' . http_build_query($params);
    header('Location: ' . $target);
    exit;
}

if (!isset($_SERVER['REQUEST_METHOD']) || strtoupper((string) $_SERVER['REQUEST_METHOD']) !== 'POST') {
    $id = isset($_GET['id']) && ctype_digit((string) $_GET['id']) ? (int) $_GET['id'] : 0;
    convenioUploadRedirect($id, null, 'invalid_request');
}

$convenioId = isset($_POST['convenio_id']) && ctype_digit((string) $_POST['convenio_id'])
    ? (int) $_POST['convenio_id']
    : 0;
$postedEmpresaId = isset($_POST['empresa_id']) && ctype_digit((string) $_POST['empresa_id'])
    ? (int) $_POST['empresa_id']
    : null;

if ($convenioId <= 0) {
    convenioUploadRedirect(0, null, 'missing_id');
}

try {
    $controller = new ConvenioEditController();
} catch (\Throwable) {
    convenioUploadRedirect($convenioId, null, 'controller_unavailable');
}

try {
    $convenio = $controller->getConvenioById($convenioId);
} catch (\Throwable) {
    convenioUploadRedirect($convenioId, null, 'not_found');
}

if (!is_array($convenio)) {
    convenioUploadRedirect($convenioId, null, 'not_found');
}

$convenioEmpresaId = isset($convenio['empresa_id']) ? (int) $convenio['empresa_id'] : null;

if ($postedEmpresaId !== null && $convenioEmpresaId !== null && $convenioEmpresaId !== $postedEmpresaId) {
    convenioUploadRedirect($convenioId, null, 'empresa_mismatch');
}

$estatus = isset($convenio['estatus']) ? trim((string) $convenio['estatus']) : '';

if ($estatus !== 'Activa') {
    convenioUploadRedirect($convenioId, null, 'wrong_status');
}

if (!isset($_FILES['convenio_pdf'])) {
    convenioUploadRedirect($convenioId, null, 'no_file');
}

$uploadResult = convenioProcessFileUpload(
    $_FILES['convenio_pdf'],
    convenioUploadsAbsoluteDir(),
    convenioUploadsRelativeDir(),
    'convenio'
);

if ($uploadResult['error'] !== null) {
    convenioUploadRedirect($convenioId, null, 'upload_error', $uploadResult['error']);
}

$newRelativePath = $uploadResult['path'];

if ($newRelativePath === null || $newRelativePath === '') {
    convenioUploadRedirect($convenioId, null, 'no_file');
}

$previousFirmado = isset($convenio['firmado_path']) && $convenio['firmado_path'] !== null
    ? trim((string) $convenio['firmado_path'])
    : '';

$payload = [
    'empresa_id' => $convenioEmpresaId !== null ? $convenioEmpresaId : 0,
    'folio' => $convenio['folio'] ?? null,
    'estatus' => $estatus !== '' ? $estatus : 'En revision',
    'tipo_convenio' => $convenio['tipo_convenio'] ?? null,
    'responsable_academico' => $convenio['responsable_academico'] ?? null,
    'fecha_inicio' => $convenio['fecha_inicio'] ?? null,
    'fecha_fin' => $convenio['fecha_fin'] ?? null,
    'observaciones' => $convenio['observaciones'] ?? null,
    'borrador_path' => $convenio['borrador_path'] ?? null,
    'firmado_path' => $newRelativePath,
];

$uploadedAbsolutePath = rtrim(convenioUploadsAbsoluteDir(), DIRECTORY_SEPARATOR)
    . DIRECTORY_SEPARATOR
    . basename($newRelativePath);

try {
    $controller->updateConvenio($convenioId, $payload);
    $updatedConvenio = $controller->getConvenioById($convenioId);
} catch (\Throwable) {
    if ($uploadedAbsolutePath !== '' && is_file($uploadedAbsolutePath)) {
        @unlink($uploadedAbsolutePath);
    }

    convenioUploadRedirect($convenioId, null, 'save_failed');
}

if ($previousFirmado !== '' && $previousFirmado !== $newRelativePath) {
    $previousFileName = basename(str_replace(['\\', '/'], DIRECTORY_SEPARATOR, $previousFirmado));
    $previousAbsolutePath = rtrim(convenioUploadsAbsoluteDir(), DIRECTORY_SEPARATOR)
        . DIRECTORY_SEPARATOR
        . $previousFileName;

    if ($previousFileName !== '' && is_file($previousAbsolutePath)) {
        @unlink($previousAbsolutePath);
    }
}

if (isset($updatedConvenio) && is_array($updatedConvenio)) {
    $cambios = convenioAuditoriaDetectCambios($convenio, $updatedConvenio);
    $contextoAuditoria = convenioCurrentAuditContext();

    if ($cambios['estatusCambio']) {
        $accionEstatus = convenioAuditoriaActionForStatusChange(
            $cambios['estatusAnterior'],
            $cambios['estatusNuevo']
        );
        convenioRegisterAuditEvent($accionEstatus, $convenioId, $contextoAuditoria, $cambios['detallesEstatus']);
    }

    if ($cambios['otrosCambios']) {
        convenioRegisterAuditEvent('actualizar', $convenioId, $contextoAuditoria, $cambios['detallesCampos']);
    }
}

convenioUploadRedirect($convenioId, 'success');
