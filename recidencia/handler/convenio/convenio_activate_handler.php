<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/convenio/ConvenioEditController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_edit.php';

use Residencia\Controller\Convenio\ConvenioEditController;

/**
 * Redirige de vuelta a la vista del convenio con el resultado del intento de activación.
 */
function convenioActivateRedirect(int $convenioId, ?string $status = null, ?string $error = null): void
{
    $params = ['id' => $convenioId];

    if ($status !== null) {
        $params['activate_status'] = $status;
    }

    if ($error !== null) {
        $params['activate_error'] = $error;
    }

    $target = '../../view/convenio/convenio_view.php?' . http_build_query($params);
    header('Location: ' . $target);
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    convenioActivateRedirect(0, null, 'invalid_request');
}

$convenioIdRaw = $_POST['convenio_id'] ?? null;
$convenioId = 0;
if (is_scalar($convenioIdRaw)) {
    $filtered = preg_replace('/[^0-9]/', '', (string) $convenioIdRaw);
    $convenioId = $filtered !== null && $filtered !== '' ? (int) $filtered : 0;
}

if ($convenioId <= 0) {
    convenioActivateRedirect(0, null, 'missing_id');
}

$postedEmpresaId = null;
if (isset($_POST['empresa_id']) && is_scalar($_POST['empresa_id'])) {
    $filteredEmpresa = preg_replace('/[^0-9]/', '', (string) $_POST['empresa_id']);
    if ($filteredEmpresa !== null && $filteredEmpresa !== '') {
        $postedEmpresaId = (int) $filteredEmpresa;
    }
}

try {
    $controller = new ConvenioEditController();
} catch (\Throwable) {
    convenioActivateRedirect($convenioId, null, 'controller_unavailable');
}

try {
    $convenio = $controller->getConvenioById($convenioId);
} catch (\Throwable) {
    convenioActivateRedirect($convenioId, null, 'controller_unavailable');
}

if (!is_array($convenio)) {
    convenioActivateRedirect($convenioId, null, 'not_found');
}

$convenioEmpresaId = isset($convenio['empresa_id']) ? (int) $convenio['empresa_id'] : null;
if ($postedEmpresaId !== null && $convenioEmpresaId !== null && $postedEmpresaId !== $convenioEmpresaId) {
    convenioActivateRedirect($convenioId, null, 'empresa_mismatch');
}

$estatusActual = isset($convenio['estatus']) ? convenioNormalizeStatus((string) $convenio['estatus']) : 'En revisiA3n';
if ($estatusActual === 'Activa') {
    convenioActivateRedirect($convenioId, 'already_active');
}

$firmadoPath = isset($convenio['firmado_path']) && $convenio['firmado_path'] !== null
    ? trim((string) $convenio['firmado_path'])
    : '';

if ($firmadoPath === '') {
    convenioActivateRedirect($convenioId, null, 'missing_file');
}

$payload = [
    'empresa_id' => $convenioEmpresaId !== null ? $convenioEmpresaId : 0,
    'folio' => $convenio['folio'] ?? null,
    'estatus' => 'Activa',
    'tipo_convenio' => $convenio['tipo_convenio'] ?? null,
    'responsable_academico' => $convenio['responsable_academico'] ?? null,
    'fecha_inicio' => $convenio['fecha_inicio'] ?? null,
    'fecha_fin' => $convenio['fecha_fin'] ?? null,
    'observaciones' => $convenio['observaciones'] ?? null,
    'borrador_path' => $convenio['borrador_path'] ?? null,
    'firmado_path' => $firmadoPath,
];

try {
    $controller->updateConvenio($convenioId, $payload);
    $updatedConvenio = $controller->getConvenioById($convenioId);
} catch (\Throwable) {
    convenioActivateRedirect($convenioId, null, 'save_failed');
}

if (isset($updatedConvenio) && is_array($updatedConvenio)) {
    $cambios = convenioAuditoriaDetectCambios($convenio, $updatedConvenio);
    $contexto = convenioCurrentAuditContext();

    if ($cambios['estatusCambio']) {
        $accionEstatus = convenioAuditoriaActionForStatusChange(
            $cambios['estatusAnterior'],
            $cambios['estatusNuevo']
        );
        convenioRegisterAuditEvent($accionEstatus, $convenioId, $contexto, $cambios['detallesEstatus']);
    }

    if ($cambios['otrosCambios']) {
        convenioRegisterAuditEvent('actualizar', $convenioId, $contexto, $cambios['detallesCampos']);
    }
}

convenioActivateRedirect($convenioId, 'success');
