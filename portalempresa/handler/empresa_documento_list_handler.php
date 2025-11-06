<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaDocumentoListController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../common/functions/empresadocumentofunctions.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/EmpresaDocumentoListController.php';

$sessionEmpresa = portalEmpresaRequireSession('../view/login.php');
$empresaId = (int) ($sessionEmpresa['empresa_id'] ?? 0);

$controller = new EmpresaDocumentoListController();
$rawFilters = $_GET ?? [];
$errorMessage = null;

try {
    $payload = $controller->buildViewData($empresaId, $rawFilters);
} catch (\Throwable $exception) {
    $payload = [
        'empresa' => [
            'nombre' => (string) ($sessionEmpresa['empresa_nombre'] ?? ''),
        ],
        'documentos' => [],
        'kpis' => ['aprobado' => 0, 'pendiente' => 0, 'rechazado' => 0],
        'filters' => empresaDocumentoNormalizeFilters($rawFilters),
        'statusOptions' => empresaDocumentoStatusOptions(),
        'uploadOptions' => [],
    ];
    $errorMessage = 'No se pudo cargar la información de los documentos. Intenta nuevamente más tarde.';
}

$empresaNombre = $payload['empresa']['nombre'] ?? (string) ($sessionEmpresa['empresa_nombre'] ?? '');
$documentos = $payload['documentos'];
$kpis = $payload['kpis'];
$filterValues = $payload['filters'];
$statusOptions = $payload['statusOptions'];
$tiposDocumentos = $payload['uploadOptions'] ?? [];

$statusCode = isset($_GET['status']) ? (string) $_GET['status'] : '';
$documentLabel = isset($_GET['doc']) ? (string) $_GET['doc'] : null;
$reasonCode = isset($_GET['reason']) ? (string) $_GET['reason'] : null;
$uploadFlash = empresaDocumentoUploadStatusMessage($statusCode, $documentLabel, $reasonCode);

require __DIR__ . '/../view/documentos_list.php';
