<?php

declare(strict_types=1);

use PortalEmpresa\Controller\EmpresaDocumentoListController;

require_once __DIR__ . '/../../config/session.php';
require_once __DIR__ . '/../helpers/empresadocumentofunction.php';
require_once __DIR__ . '/../common/functions/empresadocumentofunctions.php';
require_once __DIR__ . '/../common/functions/portal_session_guard.php';
require_once __DIR__ . '/../controller/EmpresaDocumentoListController.php';

$sessionEmpresa = portalEmpresaRequireSession('../view/login.php');
$portalSession = $sessionEmpresa;
$empresaId = (int) ($sessionEmpresa['empresa_id'] ?? 0);
$portalReadOnly = portalEmpresaIsReadOnly($sessionEmpresa);
$portalReadOnlyMessage = $portalReadOnly
    ? 'La empresa esta en estatus Completada; el portal esta en modo de solo lectura.'
    : null;

$controller = new EmpresaDocumentoListController();
$rawFilters = $_GET ?? [];
$errorMessage = null;

try {
    $payload = $controller->buildViewData($empresaId, $rawFilters);
} catch (\Throwable $exception) {
    $payload = [
        'empresa' => [
            'nombre' => (string) ($sessionEmpresa['empresa_nombre'] ?? ''),
            'logo_path' => $sessionEmpresa['empresa_logo_path'] ?? null,
        ],
        'documentos' => [],
        'kpis' => ['aprobado' => 0, 'pendiente' => 0, 'rechazado' => 0],
        'documentosStats' => ['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0],
        'filters' => empresaDocumentoNormalizeFilters($rawFilters),
        'statusOptions' => empresaDocumentoStatusOptions(),
        'uploadOptions' => [],
    ];
    $errorMessage = 'No se pudo cargar la información de los documentos. Intenta nuevamente más tarde.';
}

$empresa = isset($payload['empresa']) && is_array($payload['empresa']) ? $payload['empresa'] : [];
if (!isset($empresa['logo_path']) || $empresa['logo_path'] === '') {
    $empresa['logo_path'] = $sessionEmpresa['empresa_logo_path'] ?? null;
}

$empresaNombre = $payload['empresa']['nombre'] ?? (string) ($sessionEmpresa['empresa_nombre'] ?? '');
$documentos = $payload['documentos'];
$kpis = $payload['kpis'];
$documentosStats = $payload['documentosStats'] ?? ['total' => 0, 'subidos' => 0, 'aprobados' => 0, 'porcentaje' => 0];
$filterValues = $payload['filters'];
$statusOptions = $payload['statusOptions'];
$tiposDocumentos = $payload['uploadOptions'] ?? [];

$statusCode = isset($_GET['status']) ? (string) $_GET['status'] : '';
$documentLabel = isset($_GET['doc']) ? (string) $_GET['doc'] : null;
$reasonCode = isset($_GET['reason']) ? (string) $_GET['reason'] : null;
$uploadFlash = empresaDocumentoUploadStatusMessage($statusCode, $documentLabel, $reasonCode);

require __DIR__ . '/../view/documentos_list.php';
