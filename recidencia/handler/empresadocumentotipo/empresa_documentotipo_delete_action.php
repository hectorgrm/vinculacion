<?php

declare(strict_types=1);

require_once __DIR__ . '/../../common/functions/empresadocumentotipo/empresa_documentotipo_functions_delete.php';
require_once __DIR__ . '/../../controller/empresadocumentotipo/EmpresaDocumentoTipoDeleteController.php';

use Residencia\Controller\Empresadocumentotipo\EmpresaDocumentoTipoDeleteController;

$method = isset($_SERVER['REQUEST_METHOD']) ? strtoupper((string) $_SERVER['REQUEST_METHOD']) : 'GET';

$rawId = $_POST['id'] ?? ($_GET['id'] ?? null);
$rawEmpresa = $_POST['empresa_id'] ?? ($_POST['id_empresa'] ?? ($_GET['empresa_id'] ?? ($_GET['id_empresa'] ?? null)));

$documentoId = empresaDocumentoTipoDeleteNormalizeId($rawId);
$empresaId = empresaDocumentoTipoDeleteNormalizeEmpresaId($rawEmpresa);

$redirectBase = '../../view/empresadocumentotipo/empresa_documentotipo_delete.php';

if ($method !== 'POST') {
    $redirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
        $redirectBase,
        [
            'id' => $documentoId,
            'id_empresa' => $empresaId,
            'error' => 'method_not_allowed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$sanitized = empresaDocumentoTipoDeleteSanitizeInput($_POST);
$validationErrors = empresaDocumentoTipoDeleteValidateRequest($sanitized);

$documentoId = $sanitized['id'];
$empresaId = $sanitized['empresa_id'];

if ($validationErrors !== []) {
    $redirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
        $redirectBase,
        [
            'id' => $documentoId,
            'id_empresa' => $empresaId,
            'error' => $validationErrors[0],
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $controller = new EmpresaDocumentoTipoDeleteController();
} catch (\Throwable $exception) {
    $redirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
        $redirectBase,
        [
            'id' => $documentoId,
            'id_empresa' => $empresaId,
            'error' => 'controller',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

try {
    $result = $controller->deleteDocumento($documentoId, $empresaId);
} catch (RuntimeException $exception) {
    $code = $exception->getCode();
    $errorCode = match ($code) {
        404 => 'not_found',
        409 => 'inactive_not_supported',
        default => 'delete_failed',
    };

    $redirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
        $redirectBase,
        [
            'id' => $documentoId,
            'id_empresa' => $empresaId,
            'error' => $errorCode,
        ]
    );

    header('Location: ' . $redirect);
    exit;
} catch (\Throwable $exception) {
    $redirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
        $redirectBase,
        [
            'id' => $documentoId,
            'id_empresa' => $empresaId,
            'error' => 'delete_failed',
        ]
    );

    header('Location: ' . $redirect);
    exit;
}

$documento = $result['documento'] ?? [];
$nombre = empresaDocumentoTipoDeleteExtractNombre(is_array($documento) ? $documento : null);
$usageCount = (int) ($result['usageCount'] ?? 0);

$listRedirect = empresaDocumentoTipoDeleteBuildRedirectUrl(
    '../../view/empresadocumentotipo/empresa_documentotipo_list.php',
    [
        'id_empresa' => $empresaId,
        'status' => $result['action'] ?? null,
        'nombre' => $nombre !== '' ? $nombre : null,
        'usage' => $usageCount > 0 ? $usageCount : null,
    ]
);

header('Location: ' . $listRedirect);
exit;
