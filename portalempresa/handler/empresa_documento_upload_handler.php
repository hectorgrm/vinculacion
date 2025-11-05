<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaDocumentoUploadController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../common/functions/empresadocumentofunctions.php';
require_once __DIR__ . '/../controller/EmpresaDocumentoUploadController.php';

if (!isset($_SESSION['portal_empresa']) || !is_array($_SESSION['portal_empresa'])) {
    header('Location: ../view/login.php');
    exit;
}

$sessionEmpresa = $_SESSION['portal_empresa'];
$empresaId = isset($sessionEmpresa['empresa_id']) ? (int) $sessionEmpresa['empresa_id'] : 0;

if ($empresaId <= 0) {
    header('Location: ../view/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: empresa_documento_list_handler.php');
    exit;
}

$docTipoRaw = isset($_POST['doc_tipo']) ? (string) $_POST['doc_tipo'] : '';
$docTipo = empresaDocumentoUploadParseOptionValue($docTipoRaw);

if ($docTipo === null) {
    header('Location: empresa_documento_list_handler.php?status=upload_invalid_tipo');
    exit;
}

$comentario = isset($_POST['comentario']) ? (string) $_POST['comentario'] : '';
$fileData = isset($_FILES['archivo']) && is_array($_FILES['archivo']) ? $_FILES['archivo'] : [];

$controller = new EmpresaDocumentoUploadController();

try {
    $result = $controller->processUpload(
        $empresaId,
        $docTipo['scope'],
        $docTipo['id'],
        $fileData,
        $comentario
    );
} catch (\Throwable $exception) {
    $result = [
        'success' => false,
        'status_code' => 'upload_server_error',
    ];
}

$statusCode = (string) ($result['status_code'] ?? 'upload_server_error');
$documentName = isset($result['document_name']) ? (string) $result['document_name'] : '';
$reason = isset($result['reason']) ? (string) $result['reason'] : null;

$query = ['status' => $statusCode];

if ($documentName !== '') {
    $query['doc'] = $documentName;
}

if ($reason !== null && $reason !== '') {
    $query['reason'] = $reason;
}

$redirect = 'empresa_documento_list_handler.php';

if ($query !== []) {
    $redirect .= '?' . http_build_query($query);
}

header('Location: ' . $redirect);
exit;
