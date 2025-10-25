<?php
declare(strict_types=1);

require_once __DIR__ . '/../../controller/ConvenioController.php';
require_once __DIR__ . '/../../common/functions/conveniofunction.php';
require_once __DIR__ . '/../../common/functions/convenio/conveniofunctions_delete.php';

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

$controllerData = convenioResolveControllerData();
$controller = $controllerData['controller'] ?? null;
$controllerError = $controllerData['error'] ?? null;

$errors = [];
$successMessage = null;
$sanitizedPost = null;
$convenio = null;

if ($controller !== null && $requestedId > 0) {
    try {
        $convenio = $controller->getConvenioById($requestedId);
        if ($convenio === null) {
            $errors[] = 'No se encontró el convenio solicitado.';
        }
    } catch (\RuntimeException $runtimeException) {
        $errors[] = $runtimeException->getMessage();
    }
}

if ($controller !== null && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    $handleResult = convenioHandleDeleteRequest($controller, $_POST);
    $sanitizedPost = $handleResult['sanitized'];
    $errors = array_merge($errors, $handleResult['errors']);
    $successMessage = $handleResult['successMessage'];

    if ($handleResult['convenioId'] > 0) {
        $requestedId = $handleResult['convenioId'];
    }

    if ($successMessage !== null && $requestedId > 0) {
        try {
            $refreshed = $controller->getConvenioById($requestedId);
            if ($refreshed !== null) {
                $convenio = $refreshed;
            }
        } catch (\RuntimeException $runtimeException) {
            $errors[] = $runtimeException->getMessage();
        }
    }
} elseif ($controller === null && ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
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

$formDisabled = $successMessage !== null || $isAlreadyInactive || $controller === null;

return [
    'controllerError' => $controllerError,
    'errors' => $errors,
    'successMessage' => $successMessage,
    'convenioIdDisplay' => $convenioIdDisplay,
    'empresaIdDisplay' => $empresaIdDisplay,
    'empresaNombreDisplay' => $empresaNombreDisplay,
    'motivoValue' => $motivoValue,
    'confirmChecked' => $confirmChecked,
    'isAlreadyInactive' => $isAlreadyInactive,
    'formDisabled' => $formDisabled,
];
