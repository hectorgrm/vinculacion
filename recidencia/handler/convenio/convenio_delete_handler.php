<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../controller/convenio/ConvenioEditController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_delete.php';
require_once __DIR__ . '/../../model/convenio/ConvenioMachoteModel.php';

$requestedId = 0;
if (isset($_GET['id'])) {
    $filteredId = preg_replace('/[^0-9]/', '', (string) $_GET['id']);
    if ($filteredId !== null && $filteredId !== '') {
        $requestedId = (int) $filteredId;
    }
}

$requestedEmpresaId = null;
if (isset($_GET['empresa_id'])) {
    $filteredEmpresaId = preg_replace('/[^0-9]/', '', (string) $_GET['empresa_id']);
    if ($filteredEmpresaId !== null && $filteredEmpresaId !== '') {
        $requestedEmpresaId = (int) $filteredEmpresaId;
    }
}

$controller = null;
$editController = null;
$controllerError = null;

try {
    $controller = new \Residencia\Controller\ConvenioController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    $controllerError = $message !== '' ? $message : 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
}

try {
    $editController = new \Residencia\Controller\Convenio\ConvenioEditController();
} catch (\Throwable $throwable) {
    $message = trim($throwable->getMessage());
    if ($controllerError === null) {
        $controllerError = $message !== '' ? $message : 'No se pudo establecer conexion con la base de datos. Intenta nuevamente mas tarde.';
    }
}

$errors = [];
$successMessage = null;
$sanitizedPost = null;
$convenio = null;
$machoteIdDisplay = '';
$machoteModel = null;
$empresaEstatus = '';
$empresaIsCompletada = false;
$empresaIsInactiva = false;

try {
    $machoteModel = \Residencia\Model\Convenio\ConvenioMachoteModel::createWithDefaultConnection();
} catch (\Throwable) {
    $machoteModel = null;
}

if ($editController === null && $controllerError !== null) {
    $errors[] = $controllerError;
}

if ($editController !== null && $requestedId > 0) {
    try {
        $convenio = $editController->getConvenioById($requestedId);
        if ($convenio === null) {
            $errors[] = 'No se encontró el convenio solicitado.';
        }
    } catch (\RuntimeException $runtimeException) {
        $errors[] = $runtimeException->getMessage();
    }
}

if ($convenio !== null && isset($convenio['empresa_estatus'])) {
    $empresaEstatus = trim((string) $convenio['empresa_estatus']);
    $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;
}

if ($controller !== null && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    if ($empresaIsCompletada) {
        $errors[] = 'No se puede desactivar el convenio porque la empresa está en estatus Completada.';
    } else {
        $handleResult = convenioHandleDeleteRequest($controller, $_POST);
        $sanitizedPost = $handleResult['sanitized'];
        $errors = array_merge($errors, $handleResult['errors']);
        $successMessage = $handleResult['successMessage'];

        if ($handleResult['convenioId'] > 0) {
            $requestedId = $handleResult['convenioId'];
        }

        if ($successMessage !== null && $requestedId > 0 && $editController !== null) {
            try {
                $refreshed = $editController->getConvenioById($requestedId);
                if ($refreshed !== null) {
                    $convenio = $refreshed;
                }
            } catch (\RuntimeException $runtimeException) {
                $errors[] = $runtimeException->getMessage();
            }
        }
    }
} elseif (($controller === null || $editController === null) && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $errors[] = $controllerError ?? 'No se pudo procesar la solicitud.';
}

$convenioIdDisplay = $convenio !== null && isset($convenio['id'])
    ? (string) $convenio['id']
    : ($requestedId > 0 ? (string) $requestedId : '');

$empresaIdDisplay = '';
if ($convenio !== null && isset($convenio['empresa_id'])) {
    $empresaIdDisplay = (string) $convenio['empresa_id'];
} elseif ($sanitizedPost !== null && $sanitizedPost['empresa_id'] !== null) {
    $empresaIdDisplay = (string) $sanitizedPost['empresa_id'];
} elseif ($requestedEmpresaId !== null) {
    $empresaIdDisplay = (string) $requestedEmpresaId;
}

$machoteIdFromConvenio = null;
if ($convenio !== null && isset($convenio['machote_id']) && ctype_digit((string) $convenio['machote_id'])) {
    $machoteIdFromConvenio = (int) $convenio['machote_id'];
}

if ($machoteIdFromConvenio !== null && $machoteIdFromConvenio > 0) {
    $machoteIdDisplay = (string) $machoteIdFromConvenio;
} elseif ($machoteModel !== null && $requestedId > 0) {
    try {
        $machoteRecord = $machoteModel->getByConvenio($requestedId);
        if ($machoteRecord !== null && isset($machoteRecord['id'])) {
            $machoteIdDisplay = (string) $machoteRecord['id'];
        }
    } catch (\Throwable) {
        // Si ocurre un error al obtener el machote, simplemente no se muestra el enlace.
    }
}

$empresaNombreDisplay = '';
if ($convenio !== null && isset($convenio['empresa_nombre'])) {
    $empresaNombreDisplay = (string) $convenio['empresa_nombre'];
}

$motivoValue = '';
if ($sanitizedPost !== null && $successMessage === null && $sanitizedPost['motivo'] !== null) {
    $motivoValue = (string) $sanitizedPost['motivo'];
}

$confirmChecked = $sanitizedPost !== null && $sanitizedPost['confirmado'] === true;

$isAlreadyInactive = $convenio !== null
    && isset($convenio['estatus'])
    && (string) $convenio['estatus'] === 'Inactiva';

if ($convenio !== null && isset($convenio['empresa_estatus'])) {
    $empresaEstatus = trim((string) $convenio['empresa_estatus']);
    $empresaIsCompletada = strcasecmp($empresaEstatus, 'Completada') === 0;
    $empresaIsInactiva = strcasecmp($empresaEstatus, 'Inactiva') === 0;
}

$formDisabled = $successMessage !== null
    || $isAlreadyInactive
    || $controller === null
    || $editController === null
    || $empresaIsCompletada
    || $empresaIsInactiva;

return [
    'controllerError' => $controllerError,
    'errors' => $errors,
    'successMessage' => $successMessage,
    'convenioIdDisplay' => $convenioIdDisplay,
    'empresaIdDisplay' => $empresaIdDisplay,
    'machoteIdDisplay' => $machoteIdDisplay,
    'empresaNombreDisplay' => $empresaNombreDisplay,
    'motivoValue' => $motivoValue,
    'confirmChecked' => $confirmChecked,
    'isAlreadyInactive' => $isAlreadyInactive,
    'formDisabled' => $formDisabled,
    'empresaIsCompletada' => $empresaIsCompletada,
    'empresaIsInactiva' => $empresaIsInactiva,
];
