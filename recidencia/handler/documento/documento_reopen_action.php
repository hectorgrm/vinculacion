<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunctions_reopen.php';
require_once __DIR__ . '/../../controller/documento/DocumentoReopenController.php';

use Residencia\Controller\Documento\DocumentoReopenController;

$redirectBase = '../../view/documentos/documento_view.php';

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';
$requestedId = documentoReopenNormalizeId($_GET['id'] ?? $_POST['id'] ?? null);

if ($method !== 'POST') {
    $redirect = documentoReopenBuildRedirectUrl($redirectBase, [
        'id' => $requestedId,
        'error' => 'method_not_allowed',
    ]);

    header('Location: ' . $redirect);
    exit;
}

$sanitized = documentoReopenSanitizeInput($_POST);
$documentId = $sanitized['id'];

if ($documentId === null) {
    $redirect = documentoReopenBuildRedirectUrl('../../view/documentos/documento_list.php', [
        'error' => 'invalid_id',
    ]);

    header('Location: ' . $redirect);
    exit;
}

try {
    $controller = new DocumentoReopenController();
} catch (\Throwable) {
    $redirect = documentoReopenBuildRedirectUrl($redirectBase, [
        'id' => $documentId,
        'error' => 'controller',
    ]);

    header('Location: ' . $redirect);
    exit;
}

try {
    $controller->reopenDocument($documentId);
} catch (\RuntimeException $exception) {
    $errorCode = match ($exception->getCode()) {
        404 => 'not_found',
        400 => 'invalid_status',
        default => 'reopen_failed',
    };

    $redirect = documentoReopenBuildRedirectUrl($redirectBase, [
        'id' => $documentId,
        'error' => $errorCode,
    ]);

    header('Location: ' . $redirect);
    exit;
} catch (\Throwable) {
    $redirect = documentoReopenBuildRedirectUrl($redirectBase, [
        'id' => $documentId,
        'error' => 'reopen_failed',
    ]);

    header('Location: ' . $redirect);
    exit;
}

$redirect = documentoReopenBuildRedirectUrl($redirectBase, [
    'id' => $documentId,
    'reopened' => 1,
]);

header('Location: ' . $redirect);
exit;
