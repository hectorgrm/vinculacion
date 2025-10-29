<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documentotipo/documentotipo_functions_delete.php';
require_once __DIR__ . '/../../controller/documentotipo/DocumentoTipoDeleteController.php';

use Residencia\Controller\DocumentoTipo\DocumentoTipoDeleteController;

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';

$rawId = $_GET['id'] ?? $_POST['id'] ?? null;
$documentoTipoId = documentoTipoDeleteNormalizeId($rawId);

if ($method !== 'POST') {
    $redirect = documentoTipoDeleteBuildRedirectUrl(
        '../../view/documentotipo/documentotipo_delete.php',
        [
            'id' => $documentoTipoId,
            'error' => 'method_not_allowed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$sanitized = documentoTipoDeleteSanitizeInput($_POST);
$validationErrors = documentoTipoDeleteValidateRequest($sanitized);
$documentoTipoId = $sanitized['id'];

if ($validationErrors !== []) {
    $redirect = documentoTipoDeleteBuildRedirectUrl(
        '../../view/documentotipo/documentotipo_delete.php',
        [
            'id' => $documentoTipoId,
            'error' => $validationErrors[0],
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $controller = new DocumentoTipoDeleteController();
} catch (\Throwable $exception) {
    $redirect = documentoTipoDeleteBuildRedirectUrl(
        '../../view/documentotipo/documentotipo_delete.php',
        [
            'id' => $documentoTipoId,
            'error' => 'controller',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $result = $controller->deleteDocumentoTipo($documentoTipoId);
} catch (\RuntimeException $exception) {
    $errorCode = $exception->getCode() === 404 ? 'not_found' : 'delete_failed';

    $redirect = documentoTipoDeleteBuildRedirectUrl(
        '../../view/documentotipo/documentotipo_delete.php',
        [
            'id' => $documentoTipoId,
            'error' => $errorCode,
        ]
    );

    header('Location: ' . $redirect);
    exit;
} catch (\Throwable $exception) {
    $redirect = documentoTipoDeleteBuildRedirectUrl(
        '../../view/documentotipo/documentotipo_delete.php',
        [
            'id' => $documentoTipoId,
            'error' => 'delete_failed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$documentoTipo = $result['documentoTipo'] ?? [];
$nombre = '';
if (is_array($documentoTipo) && isset($documentoTipo['nombre'])) {
    $nombreValue = trim((string) $documentoTipo['nombre']);
    if ($nombreValue !== '') {
        $nombre = $nombreValue;
    }
}

$redirectParams = [
    'status' => $result['action'] ?? null,
];

if ($nombre !== '') {
    $redirectParams['nombre'] = $nombre;
}

$redirect = documentoTipoDeleteBuildRedirectUrl(
    '../../view/documentotipo/documentotipo_list.php',
    $redirectParams
);

header('Location: ' . $redirect);
exit;
