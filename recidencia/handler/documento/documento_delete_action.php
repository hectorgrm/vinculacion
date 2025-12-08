<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/documento/documentofunctions_delete.php';
require_once __DIR__ . '/../../controller/documento/DocumentoDeleteController.php';

use Residencia\Controller\Documento\DocumentoDeleteController;

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';

if ($method !== 'POST') {
    $redirect = documentoDeleteBuildRedirectUrl(
        '../../view/documentos/documento_delete.php',
        [
            'id' => documentoDeleteNormalizeId($_GET['id'] ?? $_POST['id'] ?? null),
            'error' => 'method_not_allowed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$sanitized = documentoDeleteSanitizeInput($_POST);
$validationErrors = documentoDeleteValidateRequest($sanitized);

$documentId = $sanitized['id'];

if ($validationErrors !== []) {
    $redirectParams = [
        'id' => $documentId,
        'error' => $validationErrors[0],
    ];

    $redirect = documentoDeleteBuildRedirectUrl(
        '../../view/documentos/documento_delete.php',
        $redirectParams
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $controller = new DocumentoDeleteController();
} catch (\Throwable $exception) {
    $redirect = documentoDeleteBuildRedirectUrl(
        '../../view/documentos/documento_delete.php',
        [
            'id' => $documentId,
            'error' => 'controller',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $deletedDocument = $controller->deleteDocument($documentId);
} catch (\RuntimeException $exception) {
    $errorCode = match ($exception->getCode()) {
        404 => 'not_found',
        403 => 'empresa_completada',
        default => 'delete_failed',
    };

    $redirect = documentoDeleteBuildRedirectUrl(
        '../../view/documentos/documento_delete.php',
        [
            'id' => $documentId,
            'error' => $errorCode,
        ]
    );

    header('Location: ' . $redirect);
    exit;
} catch (\Throwable) {
    $redirect = documentoDeleteBuildRedirectUrl(
        '../../view/documentos/documento_delete.php',
        [
            'id' => $documentId,
            'error' => 'delete_failed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$projectRoot = dirname(__DIR__, 2);
$fileMeta = documentoDeleteBuildFileMeta(
    isset($deletedDocument['ruta']) ? (string) $deletedDocument['ruta'] : null,
    $projectRoot
);

if ($fileMeta['exists'] && isset($fileMeta['absolutePath'])) {
    $absolutePath = (string) $fileMeta['absolutePath'];
    if (!@unlink($absolutePath)) {
        error_log('[documento_delete] No se pudo eliminar el archivo en ' . $absolutePath);
    }
}

documentoDeleteLogMotivo($deletedDocument, $sanitized['motivo'] ?? null);

$empresaId = documentoDeleteNormalizeId($deletedDocument['empresa_id'] ?? $sanitized['empresa_id'] ?? null);

$redirectParams = [
    'deleted' => 1,
];

if ($empresaId !== null) {
    $redirectParams['empresa'] = $empresaId;
}

$redirect = documentoDeleteBuildRedirectUrl(
    '../../view/documentos/documento_list.php',
    $redirectParams
);

header('Location: ' . $redirect);
exit;

